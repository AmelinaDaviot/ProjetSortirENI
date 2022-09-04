<?php

namespace App\Form;

use App\Entity\Sortie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class AnnulerSortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('motifAnnulation',TextareaType::class, [
                'required' => true,
                'label' => 'Motif d\'annulation',
                'attr' => [
                    'placeHolder' => 'Veuillez justifier l\'annulation de votre sortie'
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Le motif d\'annulation est requis !']),
                     new Length(['min' => 6,
                        'max' => 250,
                        'minMessage' => 'Le motif d\'annulation doit contenir au minimum {{ limit }} caractères !',
                        'maxMessage' => 'Le motif d\'annulation doit contenir au maximum {{ limit }} caractères !'])
                ],

            ])
            ->add('submit', SubmitType::class,[
                'label' =>'Annuler la sortie',
                'attr' => [
                    'class' => 'btn btn-danger btn-lg'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}

// @TODO : rajouter function annuler_sortie dans controller pour tout lié etc
