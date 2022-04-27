<?php

namespace App\Controller;

use App\Entity\Sortie;
use App\Entity\Participant;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccueilController extends AbstractController
{
    /**
     * Page d'accueil du site
     *
     * @Route("/", name="accueil")
     */
    public function home(): Response
    {
//        $message = null;
//        $participantRepo = $this->getDoctrine()->getRepository(Participant::class);
//        $sortieRepo = $this->getDoctrine()->getRepository(Sortie::class);
//
//        $toutesLesSorties = $sortieRepo->findAll();
//
//        $participants = $participantRepo->totalParticipantsInscrits();
//        $sorties = $sortieRepo->totalSortiesOrganisees();

//        dump($participants);
//        dump($sorties);

        return $this->render('accueil/home.html.twig.', [
            'controller_name' => 'AccueilController',
//            'participants' => $participants,
//            'entities' => $toutesLesSorties,
//            'message' => $message,
//            'sorties' => $sorties

        ]);
    }
}
