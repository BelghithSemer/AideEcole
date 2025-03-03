<?php

namespace App\Repository;

use App\Entity\Quiz;
use App\Entity\User;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Quiz>
 */
class QuizRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Quiz::class);
    }
    public function findAllByUserId(int $userId): array
{
    return $this->createQueryBuilder('q')
        ->innerJoin('q.user', 'u')
        ->where('u.id = :userId')
        ->setParameter('userId', $userId)
        ->orderBy('q.id', 'DESC') 
        ->getQuery()
        ->getResult();
}

public function findByMatierAndNiveau(int $idMatier, int $idNiveau): array
{
    return $this->createQueryBuilder('q')
        ->where('q.id_matier = :idMatier')
        ->andWhere('q.id_niveau = :idNiveau')
        ->setParameter('idMatier', $idMatier)
        ->setParameter('idNiveau', $idNiveau)
        ->orderBy('q.id', 'DESC')
        ->getQuery()
        ->getResult();
}


public function findByMatierAndNiveauAndUser(int $idMatier, int $idNiveau, int $idUser): array
{
    return $this->createQueryBuilder('q')
        ->where('q.id_matier = :idMatier')
        ->andWhere('q.id_niveau = :idNiveau')
        ->andWhere('q.id_user = :idUser')
        ->setParameter('idMatier', $idMatier)
        ->setParameter('idNiveau', $idNiveau)
        ->setParameter('idUser', $idUser) 
        ->orderBy('q.id', 'DESC')
        ->getQuery()
        ->getResult();
}


public function findAllWithUser(): array
{
    return $this->createQueryBuilder('q')
        ->addSelect('u') 
        ->leftJoin('q.id_user', 'u') 
        ->getQuery() 
        ->getResult();
}


    //    /**
    //     * @return Quiz[] Returns an array of Quiz objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('q')
    //            ->andWhere('q.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('q.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Quiz
    //    {
    //        return $this->createQueryBuilder('q')
    //            ->andWhere('q.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
