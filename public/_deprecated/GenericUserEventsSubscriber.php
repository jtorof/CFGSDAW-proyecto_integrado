<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\RateLimiter\RateLimiterFactory;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;

use App\Event\GenericUserEvents;
use Symfony\Contracts\EventDispatcher\Event;

class GenericUserEventsSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private Security $security,
        private RateLimiterFactory $contentApiLimiter,
    ) 
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [GenericUserEvents::REQUEST_RESET_LIMITER => 'onRequestingResetLimiter'];
    }

    public function onRequestingResetLimiter(Event $event): void
    {
        file_put_contents('REQUEST_RESET.LOG', print_r("REQUEST_RESET_LIMITER:", true).PHP_EOL, FILE_APPEND);
        
        $userIdentifier = $this->security->getUser()->getUserIdentifier();
        $userRoles = $this->security->isGranted("ROLE_ADMIN");
     
        $limiter = $this->contentApiLimiter->create($userIdentifier);
        $limiter->reset();

        file_put_contents('REQUEST_RESET.LOG', print_r($limiter->consume(0)->getRemainingTokens(), true).PHP_EOL.PHP_EOL, FILE_APPEND);
        
        if (false === $limiter->consume(1)->isAccepted()) {
            throw new TooManyRequestsHttpException();
        }

    }
}