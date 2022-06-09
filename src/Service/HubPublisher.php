<?php

namespace App\Service;

use Symfony\Component\Security\Core\Security;
use Symfony\Component\RateLimiter\RateLimiterFactory;
use App\Entity\ContentApiRequestLog;
use App\Repository\ContentApiRequestLogRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;

class HubPublisher
{
    public function __construct(
        private Security $security,
        private RateLimiterFactory $contentApiLimiter,
        private EntityManagerInterface $entityManager,
        private HubInterface $hub,
        private $apiTokenPassphrase,
    ) {
    }

    public function publishUpdate(): void
    {
        $user = $this->security->getUser();
        $userIdentifier = $user->getUserIdentifier();
        $userHasApiDataCopy = $user->getHasApiDataCopy();

        if ($apiKeyObject = $user->getApiTokens()[0]) {
            $codedApiKey = $apiKeyObject->getToken();
            $iv = substr($codedApiKey, 0, 16);
            $token = substr($codedApiKey, 16);
            $decodedApiToken = openssl_decrypt($token, "aes-256-cbc", $this->apiTokenPassphrase, 0, $iv);
            $apiKeyIsEnabled = $apiKeyObject->getIsEnabled(); //
        } else {
            $iv = "";
            $decodedApiToken = "";
            $apiKeyIsEnabled = "null";
        }
       
        $retryAfter = null;
        if (!$apiKeyIsEnabled) {
            $limiter = $this->contentApiLimiter->create($userIdentifier);
            $limit = $limiter->consume(1);
            $retryAfter = $limit->getRetryAfter()->getTimestamp() - time();
        }

        $update = new Update(
            "apiparapracticar.com/user/$userIdentifier",
            json_encode([
                'stats' => $this->entityManager->getRepository(ContentApiRequestLog::class)->countRequestsOfEachType($user),
                'apiKeyIsEnabled' => $apiKeyIsEnabled,
                'retryAfter' => $retryAfter,
                'apiKey' => $iv . $decodedApiToken,
                'userHasApiDataCopy' => $userHasApiDataCopy,
            ]),
        );


        $this->hub->publish($update);
    }
}
