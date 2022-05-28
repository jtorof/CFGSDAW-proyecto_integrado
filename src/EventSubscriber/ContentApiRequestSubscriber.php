<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\FinishRequestEvent;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\RateLimiter\RateLimiterFactory;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Http\Event\LogoutEvent;
use Symfony\Component\Security\Http\EventListener\DefaultLogoutListener;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
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
            TooManyRequestsHttpException::class => 'onTooManyRequestsHttpException',
            RequestEvent::class => 'onKernelRequest',
        ];
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();
        $requestUri = $request->getRequestUri();
        $method = $request->getMethod();
        $user = $this->security->getUser();

        if (!preg_match('/^\/api\/user(\/\d+)*$/', $requestUri)) {
            file_put_contents('REQUEST.LOG', print_r("nomatch", true).PHP_EOL.PHP_EOL, FILE_APPEND);
            return; //TODO: consider rate limiting whole site
        }

        file_put_contents('REQUEST.LOG', print_r("match", true).PHP_EOL, FILE_APPEND);

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

        file_put_contents('REQUEST.LOG', print_r($userIdentifier, true).PHP_EOL.PHP_EOL, FILE_APPEND);
                   
        if (false === $limiter->consume(1)->isAccepted()) {
            throw new TooManyRequestsHttpException();
        }

        // If reaches here, stores operation info to database
        $log = new ContentApiRequestLog();
        $log->setUser($user);
        $log->setOperation($method);

        $this->entityManager->persist($log);
        $this->entityManager->flush();

        // then logout
        $logoutEvent = new LogoutEvent(Request::create('/logout'), $this->tokenStorage->getToken());
        $dispatcher = new EventDispatcher();
        $listener = $this->defaultLogoutListener;
        $dispatcher->addListener("Symfony\Component\Security\Http\Event\LogoutEvent", [$listener, 'onLogout']);
        $dispatcher->dispatch($logoutEvent);

        $response = $logoutEvent->getResponse();
        if (!$response instanceof Response) {
            throw new \RuntimeException('No logout listener set the Response, make sure at least the DefaultLogoutListener is registered.');
        }

        $this->tokenStorage->setToken(null); // actual logout  

        return;             
    }    

    public function onTooManyRequestsHttpException(TooManyRequestsHttpException $event): void
    {
        // logout also after throwing 429
        $logoutEvent = new LogoutEvent(Request::create('/logout'), $this->tokenStorage->getToken());
        if (!$logoutEvent) {
            throw new \RuntimeException('No $logoutEvent');
        }
        $dispatcher = new EventDispatcher();
        $listener = $this->defaultLogoutListener;
        $dispatcher->addListener("Symfony\Component\Security\Http\Event\LogoutEvent", [$listener, 'onLogout']);
        $dispatcher->dispatch($logoutEvent);

        $response = $logoutEvent->getResponse();
        if (!$response instanceof Response) {
            throw new \RuntimeException('No logout listener set the Response, make sure at least the DefaultLogoutListener is registered.');
        }

        $this->tokenStorage->setToken(); // actual logout  
    }
}