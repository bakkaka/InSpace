<?php

namespace App\Repository;

use App\Entity\Author;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Author|null find($id, $lockMode = null, $lockVersion = null)
 * @method Author|null findOneBy(array $criteria, array $orderBy = null)
 * @method Author[]    findAll()
 * @method Author[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AuthorRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Author::class);
    }

     /**
      * @return Author[] Returns an array of Author objects
     */
    
    public function findOneByUsernmae($username) : Author
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.user = :user')
            ->setParameter('username', $username)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
	
	 public function findAuthorWithUser()
    {

        $qb = $this->createQueryBuilder('a')
            //->leftJoin('a.gender', 'g')
            //->addSelect('g')
            //->leftJoin('a.cities', 'c')
           // ->addSelect('c')
            ->leftJoin('a.user', 'u')
            ->addSelect('u')
            ->leftJoin('a.image', 'i')
            ->addSelect('i')
            //->leftJoin('a.articlecategories', 'ac')
            //->addSelect('ac')

        ;
        $qb->andWhere('a.user = :user')
            ->setParameter('user', $user)
        ;
        $qb->orderBy('a.date', 'DESC')
        ;
        return $qb

            ->getQuery()

            ->getResult()

            ;
    }
    

    /*
    public function findOneBySomeField($value): ?Author
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
