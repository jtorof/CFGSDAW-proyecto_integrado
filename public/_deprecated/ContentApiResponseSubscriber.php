<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\RateLimiter\RateLimiterFactory;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;

class ContentApiResponseSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private Security $security,
        private RateLimiterFactory $contentApiLimiter,
    ) 
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [ResponseEvent::class => 'onResponse'];
    }

    public function onResponse(ResponseEvent $event): void
    {
        file_put_contents('RESPONSE.LOG', print_r("Response:", true).PHP_EOL, FILE_APPEND);
        file_put_contents('RESPONSE.LOG', print_r($event->getResponse(), true).PHP_EOL, FILE_APPEND);
        $request = $event->getRequest();
        $requestUri = $request->getRequestUri();
        $method = $request->getMethod();

        if (!$this->security->getUser()) {
            $limiter = $this->contentApiLimiter->create($request->getClientIp());
        
            if (false === $limiter->consume(1)->isAccepted()) {
                throw new TooManyRequestsHttpException();
            }

            return;
        }
        $userIdentifier = $this->security->getUser()->getUserIdentifier();
        $userRoles = $this->security->isGranted("ROLE_ADMIN");
        file_put_contents('RESPONSE.LOG', print_r("$requestUri-$method-$userIdentifier", true).PHP_EOL, FILE_APPEND);
        file_put_contents('RESPONSE.LOG', print_r($userRoles, true).PHP_EOL, FILE_APPEND);
        file_put_contents('RESPONSE.LOG', print_r($request->getHost(), true).PHP_EOL.PHP_EOL, FILE_APPEND);
        // file_put_contents('RESPONSE.LOG', print_r($request->headers, true).PHP_EOL.PHP_EOL, FILE_APPEND);

        // if (preg_match('/^\/api\/user(\/\d+)*$/', $request->getRequestUri())) {
        //     file_put_contents('LOGINREQUEST.LOG', print_r("match", true).PHP_EOL.PHP_EOL, FILE_APPEND);
        // }

        $resetLimiter = $event->getResponse()->headers->get("reset-limiter");
     
        $limiter = $this->contentApiLimiter->create($userIdentifier);
        $limiter->reset();

        if ($resetLimiter) {
            file_put_contents('RESPONSE.LOG', print_r("resetLimiter", true).PHP_EOL, FILE_APPEND);
            $limiter->reset();
        }

        file_put_contents('RESPONSE.LOG', print_r($limiter->consume(0)->getRemainingTokens(), true).PHP_EOL.PHP_EOL, FILE_APPEND);
        
        if (false === $limiter->consume(1)->isAccepted()) {
            throw new TooManyRequestsHttpException();
        }

    }
}