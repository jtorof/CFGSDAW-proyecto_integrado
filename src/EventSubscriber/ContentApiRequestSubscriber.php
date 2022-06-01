<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\RateLimiter\RateLimiterFactory;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Http\EventListener\DefaultLogoutListener;
use App\Entity\ContentApiRequestLog;
use Doctrine\ORM\EntityManagerInterface;

class ContentApiRequestSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private Security $security,
        private RateLimiterFactory $contentApiLimiter,
        private TokenStorageInterface $tokenStorage,
        private DefaultLogoutListener $defaultLogoutListener,
        private EntityManagerInterface $entityManager,
    ) 
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            RequestEvent::class => 'onKernelRequest',
        ];
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();
        $requestUri = $request->getRequestUri();
        $method = $request->getMethod();
        $user = $this->security->getUser();

        // file_put_contents('REQUEST.LOG', print_r($_SERVER['SERVER_NAME']."-".$request->getHost(), true).PHP_EOL, FILE_APPEND);
        // file_put_contents('REQUEST.LOG', print_r($_SERVER['HTTP_HOST']."-".$request->getHost(), true).PHP_EOL, FILE_APPEND);

        if (!preg_match('/^\/api\/user.*$/', $requestUri)) {
            // file_put_contents('REQUEST.LOG', print_r("nomatch-$requestUri", true).PHP_EOL.PHP_EOL, FILE_APPEND);
            return; //TODO: consider rate limiting whole site
        }

        // file_put_contents('REQUEST.LOG', print_r("match-$requestUri", true).PHP_EOL, FILE_APPEND);
        // file_put_contents('REQUEST.LOG', print_r($request->getSchemeAndHttpHost(), true).PHP_EOL, FILE_APPEND);

        if (!$user) {
            file_put_contents('REQUEST.LOG', print_r("noUser", true).PHP_EOL, FILE_APPEND);
            $limiter = $this->contentApiLimiter->create($request->getClientIp());
        
            if (false === $limiter->consume(1)->isAccepted()) {
                throw new TooManyRequestsHttpException();
            }

            return;
        }
        
        $userIdentifier = $user->getUserIdentifier();
        $limiter = $this->contentApiLimiter->create($userIdentifier);
                   
        if (false === $limiter->consume(1)->isAccepted()) {
            throw new TooManyRequestsHttpException();
        }

        // If reaches here, stores operation info to database
        $log = new ContentApiRequestLog();
        $log->setUser($user);
        $log->setOperation($method);

        $this->entityManager->persist($log);
        $this->entityManager->flush();

        return;             
    }
}