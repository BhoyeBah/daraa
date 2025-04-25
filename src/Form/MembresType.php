<?php

namespace App\Form;

use App\Entity\Dahiras;
use App\Entity\Encadreur;
use App\Entity\Membres;
use App\Entity\Specialites;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class MembresType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class)
            ->add('prenom', TextType::class)
            ->add('adresse', TextType::class)
            ->add('email',EmailType::class)
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
            ->add('specialite', EntityType::class, [
                'class' => Specialites::class,
                'choice_label' => 'nom',
                'multiple' => true,
            ])
            ->add('poste', ChoiceType::class, [
                'choices' => [
                    'Président' => 'President',
                    'Secrétaire' => 'Secretaire',
                    'Encadreur' => 'Encadreur',
                ],
                'expanded' => false, // Affiche un select au lieu de boutons radio
                'multiple' => false,
                'placeholder' => 'Veuillez sélectionner un poste',
                'required' => false,
            ])
       ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Membres::class,
        ]);
    }
}
