<?php

namespace App\DataProvider;

use ApiPlatform\Core\DataProvider\ContextAwareCollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Entity\ApiContentUser;
use App\Repository\ApiContentUserRepository;
use App\Repository\UserRepository;
use ApiPlatform\Core\DataProvider\ItemDataProviderInterface;
use Symfony\Component\Security\Core\Security;

final class ApiContentUserProvider implements ContextAwareCollectionDataProviderInterface, RestrictedDataProviderInterface, ItemDataProviderInterface
{
    public function __construct(private ApiContentUserRepository $repository, private UserRepository $userRepository, private Security $security) 
    {
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return ApiContentUser::class === $resourceClass;
    }

    public function getCollection(string $resourceClass, string $operationName = null, array $context = []): iterable
    {
        $userIdentifier = $this->security->getUser()->getUserIdentifier();
        $loggedUserObject = $this->userRepository->findOneBy(
            [ "email" => $userIdentifier ],
        );
        $collection = $this->repository->findBy(
            [ "user" =>  $loggedUserObject->getId() ], 
            [ "publicId" => "ASC" ],
        );

        return $collection;
    }

    public function getItem(string $resourceClass, $identifier, string $operationName = null, array $context = []): ?ApiContentUser
    {
        $userIdentifier = $this->security->getUser()->getUserIdentifier();
        $loggedUserObject = $this->userRepository->findOneBy(
            [ "email" => $userIdentifier ],
        );
        $item = $this->repository->findOneBy([
            "user" => $loggedUserObject->getId(),  
            "publicId" => $identifier,
        ]);

        return $item;
    }
}