<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Http\Event\LogoutEvent;

class LogoutSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [LogoutEvent::class => 'onLogout'];
    }

    public function onLogout(LogoutEvent $event): void
    {
        // Json response if Content-type of the logout request is set to application/json in fetch()
        if (str_contains($event->getRequest()->getContentType(), 'json')) {
            $event->setResponse(new JsonResponse([
                'message' => 'Sesión cerrada',
            ]));
        }
    }
}