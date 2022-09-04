<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Form\InscriptionType;
use App\Repository\ParticipantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @Route(name="admin_", path="admin/")
 */
class InscriptionController extends AbstractController
{

/**
* @Route("participants", name="participants", methods={"GET"})
*/
public function listeParticipants(ParticipantRepository $participantRepository): Response
{
    return $this->render('participant/listeParticipants.html.twig', [
        'participants' => $participantRepository->findAll(),
    ]);
}

    /**
     * @Route(path="inscription", name="inscription")
     */
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new Participant();
        $form = $this->createForm(InscriptionType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
            $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email
            $this->addFlash('success', 'Vous avez bien inscrit ce nouvel utilisateur !');
            return $this->redirectToRoute('accueil');
        }

        return $this->render('inscription/inscription.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
