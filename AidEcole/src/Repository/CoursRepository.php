<?php

namespace App\Repository;

use App\Entity\Cours;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Cours>
 */
class CoursRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Cours::class);
    }


   /* public function findCoursByMatiereAndEnseignant(int $matiereId, int $enseignantId)
    {
        return $this->createQueryBuilder('c')
            ->join('c.matiere', 'm')  // Jointure avec l'entité Matière
            ->join('c.enseignant', 'e') // Jointure avec l'entité Enseignant
            ->where('m.id = :matiereId') // Filtrer par la matière
            ->andWhere('e.id = :enseignantId') // Filtrer par l'enseignant connecté
            ->setParameter('matiereId', $matiereId)
            ->setParameter('enseignantId', $enseignantId)
            ->getQuery()
            ->getResult();
    } */
    


    //    /**
    //     * @return Cours[] Returns an array of Cours objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('c.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Cours
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
