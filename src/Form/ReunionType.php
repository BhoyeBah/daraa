<?php

namespace App\Form;

use App\Entity\Dahiras;
use App\Entity\Membres;
use App\Entity\Reunion;
use App\Entity\Themes;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReunionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // Récupérer l'option 'dahira' passée au formulaire
        $dahira = $options['dahira'];

        $builder
            ->add('date', null, [
                'widget' => 'single_text',
            ])
            ->add('theme', EntityType::class, [
                'class' => Themes::class,
                'choice_label' => 'nom',
            ])
            ->add('lieu')
            ->add('sujetaborde')
            ->add('decisionprise')
            ->add('membres', EntityType::class, [
                'class' => Membres::class,
                'choice_label' => function (Membres $membre) {
                    return $membre->getNom() . ' ' . $membre->getPrenom();
                },
                'multiple' => true,
                'expanded' => false, // Garder un menu déroulant multi-sélection
                'query_builder' => function (\Doctrine\ORM\EntityRepository $er) use ($dahira) {
                    return $er->createQueryBuilder('m')
                        ->where('m.dahiras = :dahira') // Filtrer les membres par Dahira
                        ->setParameter('dahira', $dahira);
                },
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Reunion::class,
            'dahira' => null, // Ajout de l'option personnalisée 'dahira'
        ]);
    }
}
