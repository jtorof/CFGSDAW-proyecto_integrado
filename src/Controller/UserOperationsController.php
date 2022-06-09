<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\RateLimiter\RateLimiterFactory;
use App\Repository\ApiContentUserRepository;
use App\Repository\ApiTokenRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\ApiToken;
use App\Service\HubPublisher;

#[Route('/user/operations')]
class UserOperationsController extends AbstractController
{
    public function __construct(
        private RateLimiterFactory $contentApiLimiter,
        private EntityManagerInterface $entityManager,
        private $apiTokenPassphrase,  
        private HubPublisher $hubPublisher,
    ) 
    {
    }
    
    #[Route('/', name: 'app_user_operations')]
    public function index(): Response
    {
        return $this->redirectToRoute('app_main');
    }

    #[Route('/reset-rate-limiter', name: 'reset_rate_limiter', methods: ['POST'])]
    public function resetRateLimiter(): Response
    {
        $limiter = $this->contentApiLimiter->create($this->getUser()->getUserIdentifier());
        $limiter->reset();
        $this->getUser()->getApiTokens()[0]->setIsEnabled(true);
        $entityManager = $this->entityManager;
        $entityManager->persist($this->getUser());
        $entityManager->flush();

        $this->hubPublisher->publishUpdate();

        return $this->json(
            [ 'message' => "Ya puede volver a acceder" ], 
            Response::HTTP_OK,
        );
    }

    #[Route('/generate-data', name: 'generate_data', methods: ['POST'])]
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

        $apiContentUserRepository->cloneMasterData($this->getUser());

        $loggedUser->setHasApiDataCopy(true);
        $entityManager->persist($loggedUser);
        $entityManager->flush();

        $this->hubPublisher->publishUpdate();

        return $this->json(
            [ 'message' => 'Ya dispone de los datos' ], 
            Response::HTTP_OK,
        );
    }

    #[Route('/reset-data', name: 'reset_data', methods: ['POST'])]
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

        $apiContentUserRepository->deleteUserData($this->getUser());
        
        $apiContentUserRepository->cloneMasterData($this->getUser());

        return $this->json(
            ['message' => 'Datos regenerados'], 
            Response::HTTP_OK,
        );
    }

    #[Route('/generate-api-key', name: 'generate_api_key', methods: ['POST'])]
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

        $this->hubPublisher->publishUpdate();

        return $this->json(
            [ 'message' => 'Api Key generada' ], 
            Response::HTTP_OK,
        );
    }  

    // #[Route('/get-user-info', name: 'get_user_info')]
    // public function getUserInfo(ContentApiRequestLogRepository $contentApiRequestLogRepository): Response
    // {
    //     $user = $this->getUser();              
    //     if ($token = $user->getApiTokens()[0]) {            
    //         $string = $token->getToken();       
    //         $apiKeyIsEnabled = $token->getIsEnabled();
    //         $iv = substr($string, 0, 16);
    //         $token = substr($string, 16);
    //         $decodedApiToken = openssl_decrypt($token, "aes-256-cbc", $this->apiTokenPassphrase, 0, $iv);
    //     } else {
    //         $iv = "";
    //         $decodedApiToken = ""; 
    //         $apiKeyIsEnabled = false; 
    //     }
    //     // file_put_contents('TOKENS.LOG', print_r($iv.$decodedApiToken, true).PHP_EOL, FILE_APPEND);

    //     $stats = $contentApiRequestLogRepository->countRequestsOfEachType($user);
    //     $userHasApiDataCopy = $this->getUser()->getHasApiDataCopy();

    //     return $this->json(
    //         [ 
    //             'stats' => $stats,
    //             'apiKey' => $iv.$decodedApiToken,
    //             'apiKeyIsEnabled' => $apiKeyIsEnabled,
    //             'userHasApiDataCopy' => $userHasApiDataCopy,
    //         ], 
    //         Response::HTTP_OK,
    //     );
    // }
}
