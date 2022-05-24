<?php

namespace App\Controller;

use App\Entity\ApiContentUser;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class CustomCollectionGet extends AbstractController
{
    private $customCollectionGetHandler;

    public function __construct(CustomCollectionGetHandler $customCollectionGetHandler)
    {
        $this->customCollectionGetHandler = $customCollectionGetHandler;
    }

    public function __invoke(ApiContentUser $data): ApiContentUser
    {
        $this->customCollectionGetHandler->handle($data);

        return $data;
    }
}