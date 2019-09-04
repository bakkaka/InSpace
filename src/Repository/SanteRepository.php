<?php

namespace App\Repository;

use App\Entity\Sante;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Sante|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sante|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sante[]    findAll()
 * @method Sante[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SanteRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Sante::class);
    }

    public function findAllSantes($str){
        return $this->getEntityManager()
            ->createQuery(
                'SELECT s
                FROM App:Sante s
                WHERE s.firstName LIKE :str'
            )
            ->setParameter('str', '%'.$str.'%')
            ->getResult();
    }

    // /**
    //  * @return Sante[] Returns an array of Sante objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Sante
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
