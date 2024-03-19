<?php

namespace App\Repository;

use App\Entity\Student;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Student>
 *
 * @method Student|null find($id, $lockMode = null, $lockVersion = null)
 * @method Student|null findOneBy(array $criteria, array $orderBy = null)
 * @method Student[]    findAll()
 * @method Student[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StudentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Student::class);
    }

    //    /**
    //     * @return Student[] Returns an array of Student objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('s.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Student
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
    public function findByFieldsPaginated(array $criteria,int $page = 1,int $perPage = 5): Paginator
    {
        $queryBuilder = $this->createQueryBuilder('c');

        foreach ($criteria as $field => $value) {
            // Example: If $field is 'class_name', the query will be "c.class_name = :class_name"
            $queryBuilder->andWhere("c.$field LIKE :$field")->setParameter($field,'%'.$value.'%');
        }

//        return $queryBuilder->getQuery()->getResult();
        // Calculate offset based on the page number and number of items per page
        $offset = ($page - 1) * $perPage;

        // Set the limit and offset for pagination
        $queryBuilder->setMaxResults($perPage)->setFirstResult($offset);

        // Create a Paginator instance to paginate the query results
        return new Paginator($queryBuilder->getQuery(), $fetchJoinCollection = true);
    }
}
