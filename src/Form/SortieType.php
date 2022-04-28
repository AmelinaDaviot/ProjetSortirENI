<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\Sortie;
use App\Entity\Ville;
use Doctrine\ORM\EntityRepository;
use phpDocumentor\Reflection\Types\Null_;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom',null,[
                'label'=>"Nom ",
                'required' =>true,
            ])

            ->add('dateHeureDebut',DateTimeType::class, [
                'label'=>"Date et heure ",
                'required' =>true,
            ])

            ->add('duree', IntegerType::class,[
                'label'=>'Durée en minutes',
                'required' =>true,
            ])

            ->add('nbInscriptionsMax', IntegerType::class,[
                'label'=>'Nombre de places',
                'required' =>true,
            ])

            ->add('infosSortie', TextareaType::class,[
                'label'=>'Description et informations',
                'trim'=>true,

                ])
            ->add('dateLimiteInscription', DateTimeType::class, [
                'label' => 'Date limite d\'inscription :',
                'years' => range(2019,2030),
                'widget' => 'single_text',
                'html5' => 'true',
                'required' =>true,
            ])

            ->add('campus', EntityType::class, [
                'class' => Campus::class,
                'choice_label' => 'nom',
                'placeholder' => '-- Choisir --',
                'query_builder' => function(EntityRepository $repository) {},
                'attr' => [
                    'id' => "campus",
                    'class' => 'form-select',

                ]
            ])

//            ->add('ville', EntityType::class, [
//                'label' => 'Ville :',
//                'class' => Ville::class,
//                'attr' => [
//                        'id' => "campus",
//                         'class' => 'form-select',
//
//    ]
//            ])

//            ->add('rue', TextType::class,[
//                'label'=>'Rue',
//                'required' =>true,
//            ])

            ->add('submit', SubmitType::class, [
                'label' => 'Enregistrer'
            ])

            ->add('submit', SubmitType::class, [
                'label' => 'Annuler'
            ]);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
