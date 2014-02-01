<?php

namespace Bettingup\UserBundle\Controller;

use InvalidArgumentException;

use Symfony\Component\HttpFoundation\Request,
    Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

use FOS\RestBundle\Controller\FOSRestController;

use Bettingup\UserBundle\Entity\User,
    Bettingup\UserBundle\Form\Api\UserType,

    Bettingup\CoreBundle\Exception\FormNotValidException;

class ApiUserController extends FOSRestController
{
    public function getUserAction($slug)
    {
        return $this->view($this->get('bettingup.api.user')->get($slug), 200);
    }

    public function postUserAction(Request $request)
    {
        try {
            $user = $this->get('bettingup.api.user')->create($request->request->all());
        } catch (FormNotValidException $e) {
            throw new BadRequestHttpException($e->getErrors());
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        return $this->view($user->toArray(), 201);
    }
}
