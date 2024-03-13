<?php

namespace App\Repository;

use App\Entity\Music;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use function PHPUnit\Framework\equalTo;

/**
 * @extends ServiceEntityRepository<Music>
 *
 * @method Music|null find($id, $lockMode = null, $lockVersion = null)
 * @method Music|null findOneBy(array $criteria, array $orderBy = null)
 * @method Music[]    findAll()
 * @method Music[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MusicRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Music::class);
    }

    /**
     * @return Music[] Returns an array of Music objects
     */
    public function findByExampleField($value): array
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
//            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }

    public function findOneBySomeField($value): ?Music
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findField(string $field): array
    {
        if ($field == 'songName')
            $field = 'm.songName';
        if ($field == 'author')
            $field = 'm.author';
        if ($field == 'album')
            $field = 'm.album';

        return $this->createQueryBuilder('m')
            ->select('m.id', $field)
            ->getQuery()
            ->getResult();
    }

    public function searchBySongName(string $value): array
    {
        $searchParam = $value . "%";
        return $this->createQueryBuilder('m')
            ->select('m.id', 'm.songName')
            ->andWhere('m.songName LIKE :val')
            ->setParameter('val', $searchParam)
            ->getQuery()
            ->getResult();
    }
}
