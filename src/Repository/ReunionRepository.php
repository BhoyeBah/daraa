<?php

namespace App\Repository;

use App\Entity\Reunion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Reunion>
 */
class ReunionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reunion::class);
    }

    /**
     * Récupérer le nombre de réunions par Dahira dans un mois donné
     *
     * @param int $mois Le mois (1 = janvier, 12 = décembre)
     * @param int $annee L'année (ex : 2024)
     * @return array Un tableau contenant le nombre de réunions par Dahira
     */
    public function countReunionsParDahira(int $mois, int $annee)
    {
        // Créer une date de début et de fin pour le mois donné
        $dateDebut = new \DateTime("$annee-$mois-01 00:00:00");
        $dateFin = clone $dateDebut;
        $dateFin->modify('last day of this month')->setTime(23, 59, 59);

        // Créer la requête DQL
        $qb = $this->createQueryBuilder('r')
            ->select('dahira.id AS dahira_id', 'COUNT(r.id) AS nombre_reunions')
            ->innerJoin('r.dahiras', 'dahira')
            ->where('r.date BETWEEN :dateDebut AND :dateFin')
            ->groupBy('dahira.id')
            ->setParameter('dateDebut', $dateDebut)
            ->setParameter('dateFin', $dateFin);

        // Exécuter la requête et retourner les résultats
        return $qb->getQuery()->getResult();
    }

    //    /**
    //     * @return Reunion[] Returns an array of Reunion objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('r.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Reunion
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
