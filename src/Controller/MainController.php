<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use App\Repository\ContentApiRequestLogRepository;
use Symfony\Component\RateLimiter\RateLimiterFactory;

class MainController extends AbstractController
{
    public function __construct(
        private $apiTokenPassphrase,
    ) {
    }

    #[Route('/{reactRouting?}', name: 'app_main', priority: "-1", requirements: ['reactRouting' => '.+'])]
    public function index(SerializerInterface $serializer, ContentApiRequestLogRepository $contentApiRequestLogRepository,  RateLimiterFactory $contentApiLimiter): Response
    {
        $user = $this->getUser();
        if ($user) {
            $userHasApiDataCopy = $this->getUser()->getHasApiDataCopy();
        } else {
            $userHasApiDataCopy = null;
        }

        if ($user) {
            $stats = $contentApiRequestLogRepository->countRequestsOfEachType($user);
            $retryAfter = null;
            $userInfo = [
                'stats' => $stats,
                'userHasApiDataCopy' => $userHasApiDataCopy,
            ];
            if ($token = $user->getApiTokens()[0]) {

                $string = $token->getToken();
                $apiKeyIsEnabled = $token->getIsEnabled();
                if (!$apiKeyIsEnabled) {
                    $limiter = $contentApiLimiter->create($user->getUserIdentifier());
                    $limit = $limiter->consume(1);
                    $retryAfter = $limit->getRetryAfter()->getTimestamp() - time();
                }
                $iv = substr($string, 0, 16);
                $token = substr($string, 16);
                $decodedApiToken = openssl_decrypt($token, "aes-256-cbc", $this->apiTokenPassphrase, 0, $iv);
                $userInfo += [
                    'apiKey' => $iv . $decodedApiToken,
                    'apiKeyIsEnabled' => $apiKeyIsEnabled,
                    'retryAfter' => $retryAfter,
                ];
            } else {
                $userInfo += [
                    'apiKey' => "",
                    'apiKeyIsEnabled' => null,
                    'retryAfter' => $retryAfter,
                ];
            }
        } else {
            $userInfo = [
                'stats' => null,
                'apiKey' => "",
                'apiKeyIsEnabled' => null,
                'userHasApiDataCopy' => $userHasApiDataCopy,
                'retryAfter' => null,
            ];
        }
        // file_put_contents('TOKENS.LOG', print_r($iv.$decodedApiToken, true).PHP_EOL, FILE_APPEND);

        return $this->render('main/index.html.twig', [
            'user' => $serializer->serialize($this->getUser(), 'json', ['groups' => 'userInfo']),
            'userInfo' => $serializer->serialize($userInfo, 'json'),
        ]);
    }
}
