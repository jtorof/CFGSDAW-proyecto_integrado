<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\RateLimiter\RateLimiterFactory;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;


#[Route('/user/operations')]
class UserOperationsController extends AbstractController
{
    public function __construct(
        private RateLimiterFactory $contentApiLimiter,
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
        if (!$loggedUser = $this->getUser()) {
            throw new AccessDeniedException("Acceso Denegado");            
        }
        $limiter = $this->contentApiLimiter->create($loggedUser->getUserIdentifier());
        $limiter->reset();

        return $this->json(
            ['message' => 'Ya puede volver a acceder'], 
            200
        );
    }

    #[Route('/generate-data', name: 'generate_data')]
    public function generateData(): Response
    {


        return $this->json(
            ['message' => ''], 
            200
        );
    }

    #[Route('/reset-data', name: 'reset_data')]
    public function resetData(): Response
    {
        

        return $this->json(
            ['message' => ''], 
            200
        );
    }
}
