<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        try {
            $data = json_decode($request->getContent(), true);
            $email = $data['email']; 
            $password = $data['password'];
            
            $user = new User();
            $user->setEmail($email);
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                        $user,
                        $password,
                    )
                );

            $entityManager->persist($user);
            $entityManager->flush();

            $message = 'Account successfully created';
        } catch (\Exception $e) {
            $message = $e->getMessage();
        }
        
        return $this->json([
            'message' => $message,
        ]);
    }
}
