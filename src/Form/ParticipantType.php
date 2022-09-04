<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\Participant;
use App\Repository\CampusRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class ParticipantType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('pseudo', TextType::class,[
                'required' => true,
                'label' => 'Pseudo',
                'attr' => ['placeHolder' => 'Pseudo'],
            ])

            ->add('prenom', TextType::class,[
                'disabled' => true,
                'label' => 'Prénom',
//                'attr' => ['placeHolder' => 'Prénom'],
            ])

            ->add('nom', TextType::class,[
                'disabled' => true,
                'label' => 'Nom',
//                'attr' => ['placeHolder' => 'Nom'],
            ])

            ->add('telephone', TelType::class,[
                'required' => true,
                'label' => 'Téléphone',
                'attr' => ['placeHolder' => 'Numéro de téléphone'],
                'invalid_message' => 'Le numéro de téléphone n\'est pas valide.'
            ])

            ->add('email', EmailType::class, [
                'required' => true,
                'label' => 'Email',
                'attr' => ['placeHolder' => 'Adresse email'],
            ])

            ->add('campus', EntityType::class,[
                'disabled' => true,
                'label' => 'Campus',
                'class' => Campus::class,
                'attr' => ['class' => 'form-control'],
                //Classer par ordre alphabétique les noms de campus
                'query_builder' => function(CampusRepository $cr) {
                    return $cr->createQueryBuilder('campus')
                        ->orderBy('campus.nom', 'ASC');
                },
                'choice_label' => 'nom',
            ])

            ->add('plainPassword', PasswordType::class, [
                'required' => true,
                'label' => 'Saisir le mot de passe pour valider',
                'attr' => ['placeHolder' => 'Mot de passe'],
            ])

            ->add('photo', FileType::class, [
                'label' => 'Photo (PNG, JPG, BMP)',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/*'
                        ],
                        'mimeTypesMessage' => 'Merci de sélectionner un fichier image.',
                    ])
                ],
            ])

            ->add('submit', SubmitType::class, [
                'label' => 'Enregistrer',
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Participant::class,
        ]);
    }
}
