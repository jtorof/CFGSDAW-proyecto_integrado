<?php

namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\ApiContentUser;
use App\Entity\Test;
use App\Entity\ApiContentAddress;
use App\Entity\ApiContentPhone;
use App\Repository\ApiContentUserRepository;
use Symfony\Component\Security\Core\Security;

final class ApiContenUserPersister implements ContextAwareDataPersisterInterface
{
    private $decoratedDataPersister;
    private $entityManager;
    private $security;

    public function __construct(DataPersisterInterface $decoratedDataPersister, EntityManagerInterface $entityManager, Security $security)
    {
        $this->decoratedDataPersister = $decoratedDataPersister;
        $this->entityManager = $entityManager;
        $this->security = $security;
    }

    public function supports($data, array $context = []): bool
    {
        return $this->decoratedDataPersister->supports($data, $context);
    }

    public function persist($data, array $context = [])
    {
        // $result = $this->decoratedDataPersister->persist($data, $context);

        // file_put_contents('SOMELOG2.LOG', print_r($data, true).PHP_EOL, FILE_APPEND);
        // file_put_contents('SOMELOG2.LOG', print_r($data->getPhones(), true).PHP_EOL, FILE_APPEND);
        // file_put_contents('SOMELOG2.LOG', print_r($data, true).PHP_EOL, FILE_APPEND);
        // file_put_contents('SOMELOG2.LOG', print_r($data->getAddress(), true).PHP_EOL, FILE_APPEND);
        // die();

        if (!$data instanceof ApiContentUser) {
            return;
        }
               
        if (($context['collection_operation_name'] ?? null) === 'post') {
            if ($address = $data->getAddress()) {
                $this->entityManager->persist($address);
                // $this->entityManager->flush();
            }

            if ($phones = $data->getPhones()) {
                foreach ($phones as $phone) {
                    $this->entityManager->persist($phone);
                    // $this->entityManager->flush();
                }
            }
        }

        if (($context['item_operation_name'] ?? null) === 'put') {
            file_put_contents('SOMELOG2.LOG', print_r("put", true).PHP_EOL, FILE_APPEND);
            die();

            if ($address = $data->getAddress()) {
                $this->entityManager->persist($address);
                $this->entityManager->flush();
            }
        }

        // return;
        $data->setUser($this->security->getUser());
        $this->entityManager->persist($data);
        $this->entityManager->flush();

        return $data;
    }

    public function remove($data, array $context = [])
    {
        return $this->decoratedDataPersister->remove($data, $context);
    }
}
