<?php

namespace App\Form;

use App\Entity\Dahiras;
use App\Entity\Encadreur;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class EncadreurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class)
            ->add('prenom', TextType::class)
            ->add('email', EmailType::class)
            ->add('adresse', TextType::class)
            ->add('telephone', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => "Le numéro de téléphone ne doivent pas être vide.",
                    ]),
                    new Length([
                        'min' => 9,
                        'max' => 14,
                        'minMessage' => 'Le numéro de téléphone doit contenir au moins {{ limit }} chiffres.',
                        'maxMessage' => 'Le numéro de téléphone ne peut pas contenir plus de {{ limit }} chiffres.',
                    ]),
                    new Regex([
                        'pattern' => '/^\d+$/',
                        'message' => 'Le numéro de téléphone doit contenir uniquement des chiffres.',
                    ]),
                ],
                'attr' => [
                    'oninput' => "this.value = this.value.replace(/[^0-9]/g, '')", // Supprime les caractères non numériques pendant la saisie
                ],
            ])
            ->add('dahiras', EntityType::class, [
                'class' => Dahiras::class,
                'choice_label' => 'nom',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Encadreur::class,
        ]);
    }
}
