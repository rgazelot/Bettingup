<?php

namespace Bettingup\TicketBundle\Form\Api\Ticket;

use Symfony\Component\Form\AbstractType,
    Symfony\Component\Form\FormBuilderInterface,
    Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Bettingup\TicketBundle\Form\Api\AbstractTicketType,
    Bettingup\TicketBundle\Form\Api\BetType;

class SimpleType extends AbstractTicketType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder->add('bets', 'collection', [
            'type'         => new BetType(),
            'allow_add'    => true,
            'by_reference' => false,
        ]);
        $builder->add('amount', 'number', ['mapped' => false]);
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);

        $resolver->setDefaults([
            'data_class' => 'Bettingup\\TicketBundle\\Entity\\Ticket\\Simple',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'simple';
    }
}
