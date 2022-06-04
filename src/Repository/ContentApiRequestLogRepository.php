<?php

namespace App\Repository;

use App\Entity\ContentApiRequestLog;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ContentApiRequestLog|null find($id, $lockMode = null, $lockVersion = null)
 * @method ContentApiRequestLog|null findOneBy(array $criteria, array $orderBy = null)
 * @method ContentApiRequestLog[]    findAll()
 * @method ContentApiRequestLog[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContentApiRequestLogRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ContentApiRequestLog::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(ContentApiRequestLog $entity, bool $flush = true): void
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
    public function remove(ContentApiRequestLog $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function countRequestsOfEachType($user): array
    {
        $getCount = $this->createQueryBuilder('c')
            ->andWhere('c.user = :user')
            ->andWhere('c.operation = :operation')
            ->setParameter('user', $user)
            ->setParameter('operation', 'GET')
            ->select('COUNT(c.id) as count')
            ->getQuery()
            ->getSingleScalarResult();

        $postCount = $this->createQueryBuilder('c')
            ->andWhere('c.user = :user')
            ->andWhere('c.operation = :operation')
            ->setParameter('user', $user)
            ->setParameter('operation', 'POST')
            ->select('COUNT(c.id) as count')
            ->getQuery()
            ->getSingleScalarResult();

        $deleteCount = $this->createQueryBuilder('c')
            ->andWhere('c.user = :user')
            ->andWhere('c.operation = :operation')
            ->setParameter('user', $user)
            ->setParameter('operation', 'DELETE')
            ->select('COUNT(c.id) as count')
            ->getQuery()
            ->getSingleScalarResult();

        $putCount = $this->createQueryBuilder('c')
            ->andWhere('c.user = :user')
            ->andWhere('c.operation = :operation')
            ->setParameter('user', $user)
            ->setParameter('operation', 'PUT')
            ->select('COUNT(c.id) as count')
            ->getQuery()
            ->getSingleScalarResult();

        $patchCount = $this->createQueryBuilder('c')
            ->andWhere('c.user = :user')
            ->andWhere('c.operation = :operation')
            ->setParameter('user', $user)
            ->setParameter('operation', 'PATCH')
            ->select('COUNT(c.id) as count')
            ->getQuery()
            ->getSingleScalarResult();

        file_put_contents("SOMELOG.log", print_r($getCount, true).PHP_EOL, FILE_APPEND);

        return [
            'getCount' => $getCount,
            'postCount' => $postCount,
            'deleteCount' => $deleteCount,
            'putCount' => $putCount,
            'patchCount' => $patchCount,
        ];
    }

    // /**
    //  * @return ContentApiRequestLog[] Returns an array of ContentApiRequestLog objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ContentApiRequestLog
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
