<?php

namespace App\Controller;

use App\Entity\Encadreur;
use App\Entity\User;
use App\Form\EncadreurType;
use App\Repository\EncadreurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use SymfonyCasts\Bundle\ResetPassword\ResetPasswordHelperInterface;

#[Route('/encadreur')]
final class EncadreurController extends AbstractController
{
    #[Route(name: 'app_encadreur_index', methods: ['GET'])]
    public function index(EncadreurRepository $encadreurRepository): Response
    {
        return $this->render('encadreur/index.html.twig', [
            'encadreurs' => $encadreurRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_encadreur_new', methods: ['GET', 'POST'])]
    public function new(Request $request,UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager,MailerInterface $mailer, ResetPasswordHelperInterface $resetPasswordHelper): Response
    {
        $encadreur = new Encadreur();
        $form = $this->createForm(EncadreurType::class, $encadreur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Créer un nouvel utilisateur pour l'encadreur
            $user = new User();
            $user->setEmail($encadreur->getEmail());
            $user->setNom($encadreur->getNom());
            $user->setPrenom($encadreur->getPrenom());
            $user->setTelephone($encadreur->getTelephone());
            $user->setAdresse($encadreur->getAdresse());
            $user->setRoles(['ROLE_ENCADREUR']);
            $user->setStatus(false);

            // Définir un mot de passe par défaut "123456789"
            
            $plainPassword = "123456";
            $hashedPassword = $passwordHasher->hashPassword($user, $plainPassword);
            $user->setPassword($hashedPassword);
            $encadreur->setUser($user);
            $user->setEncadreur($encadreur);
            $entityManager->persist($user);
            $entityManager->persist($encadreur);
            $entityManager->flush();
            // Generate a password reset token for the newly created user
            $resetToken = $resetPasswordHelper->generateResetToken($user);
            //Envoi mail
            $email = (new TemplatedEmail())
            ->from(new Address('bhoyemad11@gmail.com', 'noreply'))
            ->to($encadreur->getEmail())
            ->subject ('Your password reset request')
            ->htmlTemplate('reset_password/email.html.twig')
            ->context([
            'resetToken' => $resetToken,
            ]);

            $mailer->send($email);
            $this->addFlash('success', 'Encadreur enregistré avec succès.');
            return $this->redirectToRoute('app_encadreur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('encadreur/new.html.twig', [
            'encadreur' => $encadreur,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_encadreur_show', methods: ['GET'])]
    public function show(Encadreur $encadreur): Response
    {
        return $this->render('encadreur/show.html.twig', [
            'encadreur' => $encadreur,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_encadreur_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Encadreur $encadreur, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EncadreurType::class, $encadreur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_encadreur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('encadreur/edit.html.twig', [
            'encadreur' => $encadreur,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_encadreur_delete', methods: ['POST'])]
    public function delete(Request $request, Encadreur $encadreur, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$encadreur->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($encadreur);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_encadreur_index', [], Response::HTTP_SEE_OTHER);
    }
}
