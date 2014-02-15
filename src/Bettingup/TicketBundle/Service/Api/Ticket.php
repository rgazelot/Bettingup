<?php

namespace Bettingup\TicketBundle\Service\Api;

use DomainException,
    InvalidArgumentException;

use Doctrine\ORM\EntityManager,
    Doctrine\ORM\NoResultException;

use Symfony\Component\Form\FormFactory,
    Symfony\Component\Form\FormTypeInterface;

use Bettingup\TicketBundle\Entity\Ticket as TicketEntity,
    Bettingup\TicketBundle\Entity\AbstractTicket,
    Bettingup\TicketBundle\Entity\Bet,
    Bettingup\TicketBundle\Exception\InvalidSetOfBetsException,
    Bettingup\TicketBundle\Exception\TicketNotFoundException,
    Bettingup\TicketBundle\Form\Api\Ticket as TicketType,

    Bettingup\CoreBundle\Exception\FormNotValidException,

    Bettingup\UserBundle\Entity\User;

class Ticket
{
    private $em;
    private $formFactory;

    public function __construct(EntityManager $em, FormFactory $formFactory)
    {
        $this->em = $em;
        $this->formFactory = $formFactory;
    }

    /**
     * Retrieve a collection of tickets from a user's hash
     *
     * @param  string $hash
     *
     * @return AbstractTicket[]
     */
    public function all($hash)
    {
        return $this->em->getRepository('BettingupTicketBundle:AbstractTicket')->all($hash);
    }

    /**
     * Get a ticket
     *
     * @param  string $hash
     *
     * @return AbstractTicket
     */
    public function get($hash)
    {
        try {
            return $this->em->getRepository('BettingupTicketBundle:AbstractTicket')->get($hash);
        } catch (NoResultException $e) {
            throw new TicketNotFoundException;

        }
    }

    /**
     * Delete a ticket
     *
     * @param  AbstractTicket $ticket
     */
    public function delete(AbstractTicket $ticket)
    {
        $this->em->remove($ticket);
        $this->em->flush();
    }

    /**
     * Create a Ticket and its Bets
     *
     * @param  array  $data
     * @param  User   $owner
     *
     * @return AbstractTicket
     */
    public function create(array $data, User $owner)
    {
        if (!isset($data['type'])) {
            throw new DomainException("type key is missing");
        }

        switch ($data['type']) {
            case 'simple':
                $ticket = $this->createSimple($data);
                break;

            case 'combine':
                $ticket = $this->createCombine($data);
                break;

            case 'system':
                $ticket = $this->createSystem($data);
                break;

            default:
                throw new DomainException;
        }

        $ticket->setUser($owner);

        $this->em->persist($ticket);
        $this->em->flush();

        return $ticket;
    }

    /**
     * Create a simple Ticket
     *
     * @param  array  $data
     *
     * @throws DomainException           If amount key doesn't exists
     * @throws InvalidSetOfBetsException If there is more than one bet
     *
     * @return Simple
     */
    private function createSimple(array $data)
    {
        if (!isset($data['amount'])) {
            throw new DomainException("amount key is missing");
        }

        $ticket = new TicketEntity\Simple;
        $this->bindTicket(new TicketType\SimpleType, $ticket, $data);

        if ($ticket->getBets()->count() !== 1) {
            throw new InvalidSetOfBetsException('A simple ticket must have only one bet.');
        }

        $ticket->setAmount($data['amount'])
            ->setProfit($this->computeProfiteForSimpleAndCombine($ticket));

        return $ticket;
    }

    /**
     * Create Combine
     *
     * @param  array  $data
     *
     * @return Combine
     */
    private function createCombine(array $data)
    {
        if (!isset($data['amount'])) {
            throw new DomainException("amount key is missing");
        }

        $ticket = new TicketEntity\Combine;
        $this->bindTicket(new TicketType\CombineType, $ticket, $data);

        if ($ticket->getBets()->count() <= 1) {
            throw new InvalidSetOfBetsException('A combine ticket must have at least two bets.');
        }

        $ticket->setAmount($data['amount']);

        $totalOdds = 1;
        $ticket->getBets()->map(function(Bet $bet) use (&$totalOdds) {
            $totalOdds = round($bet->getOdds() * $totalOdds, 2);
        });
        $ticket->setTotalOdds($totalOdds);

        return $ticket;
    }

    private function createSystem(array $data)
    {

    }

    /**
     * Calculate the profit for simple and combine type
     *
     * @param  TicketEntity $ticket The current ticket
     *
     * @return float
     */
    private function computeProfiteForSimpleAndCombine(AbstractTicket $ticket)
    {
        $profit = 1;

        foreach ($ticket->getBets() as $bet) {
            $profit = true === $bet->getStatus() ? $bet->getOdds() * $profit : 0;
        }

        return ($profit * $ticket->getAmount()) - $ticket->getAmount();
    }

    /**
     * Create informations about a system. Combinaisons, profit max, etc.
     *
     * @param  System    $ticket       The current system ticket
     * @param  integer   $betPerMatch  The amout of the bet per match
     * @param  string    $choose       The size of each combinaisons
     *
     * @return array
     */
    private function combineSystemData(TicketEntity\System $ticket, $betPerMatch, $choose)
    {
        $odds  = [];
        $banksOdds = [];

        foreach ($ticket->getBets() as $bet) {
            if (true === $bet->getIsBank()) {
                $banksOdds[] = $bet->getOdds();

                continue;
            }

            $odds[] = $bet->getOdds();
        }

        $combinaisons = [];
        $combinaisonsComputed = $this->extractCombinaisons($odds, $choose);

        // Here add the banksOdds in odds array for the futur search
        $odds = array_merge($odds, $banksOdds);

        foreach ($combinaisonsComputed as $combinaison) {

            // We add the banks for all combinaisons
            foreach ($banksOdds as $bankOdds) {
                $combinaison[] = $bankOdds;
            }

            $totalOdds = 1;
            $matches = [];

            foreach ($combinaison as $odd) {
                $totalOdds *= $odd;
                $matches[] = array_search($odd, $odds) + 1;
            }

            $combinaisons[] = [
                'combine' => $matches,
                'odds'    => round($totalOdds, 2),
            ];
        }

        $profit = 0;

        foreach ($combinaisons as $combinaison) {
            $profit += $combinaison['odds'] * $betPerMatch;
        }

        return [$combinaisons, round($profit, 2)];
    }

    /**
     * Extract combinaisons
     *
     * @param  array   $odds   Array of odds from bets
     * @param  integer $choose The size of each combinaisons
     *
     * @return array
     */
    private function extractCombinaisons(array $odds, $choose)
    {
        if (!filter_var($choose, FILTER_VALIDATE_INT, ['options' => ['min_range' => 0]]) || $choose > count($odds) - 1) {
            throw new InvalidArgumentException;
        }

        $result = [];
        $combination = [];
        $n = count($odds);
        $this->inner(0, $choose, $odds, $n, $result, $combination);

        return $result;
    }

    /**
     * Recursive function for extractCombinaisons
     */
    private function inner($start, $choose_, $arr, $n, &$result, &$combination) {
        if ($choose_ == 0) {
            array_push($result,$combination);
        } else for ($i = $start; $i <= $n - $choose_; ++$i) {
            array_push($combination, $arr[$i]);
            $this->inner($i + 1, $choose_ - 1, $arr, $n, $result, $combination);
            array_pop($combination);
        }
    }

    private function bindTicket(FormTypeInterface $formType, AbstractTicket $ticket, array $data)
    {
        $form = $this->formFactory->create($formType, $ticket)->submit($data, false);

        if (!$form->isValid()) {
            throw new FormNotValidException($form);
        }
    }
}
