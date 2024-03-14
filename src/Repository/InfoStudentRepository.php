<?php

namespace App\Repository;

use App\Entity\InfoStudent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<InfoStudent>
 *
 * @method InfoStudent|null find($id, $lockMode = null, $lockVersion = null)
 * @method InfoStudent|null findOneBy(array $criteria, array $orderBy = null)
// * @method InfoStudent[]    findAll()
 * @method InfoStudent[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InfoStudentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InfoStudent::class);
    }

        /**
         * @return InfoStudent[] Returns an array of InfoStudent objects
         */
        public function findByExampleField($value): array
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

    public function findAll(): array
    {
        return $this->createQueryBuilder('i')
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
            ;
    }

        public function findOneBySomeField($value): ?InfoStudent
        {
            return $this->createQueryBuilder('i')
                ->andWhere('i.exampleField = :val')
                ->setParameter('val', $value)
                ->getQuery()
                ->getOneOrNullResult()
            ;
        }

    public function findOneById($value): ?InfoStudent
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.id = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }
}
