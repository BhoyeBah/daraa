<?php

namespace App\Controller;

use App\Entity\Dahiras;
use App\Form\DahirasType;
use App\Repository\DahirasRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/dahiras')]
final class DahirasController extends AbstractController
{
    #[Route(name: 'app_dahiras_index', methods: ['GET'])]
    public function index(DahirasRepository $dahirasRepository): Response
    {
        return $this->render('dahiras/index.html.twig', [
            'dahiras' => $dahirasRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_dahiras_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $dahira = new Dahiras();
        $form = $this->createForm(DahirasType::class, $dahira);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($dahira);
            $entityManager->flush();

            return $this->redirectToRoute('app_dahiras_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('dahiras/new.html.twig', [
            'dahira' => $dahira,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_dahiras_show', methods: ['GET'])]
    public function show(Dahiras $dahira): Response
    {
        return $this->render('dahiras/show.html.twig', [
            'dahira' => $dahira,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_dahiras_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Dahiras $dahira, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(DahirasType::class, $dahira);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_dahiras_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('dahiras/edit.html.twig', [
            'dahira' => $dahira,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_dahiras_delete', methods: ['POST'])]
    public function delete(Request $request, Dahiras $dahira, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$dahira->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($dahira);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_dahiras_index', [], Response::HTTP_SEE_OTHER);
    }
}
