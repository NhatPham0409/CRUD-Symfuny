<?php

namespace App\Repository;

use App\Entity\ClassRoom;
use App\Entity\Student;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Student>
 *
 * @method Student|null find($id, $lockMode = null, $lockVersion = null)
 * @method Student|null findOneBy(array $criteria, array $orderBy = null)
// * @method Student[]    findAll()
 * @method Student[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StudentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Student::class);
    }

    public function findAll(): array
    {
        return $this->createQueryBuilder('s')
            ->orderBy('s.id', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @throws \Exception
     */
    public function findStudentByFields(array $criteria): array
    {
        $queryBuilder = $this->createQueryBuilder('s');

        // Join with Class entity
        $queryBuilder->leftJoin('s.classList', 'c');

        foreach ($criteria as $key => $value) {
            // Get metadata for Student and Class entities
            $studentMetadata = $this->getClassMetadata();
            $classMetadata = $this->getEntityManager()->getClassMetadata(ClassRoom::class);

            // Check if the key belongs to Student entity
            if (isset($studentMetadata->fieldMappings[$key])) {
                // If $key is "dob" and $value is a year (4 digits)
                if ($key == "dob" && preg_match('/^\d{4}$/', $value)) {
                    $startDate = new DateTime("$value-01-01");
                    $endDate = new DateTime("$value-12-31");

                    $queryBuilder
                        ->andWhere("s.$key BETWEEN :start_date AND :end_date")
                        ->setParameter('start_date', $startDate->format('Y-m-d'))
                        ->setParameter('end_date', $endDate->format('Y-m-d'));
                    /*
                    // Check the data type of the dob column
                    $dobColumn = $studentMetadata->fieldMappings[$key];
                    if ($dobColumn['type'] === 'date') {
                        $queryBuilder
                            ->andWhere("s.$key BETWEEN :start_date AND :end_date")
                            ->setParameter('start_date', $startDate->format('Y-m-d'))
                            ->setParameter('end_date', $endDate->format('Y-m-d'));
                    }
                    else {
                        $queryBuilder
                            ->andWhere("s.$key BETWEEN :start_date AND :end_date")
                            ->setParameter('start_date', $startDate)
                            ->setParameter('end_date', $endDate);
                    }*/
                    continue;
                }
                $queryBuilder
                    ->andWhere("s.$key LIKE :$key")
                    ->setParameter($key, '%' . $value . '%');
            }
            // Check if the key belongs to Class entity
            elseif (isset($classMetadata->fieldMappings[$key])) {
                // Nếu $key là "dob" và $value là một năm (4 chữ số)
                /*if ($key == "dob" && preg_match('/^\d{4}$/', $value)) {
                    // Nếu $key là "dob" và $value là một năm (4 chữ số)
                    $startDate = new DateTime("$value-01-01");
                    $endDate = new DateTime("$value-12-31");

                    // Kiểm tra kiểu dữ liệu của cột dob
                    $dobColumn = $studentMetadata->fieldMappings[$key];
                    if ($dobColumn['type'] === 'date') {
                        $queryBuilder
                            ->andWhere("s.$key BETWEEN :start_date AND :end_date")
                            ->setParameter('start_date', $startDate->format('Y-m-d'))
                            ->setParameter('end_date', $endDate->format('Y-m-d'));
                    } else {
                        $queryBuilder
                            ->andWhere("s.$key BETWEEN :start_date AND :end_date")
                            ->setParameter('start_date', $startDate)
                            ->setParameter('end_date', $endDate);
                    }
                    continue;
                }*/
                $queryBuilder
                    ->andWhere("c.$key LIKE :$key")
                    ->setParameter($key, '%' . $value . '%');
            }
        }
        return $queryBuilder->getQuery()->getResult();
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
}
