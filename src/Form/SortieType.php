<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\Lieu;
use App\Entity\Sortie;
use App\Entity\Ville;
use App\Repository\VilleRepository;
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
                'label'=>"Date et heure",
                'required' =>true,
            ])

            ->add('duree', IntegerType::class,[
                'label'=>'Durée',
                'required' =>true,
            ])

            ->add('nbInscriptionsMax', IntegerType::class,[
                'label'=>'Nombre de places',
                'required' =>true,
            ])

            ->add('infosSortie', TextareaType::class,[
                'label'=>'Description et informations',
                'required' => false,
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
                'query_builder' => function(EntityRepository $repository) {},
                'attr' => [
                    'id' => "campus",
                    'class' => 'form-control',
                ]
            ])


            ->add('ville', EntityType::class, [
                'label' => 'Ville',
                'required' => true,
                'class' => Ville::class,
                'mapped' => false,
                'attr' => ['class' => 'form-control'],
                'query_builder' => function (VilleRepository $cr) {
                    return $cr->createQueryBuilder('ville')
                        ->orderBy('ville.nom', 'ASC');
                },
                'choice_label' => 'nom',
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

            ->add("lieu", EntityType::class, [
                "class"=> Lieu::class,
                'label'=>'Lieu : ',
                'required'=> true,
                'attr' => ['class' => 'form-control',]
            ])

            ->add('save', SubmitType::class, [
                'label' => 'Enregistrer',
                'attr' => ['class' => 'btn btn-primary']
            ])

            ->add('saveAndPublish', SubmitType::class, [
                'label' => 'Publier',
                'attr' => array('class' => 'btn btn-success')
            ])

            ->add('cancel', SubmitType::class, [
                'label' => 'Annuler',
                'attr' => array('class' => 'btn btn-danger')
            ]);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
