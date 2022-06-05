<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\RateLimiter\RateLimiterFactory;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;
use App\Entity\ContentApiRequestLog;
use App\Entity\User;
use App\Entity\ApiToken;
use App\Repository\ContentApiRequestLogRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;

class ContentApiRequestSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private Security $security,
        private RateLimiterFactory $contentApiLimiter,
        private EntityManagerInterface $entityManager,
        private HubInterface $hub,
    ) 
    {
    }

    private function setTokenDisabled(ApiToken $apiKey): void 
    {
        $apiKey->setIsEnabled(false);
        $this->entityManager->persist($apiKey);
        $this->entityManager->flush();
    }

    private function setTokenEnabled(ApiToken $apiKey): void 
    {
        $apiKey->setIsEnabled(true);
        $this->entityManager->persist($apiKey);
        $this->entityManager->flush();
    }

    private function getUserStats(ContentApiRequestLogRepository $contentApiRequestLogRepository): array
    {
        return $contentApiRequestLogRepository->countRequestsOfEachType($this->security->getUser());
    }

    private function publishUpdate(): void
    {
        $userIdentifier = $this->security->getUser()->getUserIdentifier();
        $update = new Update(
            "apiparapracticar.com/user/$userIdentifier",
            json_encode([
                "stats" => $this->getUserStats($this->entityManager->getRepository(ContentApiRequestLog::class)),
                'apiKeyIsEnabled' => $this->security->getUser()->getApiTokens()[0]->getIsEnabled(),
            ]),
        );

        file_put_contents("TESTHUB.LOG", print_r(
            $this->hub->getUrl()."-".
            $this->hub->getPublicUrl(), true).PHP_EOL, FILE_APPEND);
        $this->hub->publish($update);
    }


    private function rateLimit(String $identifier, User $user = null): void
    {
        $limiter = $this->contentApiLimiter->create($identifier);
        
        if (false === $limiter->consume(1)->isAccepted()) {
            if ($user) {
                $this->setTokenDisabled($user->getApiTokens()[0]);
                $this->publishUpdate();
            }
            throw new TooManyRequestsHttpException();
        }

        return;
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

        if (!preg_match('/^\/api\/user.*$/', $requestUri)) {
            return; //TODO: consider rate limiting whole site
        }

        if (!$user) {            
            $this->rateLimit($request->getClientIp());

            return;
        }
        
        $userIdentifier = $user->getUserIdentifier();

        $this->rateLimit($userIdentifier, $user);
        
        // If it reaches here, stores operation info to database, sets apikey enabled
        $this->setTokenEnabled($user->getApiTokens()[0]);
        $log = new ContentApiRequestLog();
        $log->setUser($user);
        $log->setOperation($method);

        $this->entityManager->persist($log);
        $this->entityManager->flush();

        $this->publishUpdate();

        return;             
    }    
}