<?php

namespace App\Repository;

use App\Entity\EmailProvider;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method EmailProvider|null find($id, $lockMode = null, $lockVersion = null)
 * @method EmailProvider|null findOneBy(array $criteria, array $orderBy = null)
 * @method EmailProvider[]    findAll()
 * @method EmailProvider[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EmailProviderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EmailProvider::class);
    }

    public function getProviderQuery(\DateTime $date)
    {
        return $this->createQueryBuilder('ep')
            ->andWhere('ep.executedAt <= :date')
            ->setParameter('date', $date)
            ->orderBy('ep.executedAt', 'DESC')
            ->getQuery()
        ;
    }

    // /**
    //  * @return EmailProvider[] Returns an array of EmailProvider objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?EmailProvider
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
