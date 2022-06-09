<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ContentApiRequestLogRepository;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\RateLimiter\RateLimiterFactory;

class SecurityController extends AbstractController
{
    public function __construct(
        private $apiTokenPassphrase,
    ) 
    {
    }        

    #[Route('/login', name: 'app_login', methods: ['POST'])]
    public function login(SerializerInterface $serializer, ContentApiRequestLogRepository $contentApiRequestLogRepository, RateLimiterFactory $contentApiLimiter): Response
    {
        if (!$this->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->json([
                'error' => 'Invalid login request: check that the Content-Type header is "application/json".'
            ], Response::HTTP_BAD_REQUEST);
        }

        $retryAfter = null;
        $user = $this->getUser();              
        if ($token = $user->getApiTokens()[0]) {            
            $string = $token->getToken();       
            $apiKeyIsEnabled = $token->getIsEnabled();
            if (!$apiKeyIsEnabled) {
                $limiter = $contentApiLimiter->create($this->getUser()->getUserIdentifier());
                $limit = $limiter->consume(1);
                $retryAfter = $limit->getRetryAfter()->getTimestamp() - time();
            }
            $iv = substr($string, 0, 16);
            $token = substr($string, 16);
            $decodedApiToken = openssl_decrypt($token, "aes-256-cbc", $this->apiTokenPassphrase, 0, $iv);
        } else {
            $iv = "";
            $decodedApiToken = ""; 
            $apiKeyIsEnabled = null; 
        }

        $stats = $contentApiRequestLogRepository->countRequestsOfEachType($user);          
        $userHasApiDataCopy = $this->getUser()->getHasApiDataCopy();    
        $user = $serializer->serialize($this->getUser(), 'json', ['groups' => 'userInfo']);
        $userInfo = [
            'stats' => $stats,
            'apiKey' => $iv . $decodedApiToken,
            'apiKeyIsEnabled' => $apiKeyIsEnabled,
            'userHasApiDataCopy' => $userHasApiDataCopy,
            'retryAfter' => $retryAfter,
        ];

        return $this->json(
            [
                'user' => $user, 
                'userInfo' => $userInfo,
            ],
            Response::HTTP_OK, 
            [], 
            ['groups' => 'userInfo']);
    }

    #[Route('/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
