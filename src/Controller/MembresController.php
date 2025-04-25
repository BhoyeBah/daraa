<?php

namespace App\Controller;

use App\Entity\Membres;
use App\Form\MembresType;
use App\Repository\MembresRepository;
use App\Service\Numerogenerator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/membres')]
final class MembresController extends AbstractController
{
    private Numerogenerator $numerogenerator;

    public function __construct(Numerogenerator $numerogenerator)
    {
        $this->numerogenerator = $numerogenerator;
    }

    #[Route('/', name: 'app_membres_index', methods: ['GET'])]
    public function index(MembresRepository $membresRepository): Response
    {
        $currentUser = $this->getUser();
        // Si l'utilisateur a le rôle ADMIN
        if(in_array('ROLE_ADMIN', $currentUser->getRoles(), true)){
            $membres = $membresRepository->findAll(); 
            // Montre tous les membres
        }else{

        $currentUser = $this->getUser();

        $encadreur = $currentUser->getEncadreur();

        $dahiras = $encadreur->getDahiras();
        
        $membres = $membresRepository->findBy(['dahiras' => $dahiras]);
        }
        return $this->render('membres/index.html.twig', [
            'membres' => $membres,
        ]);

    }

    #[Route('/new', name: 'app_membres_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $membre = new Membres();
        $form = $this->createForm(MembresType::class, $membre);
        $form->handleRequest($request);

        // Récupérer l'utilisateur connecté (qui est l'encadreur)
        $currentUser = $this->getUser();

        $encadreur = $currentUser->getEncadreur();
         // Récupérer le dahira de l'encadreur
         if($this->isGranted('ROLE_ADMIN')){
            return $this->redirectToRoute('app_membres_index');
         }
         
         $dahira = $encadreur->getDahiras();
       // Récupérer le numéro du membre
        $memberNumero = $this->numerogenerator->generateNumberMembre($dahira);
        // Créer le membre avec le numéro généré
        $membre->setNumero($memberNumero);
           
        if ($form->isSubmitted() && $form->isValid()) {
            
            $membre->setEncadreur($encadreur);
            $membre->setDahiras($dahira);
            // dd($membre,$encadreur,$dahira->getNom());
            if (empty($membre->getPoste())) {
                $membre->setPoste('Membre');
            }
    
            $entityManager->persist($membre);
            $entityManager->flush();

            return $this->redirectToRoute('app_membres_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('membres/new.html.twig', [
            'membre' => $membre,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_membres_show', methods: ['GET'])]
    public function show(Membres $membre): Response
    {
        return $this->render('membres/show.html.twig', [
            'membre' => $membre,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_membres_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Membres $membre, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(MembresType::class, $membre);
        $form->handleRequest($request);
       
        if ($form->isSubmitted() && $form->isValid()) {
           
            if (empty($membre->getPoste())) {
                $membre->setPoste('Membre');
            }
            $entityManager->flush();

            return $this->redirectToRoute('app_membres_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('membres/edit.html.twig', [
            'membre' => $membre,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_membres_delete', methods: ['POST'])]
    public function delete(Request $request, Membres $membre, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$membre->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($membre);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_membres_index', [], Response::HTTP_SEE_OTHER);
    }
}
