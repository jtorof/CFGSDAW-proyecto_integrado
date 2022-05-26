<?php

namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\ApiContentUser;
use App\Repository\ApiContentUserRepository;
use App\Repository\ApiContentAddressRepository;
use App\Repository\ApiContentPhoneRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Security;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;

final class ApiContenUserPersister implements ContextAwareDataPersisterInterface
{
    public function __construct(
        private DataPersisterInterface $decoratedDataPersister, 
        private EntityManagerInterface $entityManager, 
        private EntityManagerInterface $entityManager2, 
        private Security $security, 
        private UserRepository $userRepository,
        private ApiContentUserRepository $apiContentUserRepository,
        private ApiContentAddressRepository $apiContentAddressRepository
    )
    {
    }

    public function supports($data, array $context = []): bool
    {
        return $this->decoratedDataPersister->supports($data, $context);
    }

    public function persist($data, array $context = [])
    {
        if (!$data instanceof ApiContentUser) {
            return;
        }

        $userIdentifier = $this->security->getUser()->getUserIdentifier();
        $loggedUserObject = $this->userRepository->findOneBy(
            [ "email" => $userIdentifier ],
        );
               
        if (($context['collection_operation_name'] ?? null) === 'post') {
            $apiContentUserCollection = $this->apiContentUserRepository->findBy(
                [ "user" => $loggedUserObject->getId() ],
                [ "publicId" => "DESC" ],
            );
            
            if (count($apiContentUserCollection) >= 15 ) { // TODO: Adjust object limit
                return new JsonResponse(
                    [ "message" => "Error: Se ha alcanzado el límite de objetos permitidos. Elimine algunos para añadir objetos nuevos." ],
                    Response::HTTP_UNAUTHORIZED,
                );
            }
            
            $lastPublicId = $apiContentUserCollection[0]->getPublicId();
            $data->setPublicId($lastPublicId ? $lastPublicId + 1 : 1);

            if ($address = $data->getAddress()) {
                $address->setApiContentUser($data);
                $this->entityManager->persist($address);
            }

            if ($phones = $data->getPhones()) {
                foreach ($phones as $phone) {
                    $this->entityManager->persist($phone);
                }
            }

            $data->setUser($this->security->getUser());
        }

        if (($context['item_operation_name'] ?? null) === 'put') {

            $previousAddress = $this->apiContentAddressRepository->findOneBy(
                [ "apiContentUser" => $data->getId() ],
            );
            $address = $data->getAddress();

            if ($address) { //cuando tenía O le mando
                if ($previousAddress) { // Tenía
                    if ($address->getId() !== $previousAddress->getId()) { // Tenía Y le mando algo nuevo
                        // file_put_contents('SOMELOG3.LOG', print_r("Tenía Y actualizo", true).PHP_EOL, FILE_APPEND);       
                        $this->entityManager2->remove($previousAddress);
                        $this->entityManager2->flush();
                        $address->setApiContentUser($data);
                    } else { //Tenía pero no le mando
                        // file_put_contents('SOMELOG3.LOG', print_r("Tenía pero no actualizo", true).PHP_EOL, FILE_APPEND);                        
                    }
                } else { // no Tenía -> le estoy mandando
                    // file_put_contents('SOMELOG3.LOG', print_r("No tenía le meto de nuevo", true).PHP_EOL, FILE_APPEND);  
                    $address->setApiContentUser($data);
                }
            } else { //(no tenía Y no le mando) O le digo que borre
                if ($previousAddress) {
                    // file_put_contents('SOMELOG3.LOG', print_r("Tenía Y borro", true).PHP_EOL, FILE_APPEND);       
                        $this->entityManager2->remove($previousAddress);
                        $this->entityManager2->flush();
                } else {
                    // file_put_contents('SOMELOG3.LOG', print_r("no tenía Y no le mando, O no tenía y le digo que borre", true).PHP_EOL, FILE_APPEND);
                }
            }

            if ($phones = $data->getPhones()) {
                foreach ($phones as $phone) {
                    $this->entityManager->persist($phone);
                }
            }
        }

        if (($context['item_operation_name'] ?? null) === 'patch') {
            return $this->decoratedDataPersister->persist($data, $context);
        }

        $this->entityManager->persist($data);
        $this->entityManager->flush();
    }

    public function remove($data, array $context = [])
    {
        return $this->decoratedDataPersister->remove($data, $context);
    }
}
