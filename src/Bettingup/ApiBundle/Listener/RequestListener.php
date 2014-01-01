<?php

namespace Bettingup\ApiBundle\Listener;

use Symfony\Component\HttpKernel\Event\GetResponseEvent,
    Symfony\Component\HttpKernel\HttpKernelInterface,
    Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

use Bettingup\ApiBundle\Exception\ApiTokenException,
    Bettingup\ApiBundle\Exception\ApiTokenNotFoundException,
    Bettingup\ApiBundle\Exception\ApiException,
    Bettingup\ApiBundle\Service\Login;

class RequestListener
{
    private $login;

    public function __construct(Login $login)
    {
        $this->login = $login;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();
        $method  = $request->getMethod();

        $route = $request->attributes->get('_route');

        if ($event->getRequestType() !== HttpKernelInterface::MASTER_REQUEST || !preg_match('/^api/', $route)) {
            return;
        }

        $token = $request->query->get('token', null);

        if (null === $token) {
            throw new ApiTokenNotFoundException;
        }

        if (in_array($method, ['POST', 'PUT', 'PATCH', 'DELETE'])) {
            if (0 === $request->request->count()) {
                $data = json_decode($request->getContent(), true);

                if (JSON_ERROR_NONE !== json_last_error()) {
                    switch (json_last_error()) {
                        case JSON_ERROR_DEPTH:
                            $message = 'The maximum stack depth has been exceeded';
                            break;

                        case JSON_ERROR_STATE_MISMATCH:
                            $message = 'Invalid or malformed JSON';
                            break;

                        case JSON_ERROR_CTRL_CHAR:
                            $message = 'Control character error, possibly incorrectly encoded';
                            break;

                        case JSON_ERROR_SYNTAX:
                            $message = 'Syntax error';
                            break;

                        case JSON_ERROR_UTF8:
                            $message = 'Malformed UTF-8 characters, possibly incorrectly encoded';
                            break;
                    }

                    throw new BadRequestHttpException($message);
                }

                if (null !== $data) {
                    $request->request->add($data);
                }
            }
        }

        $this->login->authenticate($token);
    }
}
