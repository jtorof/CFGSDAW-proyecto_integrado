<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, ValidatorInterface $validator, EntityManagerInterface $entityManager): Response
    {
        $error = false;
        $message = false;

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

            $errors = $validator->validate($user);
            file_put_contents('SOMELOG.LOG', print_r($errors, true).PHP_EOL, FILE_APPEND);
            if (count($errors) > 0) {
                $error = (string) $errors;
                //$error = 'Validation error';
                file_put_contents('SOMELOG.LOG', "Entra en if".PHP_EOL, FILE_APPEND);
            } else {
                // Commented cause testing things
                $entityManager->persist($user);
                $entityManager->flush();
    
                $message = 'Account successfully created';
            }

        } catch (\Exception $e) {
            file_put_contents('SOMELOG.LOG', "Catch".PHP_EOL, FILE_APPEND);
            $error = $e->getMessage();
        }
        
        return $this->json([
            'message' => $message,
            'error' => $error,
        ]);
    }
}
