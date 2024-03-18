<?php

namespace App\Repository;

use App\Entity\Classes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Classes>
 *
 * @method Classes|null find($id, $lockMode = null, $lockVersion = null)
 * @method Classes|null findOneBy(array $criteria, array $orderBy = null)
 * @method Classes[]    findAll()
 * @method Classes[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClassesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Classes::class);
    }

        /**
         * @return Classes[] Returns an array of Classes objects
//         */
//        public function findByFields($criteria): array
//        {
////            return $this->createQueryBuilder('c')
////                ->andWhere('c.exampleField = :val')
////                ->setParameter('val', $value)
////                ->orderBy('c.id', 'ASC')
////                ->setMaxResults(10)
////                ->getQuery()
////                ->getResult()
////            ;
//
//            $queryBuilder = $this->createQueryBuilder('c');
//
//            foreach ($criteria as $field => $value){
//                $queryBuilder->andWhere("c.$field = :$field")->setParameter($field, $value);
//            }
//
//            return $queryBuilder->getQuery()->getResult();
//
//        }
    /**
     * Find classes by specific fields with pagination.
     *
     * @param array $criteria
     * @param int $page
     * @param int $perPage
     * @return Paginator
     */
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

    //    public function findOneBySomeField($value): ?Classes
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
