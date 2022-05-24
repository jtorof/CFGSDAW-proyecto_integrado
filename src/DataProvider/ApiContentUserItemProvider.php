<?php

namespace App\DataProvider;

use ApiPlatform\Core\DataProvider\ItemDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Entity\ApiContentUser;
use App\Repository\ApiContentUserRepository;

final class ApiContentUserItemProvider implements ItemDataProviderInterface, RestrictedDataProviderInterface
{
    public function __construct(private ApiContentUserRepository $repository) 
    {
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return ApiContentUser::class === $resourceClass;
    }

    public function getItem(string $resourceClass, $identifier, string $operationName = null, array $context = []): ?ApiContentUser
    {
        // Retrieve the blog post item from somewhere then return it or null if not found
        $item = $this->repository->findOneBy([
            "user" => null,  //TODO: change to logged user
            "publicId" => $identifier,
        ]);

        return $item;
    }
}