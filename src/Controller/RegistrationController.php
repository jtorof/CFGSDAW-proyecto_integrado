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
            //file_put_contents('SOMELOG.LOG', print_r($data, true).PHP_EOL, FILE_APPEND);
            $email = $data['email']; 
            $password = $data['password1'];
            
            $user = new User();
            $user->setEmail($email);
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                        $user,
                        $password,
                    )
                );

            // Commented cause testing things
            // $entityManager->persist($user);
            // $entityManager->flush();

            $message = 'Account successfully created';
        } catch (\Exception $e) {
            $message = $e->getMessage();
        }
        
        return $this->json([
            'message' => $message,
        ]);
    }
}
