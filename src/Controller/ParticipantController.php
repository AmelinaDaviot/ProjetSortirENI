<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Form\ParticipantType;
use App\Repository\ParticipantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(path="/participant", name="participant_")
 */
class ParticipantController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     */
    public function index(ParticipantRepository $participantRepository): Response
    {
        return $this->render('participant/index.html.twig', [
            'participants' => $participantRepository->findAll(),
        ]);
    }

    /**
     * @Route("/create", name="create", methods={"GET", "POST"})
     */
    public function create(Request $request, ParticipantRepository $participantRepository): Response
    {
        $participant = new Participant();
        $formCreateParticipant = $this->createForm(ParticipantType::class, $participant);
        $formCreateParticipant->handleRequest($request);

        if ($formCreateParticipant->isSubmitted() && $formCreateParticipant->isValid()) {
            $participantRepository->add($participant);
            return $this->redirectToRoute('participant_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('participant/create.html.twig', [
            'participant' => $participant,
            'formCreateParticipant' => $formCreateParticipant,
        ]);
    }

//    /**
//     * @Route("/{id}", name="show", methods={"GET"})
//     */
//    public function show(Participant $participant): Response
//    {
//        return $this->render('participant/show.html.twig', [
//            'participant' => $participant,
//        ]);
//    }

    /**
     * @Route("/{id}/modifier", name="modifier", methods={"GET", "POST"})
     */
    public function modifier(Request $request, Participant $participant, ParticipantRepository $participantRepository): Response
    {
        $formCreateParticipant = $this->createForm(ParticipantType::class, $participant);
        $formCreateParticipant->handleRequest($request);

        if ($formCreateParticipant->isSubmitted() && $formCreateParticipant->isValid()) {
            $participantRepository->add($participant);
            return $this->redirectToRoute('participant_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('participant/modifier.html.twig', [
            'participant' => $participant,
            'formCreateParticipant' => $formCreateParticipant,
        ]);
    }

    /**
     * @Route(name="delete", path="{id}/delete", requirements={"id": "\d+"}, methods={"GET", "POST"})
     */
    public function delete(EntityManagerInterface $entityManager, Request $request): RedirectResponse
    {
        // Récupération de l'identifiant du souhait
        $id = (int) $request->get('id');

        // Récupération du souhait
        $participant = $entityManager->getRepository('App:Participant')->find($id);
        if (is_null($participant)) {
            throw $this->createNotFoundException('Participant non trouvé !');
        }

        // Dissociation
        $entityManager->remove($participant);

        // Validation
        $entityManager->flush();
//        $this->addFlash("danger", "Le compte de ce participant a été supprimé avec succès.");

        return $this->redirectToRoute("accueil");
    }
}
