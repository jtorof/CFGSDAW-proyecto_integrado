<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\RateLimiter\RateLimiterFactory;
use App\Repository\ApiContentUserRepository;
use App\Repository\ApiTokenRepository;
use App\Repository\ContentApiRequestLogRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\ApiToken;

#[Route('/user/operations')]
class UserOperationsController extends AbstractController
{
    public function __construct(
        private RateLimiterFactory $contentApiLimiter,
        private EntityManagerInterface $entityManager,
        private $apiTokenPassphrase,
    ) 
    {
    }
    
    #[Route('/', name: 'app_user_operations')]
    public function index(): Response
    {
        return $this->redirectToRoute('app_main');
    }

    #[Route('/reset-rate-limiter', name: 'reset_rate_limiter')]
    public function resetRateLimiter(): Response
    {
        $limiter = $this->contentApiLimiter->create($this->getUser()->getUserIdentifier());
        $limiter->reset();

        return $this->json(
            [ 'message' => "Ya puede volver a acceder" ], 
            Response::HTTP_OK,
        );
    }

    #[Route('/generate-data', name: 'generate_data')]
    public function generateData(ApiContentUserRepository $apiContentUserRepository): Response
    {
        $loggedUser = $this->getUser();
        $entityManager = $this->entityManager;

        if ($loggedUser->getHasApiDataCopy()) {
            return $this->json(
                [ 'message' => 'Ya ha activado su copia de los datos' ], 
                Response::HTTP_FORBIDDEN,
            );           
        }

        $apiContentUserRepository->cloneMasterData();

        $loggedUser->setHasApiDataCopy(true);
        $entityManager->persist($loggedUser);
        $entityManager->flush();

        return $this->json(
            [ 'message' => 'Ya dispone de los datos' ], 
            Response::HTTP_OK,
        );
    }

    #[Route('/reset-data', name: 'reset_data')]
    public function resetData(ApiContentUserRepository $apiContentUserRepository): Response
    {
        $loggedUser = $this->getUser();

        if ($this->isGranted('ROLE_ADMIN')) {
            return $this->json(
                [ 'message' => 'El admin no puede resetear sus datos' ], 
                Response::HTTP_FORBIDDEN,
            );           
        }

        if (!$loggedUser->getHasApiDataCopy()) {
            return $this->json(
                [ 'message' => 'Aún no ha activado su copia de los datos' ], 
                Response::HTTP_FORBIDDEN,
            );           
        }

        $apiContentUserRepository->deleteUserData();
        
        $apiContentUserRepository->cloneMasterData();

        return $this->json(
            ['message' => 'Datos regenerados'], 
            Response::HTTP_OK,
        );
    }

    #[Route('/generate-api-key', name: 'generate_api_key')]
    public function generateApiKey(ApiTokenRepository $apiTokenRepository): Response
    {
        $generatedApiTokens = $apiTokenRepository->findBy(
            [ "owner" => $this->getUser()->getId() ]
        );
        if (count($generatedApiTokens) >= 1) {
            return $this->json(
                [ 'message' => "No puede generar más Api Keys" ], 
                Response::HTTP_FORBIDDEN,
            );
        }
        $apiToken = new ApiToken;
        $string = $this->getUser()->getUserIdentifier().openssl_random_pseudo_bytes(16);
        $encodedApiToken = openssl_encrypt($string, "RC4", $this->apiTokenPassphrase);
        $iv = substr(openssl_encrypt(openssl_random_pseudo_bytes(16), "RC4", $this->apiTokenPassphrase), 0, 16);
        $toBeStoredApiToken = $iv.openssl_encrypt($encodedApiToken, "aes-256-cbc", $this->apiTokenPassphrase, 0, $iv);
        $apiToken->setToken($toBeStoredApiToken);
        $apiToken->setOwner($this->getUser());
        $this->entityManager->persist($apiToken);
        $this->entityManager->flush();

        return $this->json(
            [ 'message' => 'Api Key generada' ], 
            Response::HTTP_OK,
        );
    }  

    #[Route('/get-user-info', name: 'get_user_info')]
    public function getUserInfo(ContentApiRequestLogRepository $contentApiRequestLogRepository): Response
    {
        $string = $this->getUser()->getApiTokens()[0]->getToken();
        $iv = substr($string, 0, 16);
        $token = substr($string, 16);
        $decodedApiToken = openssl_decrypt($token, "aes-256-cbc", $this->apiTokenPassphrase, 0, $iv);
        file_put_contents('TOKENS.LOG', print_r($iv.$decodedApiToken, true).PHP_EOL, FILE_APPEND);

        $stats = $contentApiRequestLogRepository->countRequestsOfEachType($this->getUser());

        return $this->json(
            [ 
                'stats' => $stats,
                'apiKey' => $iv.$decodedApiToken,
            ], 
            Response::HTTP_OK,
        );
    }
}
