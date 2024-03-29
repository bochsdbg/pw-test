<?php

namespace App\Repository;

use App\Entity\InvitationCode;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method InvitationCode|null find($id, $lockMode = null, $lockVersion = null)
 * @method InvitationCode|null findOneBy(array $criteria, array $orderBy = null)
 * @method InvitationCode[]    findAll()
 * @method InvitationCode[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InvitationCodeRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, InvitationCode::class);
    }

    public function findOneByCode($code): ?InvitationCode
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.code = :code')
            ->setParameter('code', $code)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    // /**
    //  * @return InvitationCode[] Returns an array of InvitationCode objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?InvitationCode
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
