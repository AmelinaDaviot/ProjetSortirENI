<?php

namespace App\Controller;

use App\Entity\Etat;
use App\Entity\Sortie;
use App\Form\AnnulerSortieType;
use App\Form\SortieType;
use App\Repository\CampusRepository;
use App\Repository\EtatRepository;
use App\Repository\ParticipantRepository;
use App\Repository\SortieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/sortie", name="sortie_")
 */
class SortieController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     */
    public function index(SortieRepository $sortieRepository): Response
    {
//        TODO : A enlever
        return $this->render('sortie/index.html.twig', [
            'sorties' => $sortieRepository->findAll(),
        ]);
    }

    /**
     * @Route(path="/creer", name="creer", methods={"GET", "POST"})
     */
    public function creerSortie(Request $request, SortieRepository $sortieRepository,
                                EtatRepository $etatRepository, EntityManagerInterface $entityManager): Response
    {
        // Création de l'entité
        $sortie = new Sortie();

        // Affichage dans le twig du cammpus de l'utilisateur
        $campusUser = $this->getUser()->getCampus();

        // Création et Association du formulaire
        $formCreateSortie = $this->createForm(SortieType::class, $sortie);
        $formCreateSortie->remove('campus');
        $formCreateSortie->handleRequest($request);

        // Vérification de la soumission du formulaire
        if ($formCreateSortie->isSubmitted() && $formCreateSortie->isValid()) {
            // L'utilisateur créateur de la sortie devient organisateur
            $sortie->setOrganisateur($this->getUser());

            // Si on clique sur le bouton 'enregistrer' => etat = créée
            // Si on clique sur le bouton 'publier' => etat = ouverte
            if ($formCreateSortie->get('save')->isClicked()){
                $sortie->setEtat($etatRepository->find(1));
                $this->addFlash('info', 'Votre sortie a bien été créée : en attente de publication !');
            } else if ($formCreateSortie->get('saveAndPublish')->isClicked()){
                $sortie->setEtat($etatRepository->find(2));
                $this->addFlash('success', 'Votre sortie a bien été publiée !');
            }

            $sortieRepository->add($sortie);

            // Attribuer le campus de l'utilisateur à la sortie
            $sortie->setCampus($campusUser);

            // Association de l'objet
            $entityManager->persist($sortie);

            // Validation de la transaction
            $entityManager->flush();

            return $this->redirectToRoute('sortie_details', ["id" => $sortie->getId()],
                Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('sortie/new.html.twig', [
            'sortie' => $sortie,
            'campusUser'=> $campusUser,
            'formCreateSortie' => $formCreateSortie,
        ]);
    }

    /**
     * @Route(path="/{id}/details", name="details", methods={"GET"})
     */
    public function details(Sortie $sortie): Response
    {

        return $this->render('sortie/show.html.twig', [
            'sortie' => $sortie,
        ]);
    }

    /**
     * @Route("/{id}/modifier", name="modifier", methods={"GET", "POST"})
     */
    public function modifier(Request $request, Sortie $sortie, SortieRepository $sortieRepository,
        EtatRepository $etatRepository, EntityManagerInterface $entityManager): Response
    {
        // Affichage dans le twig du cammpus de l'utilisateur
        $campusUser = $this->getUser()->getCampus();

        // Création et Association du formulaire
        $formCreateSortie = $this->createForm(SortieType::class, $sortie);
        $formCreateSortie->remove('campus');
        $formCreateSortie->handleRequest($request);

        $sortie = $sortieRepository->find((int)$request->get('id'));

        // On ne peut pas modifier une sortie si on n'est pas organisateur OU admin
        if ($sortie->getOrganisateur() === $this->getUser() || $this->isGranted('ROLE_ADMIN')) {

            // Vérification de la soumission du formulaire
            if ($formCreateSortie->isSubmitted() && $formCreateSortie->isValid()) {
                $sortieRepository->add($sortie);

                // Si on clique sur le bouton 'enregistrer' => etat = créée
                // Si on clique sur le bouton 'publier' => etat = ouverte
                if ($formCreateSortie->get('save')->isClicked()){
                    $sortie->setEtat($etatRepository->find(1));
                    $this->addFlash('info', 'Votre sortie a bien été modifiée : en attente de publication !');
                } else if ($formCreateSortie->get('saveAndPublish')->isClicked()){
                    $sortie->setEtat($etatRepository->find(2));
                    $this->addFlash('success', 'Votre sortie a bien été publiée !');
                }

                // Attribuer le campus de l'utilisateur à la sortie
                $sortie->setCampus($campusUser);

                // Validation de la transaction
                $entityManager->flush();

                return $this->redirectToRoute('sortie_details', [
                    "id" => $sortie->getId()
                ], Response::HTTP_SEE_OTHER);
            }

                return $this->renderForm('sortie/edit.html.twig', [
                    'campusUser'=> $campusUser,
                    'sortie' => $sortie,
                    'formCreateSortie' => $formCreateSortie,
                ]);

        } else {
            // Message d'erreur
            $this->addFlash('danger', 'Impossible de modifier cette sortie. Vous n\'êtes pas l\'organisateur.');
            return $this->redirectToRoute('accueil');
        }
    }

    /**
     * @Route("/{id}/supprimer", name="supprimer", methods={"GET"})
     */
    public function supprimer(Request $request, Sortie $sortie, SortieRepository $sortieRepository,
                              EntityManagerInterface $entityManager): Response
    {
        if (($sortie->getOrganisateur() === $this->getUser() || $this->isGranted('ROLE_ADMIN'))
            && ($sortie->getEtat()->getId() == 1)) {

            // Récupération de l'id de la sortie
            $sortie = $sortieRepository->find((int) $request->get('id'));

            // Suppression de la sortie
            $entityManager->remove($sortie);

            // Validation de la transaction
            $entityManager->flush();

            // Message de confirmation
            $this->addFlash('danger', 'Votre sortie a bien été supprimée !');

            return $this->redirectToRoute('accueil', [], Response::HTTP_SEE_OTHER);

        } else {
            // Message d'erreur
            $this->addFlash('danger', 'Impossible de supprimer cette sortie. Vous n\'êtes pas l\'organisateur.');
            return $this->redirectToRoute('accueil');
        }
    }

    /**
     * @Route(path="/{id}/annuler", name="annuler", methods={"GET", "POST"})
     */
    public function annulerSortie(SortieRepository $sortieRepository, Request $request,
        EntityManagerInterface $entityManager,
        EtatRepository $etatRepository) : Response
    {
        // Récupération de l'id de la sortie
        $sortie = $sortieRepository->find((int)$request->get('id'));

        // Création et Association du formulaire
        $formAnnulerSortie = $this->createForm(AnnulerSortieType::class, $sortie);

        // On récupère la saisie du participant
        $formAnnulerSortie->handleRequest($request);

        // On sécurise l'annulation des sorties :
        // Annulation possible que si l'utilisateur est l'organisateur de la sortie
        // OU s'il s'agit de l'Admin
        // ET si l'état de la sorite est 'ouverte'
        if (($sortie->getOrganisateur() === $this->getUser() || $this->isGranted('ROLE_ADMIN'))
            && ($sortie->getEtat()->getId() == 2)) {

            if ($formAnnulerSortie->isSubmitted() && $formAnnulerSortie->isValid()) {
                // On instancie l'état de la sortie à 'Annulée'
                $sortie->setEtat($etatRepository->find(6));

                // Validation de la transaction
                $entityManager->flush();

                // Message de confirmation
                $this->addFlash('danger', 'Votre sortie a bien été annulée !');

                // On redirige vers la page détail de la sortie annulée
                return $this->redirectToRoute('sortie_details', ["id" => $sortie->getId()],
                Response::HTTP_SEE_OTHER);
            }

            return $this->renderForm('sortie/annuler.html.twig', [
                'sortie' => $sortie,
                'formAnnulerSortie' => $formAnnulerSortie,
            ]);

        } else {
            // Message d'erreur
            $this->addFlash('danger', 'Impossible d\'annuler cette sortie. Vous n\'êtes pas l\'organisateur.');
            return $this->redirectToRoute('accueil');
        }

    }

    /**
     * @Route(path="/{id}/inscription", name="inscription", methods={"GET"})
     */
    public function inscriptionSortie(
        Request $request,
        EntityManagerInterface $entityManager,
        SortieRepository $sortieRepository,
        ParticipantRepository $participantRepository
        ) : Response
    {
        // On récupère l'id de la sortie
        $sortie = $sortieRepository->find((int)$request->get('id'));

        // Si le nombre de participants est inférieur au nombre d'inscriptions max et que l'état est ouvert alors on peut s'inscrire"
        // et que l'utilisateur n'est pas l'organisateur
        if ((count($sortie->getParticipants()) < $sortie->getNbInscriptionsMax())
            && ($sortie->getEtat()->getId() == 2)
            && ($sortie->getOrganisateur() !== $this->getUser()
            ))
        {

            // On récupère l'id de l'utilisateur pour l'ajouter à la liste des participants inscrits
            $participant = $participantRepository->find($this->getUser()->getId());

            // On ajoute l'utilisateur dans la liste des participants inscrits de la sortie
            $participant->addSortie($sortie);

            // Validation de la transaction
            $entityManager->flush();

            // Message de confirmation
            $this->addFlash('success', 'Vous êtes bien inscrit(e) à cette sortie !');

            return $this->render('sortie/show.html.twig', [
                'sortie' => $sortie
            ]);

        } else {
            // Message d'erreur
            $this->addFlash('danger', 'Impossible de vous inscrire. Cette sortie n\'est probablement pas ouverte pour le moment ou est complète.');
            return $this->redirectToRoute('accueil');
        }
    }


    /**
     * @Route(path="/{id}/desistement", name="desistement", methods={"GET"})
     */
    public function desistementSortie(Request $request, EntityManagerInterface $entityManager,
            SortieRepository $sortieRepository, ParticipantRepository $participantRepository) : Response
    {
        // TODO CONDITION POUR LE DESISTEMENT : URL ?

        // Récupération de l'id de la sortie
        $sortie = $sortieRepository->find((int) $request->get('id'));

        // Récupération de l'id de l'utilisateur
        $participant = $participantRepository->find($this->getUser()->getId());

        //($sortie->getParticipants()->current() === $this->getUser()) &&

        if (($sortie->getOrganisateur() !== $this->getUser())
            && (($sortie->getEtat()->getId() == 2) || ($sortie->getEtat()->getId() == 3))
        )
        {
            // On retire l'utilisateur de la liste des participants
            $participant->removeSortie($sortie);

            // Validation de la transaction
            $entityManager->flush();

            // Message de confirmation
            $this->addFlash('danger', 'Vous vous êtes bien désisté(e) de cette sortie !');

            return $this->render('sortie/show.html.twig',[
                'sortie' => $sortie
            ]);

        } else {
            // Message d'erreur
            $this->addFlash('danger', 'Impossible de vous désister.');
            return $this->redirectToRoute('accueil');
        }


    }

    /**
     * @Route(path="/{id}/publier", name="publier", methods={"GET"})
     */
    public function publierSortie(Request $request, EntityManagerInterface $entityManager,
            SortieRepository $sortieRepository, EtatRepository $etatRepository) : Response
    {
        // Récupération de l'id de la sortie
        $sortie = $sortieRepository->find((int) $request->get('id'));

        // Si l'utilisateur est l'organisateur de la sortie : il peut publier la sortie
        if ($sortie->getOrganisateur() === $this->getUser()) {
//            $sortie = $sortieRepository->find((int)$request->get('id'));

            // On instancie l'état de la sortie à 'ouverte'
            $sortie->setEtat($etatRepository->find(2));

            // Validation de la transaction
            $entityManager->flush();

            // Message de confirmation
            $this->addFlash('success', 'Votre sortie a bien été publiée !');

            return $this->render('sortie/show.html.twig', [
                'sortie' => $sortie
            ]);

        } else {
            // Message d'erreur
            $this->addFlash('danger', 'Impossible de publier cette sortie. Seul '. $sortie->getOrganisateur()->getPseudo().' en est capable.'  );
            return $this->redirectToRoute('accueil');
        }

    }

}
