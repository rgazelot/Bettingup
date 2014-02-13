<?php

namespace Bettingup\TicketBundle\Exception;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TicketNotFoundException extends NotFoundHttpException
{
	public function __construct($message = null)
	{
		parent::__construct(null !== $message ? $message : 'Ticket not found');
	}
}