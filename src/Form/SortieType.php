<?php

namespace App\Form;

use App\Entity\Sortie;
use phpDocumentor\Reflection\Types\Null_;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom',null,["label"=>"Nom de la sortie :"])
            ->add('dateHeureDebut')
            ->add('duree')
            ->add('nbInscriptionsMax')
            ->add('infosSortie')
            ->add('motifAnnulation')
            ->add('dateLimiteInscription')
            ->add('participants')
            ->add('organisateur')
            ->add('participant')
            ->add('lieu')
            ->add('etat')
            ->add('campus')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
