<?php

namespace App\Form;

use App\Entity\Lieu;
use App\Entity\Ville;
use App\Repository\VilleRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LieuType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom du lieu : ',
//                'trim' => true,
                'required' => true,
            ])
            ->add('rue', TextType::class, [
                'label' => 'Rue : ',
//                'trim' => true,
                'required' => true,
            ])
            ->add('ville', EntityType::class, [
                'label' => 'Ville : ',
                'required' => true,
                'class' => Ville::class,
                'query_builder' => function (VilleRepository $cr) {
                    return $cr->createQueryBuilder('ville')->orderBy('ville.nom', 'ASC');
                },
                'choice_label' => 'nom',
            ]);

//        $builder->add('submit', SubmitType::class, [
//            'label' => 'Créer un lieu',
//        ]);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Lieu::class,
        ]);
    }
}
