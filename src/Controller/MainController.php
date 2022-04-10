<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class MainController extends AbstractController
{
    #[Route('/{reactRouting?}', name: 'app_main', priority: "-1", requirements: ['reactRouting'=>'.+'])]
    public function index(SerializerInterface $serializer): Response
    {
        return $this->render('main/index.html.twig', [
            'user' => $serializer->serialize($this->getUser(), 'json'),
        ]);
    }
}
