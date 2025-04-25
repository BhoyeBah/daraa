<?php
namespace App\Service;

use App\Entity\Dahiras;
use App\Entity\Membres;
use App\Entity\Reunion;
use Doctrine\ORM\EntityManagerInterface;

class Numerogenerator {

    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function gereratorNumeroRef(Dahiras $dahiras, \DateTimeInterface $dateReunion): string
    {
        // Récupérer les deux premières lettres du nom de la dahira
        $prefix = strtoupper(substr($dahiras->getNom(), 0, 2));
        
        // Formatage de la date (jour mois année)
        $date = $dateReunion->format('dmy');

        // Générer le prefixe général
        $baseRef = sprintf('#%s%s', $prefix, $date);

        // Chercher le dernier numéro correspondant
        $lastReunion = $this->entityManager->getRepository(Reunion::class)
            ->createQueryBuilder('r')
            ->where('r.numero LIKE :baseRef')
            ->setParameter('baseRef', $baseRef . '%')
            ->orderBy('r.numero', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();

        // Calculons le compteur
        $counter = 1;
        if ($lastReunion) {
            $lastNumeroRef = $lastReunion->getNumero();
            $lastcounter = (int) substr($lastNumeroRef, -2);  // Extract the last 2 digits as the counter
            $counter = $lastcounter + 1;
        }

        // Return the generated reference number with two digits for the counter
        return sprintf('%s%02d', $baseRef, $counter);
    }

    public function generateNumberMembre(Dahiras $dahiras): string
    {
        $prefix = strtoupper(substr($dahiras->getNom(), 0,2));
    
        // Chercher le dernier membre de cette dahira
        $lastMembre = $this->entityManager->getRepository(Membres::class)
        ->createQueryBuilder('m')
        ->where('m.dahiras = :dahira')
        ->orderBy('m.id', 'DESC')
        ->setParameter('dahira', $dahiras)
        ->setMaxResults(1)
        ->getQuery()
        ->getOneOrNullResult();

        $counterMembre = 1;
        if($lastMembre){
            // Extraire les trois derniers chiffres du dernier numéro
            $lastNumero = $lastMembre->getNumero();
            $lastcounter = (int) substr($lastNumero, -3);
            $counterMembre = $lastcounter + 1;
        }

        return sprintf('#%s%03d', $prefix, $counterMembre);
    }
}
