<?php

namespace App\Repository;

use App\Entity\ApiContentUser;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ApiContentUser|null find($id, $lockMode = null, $lockVersion = null)
 * @method ApiContentUser|null findOneBy(array $criteria, array $orderBy = null)
 * @method ApiContentUser[]    findAll()
 * @method ApiContentUser[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ApiContentUserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ApiContentUser::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(ApiContentUser $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(ApiContentUser $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function cloneMasterData(User $user): void
    {
        $masterData = $this->findBy(
            [ "user" => 1 ]
        );
        foreach ($masterData as $item) {
            $this->_em->persist((clone $item)->setUser($user));
            $this->_em->flush();
        }
    }

    public function deleteUserData(User $user):void
    {
        $userData = $this->findBy(
            [ "user" => $user->getId() ]
        );
        foreach ($userData as $item) {
            $this->_em->remove($item);
            $this->_em->flush();
        }
    }

    // /**
    //  * @return ApiContentUser[] Returns an array of ApiContentUser objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ApiContentUser
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
