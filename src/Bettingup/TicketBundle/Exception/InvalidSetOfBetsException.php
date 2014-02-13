<?php

namespace Bettingup\TicketBundle\Exception;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class InvalidSetOfBetsException extends BadRequestHttpException
{
	public function __construct($message = null)
	{
		parent::__construct(null !== $message ? $message : 'An error occured with the set of bets.');
	}
}