<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Http\Event\LoginSuccessEvent; 
use Symfony\Component\Security\Core\Security;

class SuccessfulApiRequestSubscriber implements EventSubscriberInterface
{
    public function __construct(private Security $security) 
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [LoginSuccessEvent::class => 'onSuccessfulApiRequest'];
    }

    public function onSuccessfulApiRequest(LoginSuccessEvent $event): void
    {
        $request = $event->getRequest();
        $requestUri = $request->getRequestUri();
        $method = $request->getMethod();
        $userId = $this->security->getUser()->getUserIdentifier();
        file_put_contents('LOGINREQUEST.LOG', print_r($requestUri."-".$method, true).PHP_EOL, FILE_APPEND);
        file_put_contents('LOGINREQUEST.LOG', print_r($request->getHost(), true).PHP_EOL, FILE_APPEND);

        // if (preg_match('/^\/api\/user(\/\d+)*$/', $request->getRequestUri())) {
        //     file_put_contents('LOGINREQUEST.LOG', print_r("match", true).PHP_EOL.PHP_EOL, FILE_APPEND);
        // }

    }
}