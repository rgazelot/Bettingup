<?php

namespace Bettingup\ApiBundle\Tests\Listener;

use Symfony\Component\HttpKernel\HttpKernelInterface,
    Symfony\Component\HttpFoundation\ParameterBag;

use Bettingup\ApiBundle\Listener\RequestListener,
    Bettingup\CoreBundle\Traits\Tests\MockBuilder,
    Bettingup\UserBundle\Exception\UserNotFoundException,
    Bettingup\UserBundle\Entity\User;

class RequestListenerTest extends \PHPUnit_Framework_TestCase
{
    use MockBuilder;

    public function testIsntAMasterRequest()
    {
        $request = $this->getMockWithoutConstructor('Symfony\\Component\\HttpFoundation\\Request');
        $request->attributes = new ParameterBag;
        $request->attributes->set('_route', 'foo');

        $event = $this->getMockWithoutConstructor('Symfony\\Component\\HttpKernel\\Event\\GetResponseEvent');
        $event->expects(self::once())
            ->method('getRequest')
            ->will(self::returnValue($request));
        $event->expects(self::once())
            ->method('getRequestType')
            ->will(self::returnValue(HttpKernelInterface::SUB_REQUEST));

        (new RequestListener(
            $this->getMockWithoutConstructor('Bettingup\\ApiBundle\\Service\\Login')
        ))->onKernelRequest($event);
    }

    public function testIsntAnAPIRoute()
    {
        $request = $this->getMockWithoutConstructor('Symfony\\Component\\HttpFoundation\\Request');
        $request->attributes = new ParameterBag;
        $request->attributes->set('_route', 'foo');

        $event = $this->getMockWithoutConstructor('Symfony\\Component\\HttpKernel\\Event\\GetResponseEvent');
        $event->expects(self::once())
            ->method('getRequest')
            ->will(self::returnValue($request));
        $event->expects(self::once())
            ->method('getRequestType')
            ->will(self::returnValue(HttpKernelInterface::MASTER_REQUEST));

        (new RequestListener(
            $this->getMockWithoutConstructor('Bettingup\\ApiBundle\\Service\\Login')
        ))->onKernelRequest($event);
    }

    /**
     * @expectedException Bettingup\ApiBundle\Exception\ApiTokenNotFoundException
     */
    public function testThereIsntTokenPassed()
    {
        $request = $this->getMockWithoutConstructor('Symfony\\Component\\HttpFoundation\\Request');
        $request->attributes = new ParameterBag;
        $request->attributes->set('_route', 'api/1/foo');
        $request->query = new ParameterBag;

        $event = $this->getMockWithoutConstructor('Symfony\\Component\\HttpKernel\\Event\\GetResponseEvent');
        $event->expects(self::once())
            ->method('getRequest')
            ->will(self::returnValue($request));
        $event->expects(self::once())
            ->method('getRequestType')
            ->will(self::returnValue(HttpKernelInterface::MASTER_REQUEST));

        (new RequestListener(
            $this->getMockWithoutConstructor('Bettingup\\ApiBundle\\Service\\Login')
        ))->onKernelRequest($event);
    }

    /**
     * @expectedException Symfony\Component\HttpKernel\Exception\BadRequestHttpException
     * @expectedMessageException Syntax error
     */
    public function testInvalidJsonInContent()
    {
        $request = $this->getMockWithoutConstructor('Symfony\\Component\\HttpFoundation\\Request');
        $request->expects(self::once())
            ->method('getMethod')
            ->will(self::returnValue('POST'));
        $request->expects(self::once())
            ->method('getContent')
            ->will(self::returnValue('{wron_json}'));
        $request->attributes = new ParameterBag;
        $request->attributes->set('_route', 'api/1/foo');
        $request->query = new ParameterBag;
        $request->query->set('token', 'foo');
        $request->request = new ParameterBag;

        $event = $this->getMockWithoutConstructor('Symfony\\Component\\HttpKernel\\Event\\GetResponseEvent');
        $event->expects(self::once())
            ->method('getRequest')
            ->will(self::returnValue($request));
        $event->expects(self::once())
            ->method('getRequestType')
            ->will(self::returnValue(HttpKernelInterface::MASTER_REQUEST));

        (new RequestListener(
            $this->getMockWithoutConstructor('Bettingup\\ApiBundle\\Service\\Login')
        ))->onKernelRequest($event);
    }

    /**
     * @expectedException Bettingup\UserBundle\Exception\UserNotFoundException
     */
    public function testUserNotFound()
    {
        $request = $this->getMockWithoutConstructor('Symfony\\Component\\HttpFoundation\\Request');
        $request->expects(self::once())
            ->method('getMethod')
            ->will(self::returnValue('POST'));
        $request->expects(self::once())
            ->method('getContent')
            ->will(self::returnValue('{"foo":"bar"}'));
        $request->attributes = new ParameterBag;
        $request->attributes->set('_route', 'api/1/foo');
        $request->query = new ParameterBag;
        $request->query->set('token', 'foo');
        $request->request = new ParameterBag;

        $event = $this->getMockWithoutConstructor('Symfony\\Component\\HttpKernel\\Event\\GetResponseEvent');
        $event->expects(self::once())
            ->method('getRequest')
            ->will(self::returnValue($request));
        $event->expects(self::once())
            ->method('getRequestType')
            ->will(self::returnValue(HttpKernelInterface::MASTER_REQUEST));

        $login = $this->getMockWithoutConstructor('Bettingup\\ApiBundle\\Service\\Login');
        $login->expects(self::once())
            ->method('authenticate')
            ->with('foo')
            ->will(self::throwException(new UserNotFoundException));

        (new RequestListener(
            $login
        ))->onKernelRequest($event);
    }

    public function testAuthenticated()
    {
        $request = $this->getMockWithoutConstructor('Symfony\\Component\\HttpFoundation\\Request');
        $request->expects(self::once())
            ->method('getMethod')
            ->will(self::returnValue('POST'));
        $request->expects(self::once())
            ->method('getContent')
            ->will(self::returnValue('{"foo":"bar"}'));
        $request->attributes = new ParameterBag;
        $request->attributes->set('_route', 'api/1/foo');
        $request->query = new ParameterBag;
        $request->query->set('token', 'foo');
        $request->request = new ParameterBag;

        $event = $this->getMockWithoutConstructor('Symfony\\Component\\HttpKernel\\Event\\GetResponseEvent');
        $event->expects(self::once())
            ->method('getRequest')
            ->will(self::returnValue($request));
        $event->expects(self::once())
            ->method('getRequestType')
            ->will(self::returnValue(HttpKernelInterface::MASTER_REQUEST));

        $login = $this->getMockWithoutConstructor('Bettingup\\ApiBundle\\Service\\Login');
        $login->expects(self::once())
            ->method('authenticate')
            ->with('foo')
            ->will(self::returnValue(new User));

        (new RequestListener(
            $login
        ))->onKernelRequest($event);

        $this->assertEquals('bar', $request->request->get('foo', null));
    }
}
