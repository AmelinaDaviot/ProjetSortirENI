<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Form\NouveauMdpType;
use App\Form\ParticipantType;
use App\Repository\ParticipantRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Services\SecuriteUrlService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(path="/participant", name="participant_")
 */
class ParticipantController extends AbstractController
{

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
            return $this->redirectToRoute('participant_profil', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('participant/create.html.twig', [
            'participant' => $participant,
            'formCreateParticipant' => $formCreateParticipant,
        ]);
    }

    /**
     * @Route("/{pseudo}/profil", name="profil", methods={"GET"})
     */
    public function details(Request $request, EntityManagerInterface $entityManager, ParticipantRepository $participantRepository): Response
    {

//        dd($_SERVER["REQUEST_URI"]);
//        dd($request->get('pseudo'));
        // Récupération du pseudo du participant
        $pseudo = (string) $request->get('pseudo');

        // Récupération du participant
        $participant = $participantRepository->findByPseudo($pseudo);
        if (is_null($participant)) {
            throw $this->createNotFoundException('Le participant n\'existe pas !');
        }

        return $this->render('participant/detailprofil.html.twig', [
            'participant' => $participant,
        ]);
    }

    /**
     * @Route("/{pseudo}/modifier", name="modifier", methods={"GET", "POST"})
     */
    public function modifier(Request $request, Participant $participant, ParticipantRepository $participantRepository, SecuriteUrlService $securiteUrl, UserPasswordHasherInterface $hasher): Response
    {
        $participantUri = '/participant/';
        $modifier = '/modifier';
        $uri = $_SERVER["REQUEST_URI"];

        /**
         * Si le pseudo du participant connecté n'est pas égal au pseudo écrit dans l'URL : redirection vers le détail du profil
         * On sécurise l'accès à la page modifier des profils des autres utilisateurs
         */
        if ($this->getUser()->getUserIdentifier() != $securiteUrl->securiserUrl($participantUri, $modifier, $uri)){
            return $this->render('participant/detailprofil.html.twig', [
                'participant' => $participant,
            ]);
        }

//        if (!$this->getUser()) {
//            return $this->redirectToRoute('securite_connexion');
//        }
//
//        if ($this->getUser() !== $participant) {
//            return $this->redirectToRoute('accueil');
//        }

        $formCreateParticipant = $this->createForm(ParticipantType::class, $participant);
        $formCreateParticipant->handleRequest($request);

        if ($formCreateParticipant->isSubmitted() && $formCreateParticipant->isValid()) {
            /**
             * On vérifie si le mot de passe entré dans le formulaire est égal au mot de passe en BDD du participant
             */
            if ($hasher->isPasswordValid($participant, $formCreateParticipant->getData()->getPlainPassword())) {
                if ($formCreateParticipant->get('photo')->getData() != null) {
                    $file = $formCreateParticipant->get('photo')->getData();
                    $fileName = md5(uniqid()) . '.' . $file->guessExtension();
                    $file->move($this->getParameter('users_photos_directory'), $fileName);
                    $participant->setImg($fileName);
                }
                $participantRepository->add($participant);
                $this->addFlash('success', 'Les modifications de votre compte ont bien été enregistrées !');
                return $this->redirectToRoute('participant_profil', ['pseudo' => $participant->getPseudo()], Response::HTTP_SEE_OTHER);
            } else {
                $this->addFlash('warning', 'Mot de passe incorrect !');
            }
        }

        return $this->renderForm('participant/modifier.html.twig', [
            'participant' => $participant,
            'formCreateParticipant' => $formCreateParticipant,
        ]);
    }

    /**
     * @Route(path="/modifier-mot-de-passe", name="modifier_password", methods={"GET", "POST"})
     */
    public function modifierPassword(EntityManagerInterface $entityManager, Request $request, UserPasswordHasherInterface $hasher) : Response
    {
        $participant = $this->getUser();

        $formModifMdp = $this->createForm(NouveauMdpType::class);
        $formModifMdp->handleRequest($request);

        if ($formModifMdp->isSubmitted() && $formModifMdp->isValid()) {
            if ($hasher->isPasswordValid($participant, $formModifMdp['oldPassword']->getData())){
                // Hasher le nouveau mot de passe
                $newEncodedPassword = $hasher->hashPassword($participant, $formModifMdp['newPassword']->getData());
                // Entrée du nouveau mot de passe dans la BDD
                $participant->setPassword($newEncodedPassword);
                $entityManager->flush();

                $this->addFlash('success', 'Votre nouveau mot de passe a bien été enregistré !');
                return $this->redirectToRoute('participant_profil', ['pseudo' => $participant->getPseudo()], Response::HTTP_SEE_OTHER);
            } else {
                $this->addFlash('warning', 'Mot de passe incorrect !');
            }
        }

        return $this->renderForm('participant/nouveaumdp.html.twig', [
            'participant' => $participant,
            'formModifMdp' => $formModifMdp,
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
