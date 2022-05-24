<?php

namespace App\DataProvider;

use ApiPlatform\Core\DataProvider\ContextAwareCollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Entity\ApiContentUser;
use App\Repository\ApiContentUserRepository;

final class ApiContentUserCollectionProvider implements ContextAwareCollectionDataProviderInterface, RestrictedDataProviderInterface
{
    public function __construct(private ApiContentUserRepository $repository) 
    {
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return ApiContentUser::class === $resourceClass;
    }

    public function getCollection(string $resourceClass, string $operationName = null, array $context = []): iterable
    {
        $collection = $this->repository->findBy([
            "user" => null,  //TODO: change to logged user
        ]);

        return $collection;
    }
}