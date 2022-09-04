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
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom',TextType::class, [
                'label'=> 'Nom de la sortie',
                'required' =>true,
            ])

            ->add('dateHeureDebut',DateTimeType::class, [
                'label'=> 'Date et heure',
                'required' =>true,
                'widget' => 'single_text',
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
//                'trim'=>true,
            ])

            ->add('dateLimiteInscription', DateTimeType::class, [
                'label' => 'Date limite d\'inscription',
                'years' => range(2019,2030),
                'widget' => 'single_text',
//                'html5' => 'true',
                'required' => true,
            ])

            ->add('campus', EntityType::class, [
                'class' => Campus::class,
                'mapped' => false,
                'choice_label' => 'nom',
                'query_builder' => function(EntityRepository $repository) {},
                'attr' => [
                    'id' => 'campus',
                    'class' => 'form-control',
                    'value' => '$this->getUser()->getCampus()',
                ],

            ])


//            ->add('ville', EntityType::class, [
//                'label' => 'Ville',
//                'required' => true,
//                'class' => Ville::class,
//                'mapped' => false,
//                'attr' => ['class' => 'form-control'],
//                'query_builder' => function (VilleRepository $cr) {
//                    return $cr->createQueryBuilder('ville')
//                        ->orderBy('ville.nom', 'ASC');
//                },
//                'choice_label' => 'nom',


//            ])

//            ->add('lieu', EntityType::class, [
//                'class'=> Lieu::class,
//                'label'=>'Lieu',
//                'required'=> true,
//                'attr' => ['class' => 'form-control',]
//            ])
//
//
//            ->add('rue', EntityType::class,[
//                'label'=>'Rue',
//                'required' =>true,
//                'class' => Lieu::class,
//                'mapped' => false,
//                'disabled' => true,
//                'attr' => ['class' => 'form-control',],
//                'choice_label' => 'rue',
//            ])

//            ->add('codePostal', EntityType::class, [
//                'label' => 'Code Postal :',
//                'mapped' => false,
//                'class' => Ville::class,
//                'disabled' => true,
//                'attr' => ['class' => 'form-control',]
//
//            ])



            ->add('save', SubmitType::class, [
                'label' => 'Enregistrer',
                'attr' => ['class' => 'btn btn-primary btn-lg']
            ])

            ->add('saveAndPublish', SubmitType::class, [
                'label' => 'Publier',
                'attr' => array('class' => 'btn btn-success btn-lg')
            ])

        ->add('ville', EntityType::class,[
                    'class' => 'App\Entity\Ville',
                    'mapped' => false,
                    'choice_label' => 'nom',
                    'placeholder' => 'Selectionner une ville',
                    'required' => false,
                    'attr' => ['class' => 'form-control',]
           ]);


        $builder->get('ville')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event){
                $form = $event->getForm();
                $this->addLieuField($form->getParent(), $form->getData());
            }
        );

        $builder->addEventListener(
            FormEvents::POST_SET_DATA,
            function (FormEvent $event){
                $data = $event->getData();
                /* @var $lieu \App\Entity\Lieu */
                $lieu = $data->getLieu();
                $form = $event->getForm();

                if($lieu){
                    $ville = $lieu->getVille();
                    $this->addLieuField($form, $ville);
                    $form->get('ville')->setData($ville);
                }else{
                    $this->addLieuField($form, null);
                }
            }
        );
    }

    private function addLieuField(FormInterface $form, ?Ville $ville){
        $builder = $form->add('lieu', EntityType::class,[
            'class' => Lieu::class,
            'choice_label' => 'nom',
            'placeholder' => $ville ? 'Selectionnez votre lieu' : 'Selectionnez d\'abord votre ville',
            'required' => true,
            'auto_initialize' => false,
            'choices' => $ville ? $ville->getLieux() : [],
            'attr' => ['class' => 'form-control',]
        ]);
//        $builder = $form->add('rue', TextType::class,[
//            'class' => Lieu::class,
//            'choice_label' => 'nom',
//            'placeholder' => $ville ? 'Selectionnez votre lieu' : 'Selectionnez d\'abord votre ville',
//            'required' => true,
//            'auto_initialize' => false,
//            'choices' => $ville ? $ville->getLieux() : [],
//            'attr' => ['class' => 'form-control',]
//        ]);
    }




    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
