<?php

namespace App\Controller;

use App\Entity\Lieu;
use App\Entity\Ville;
use App\Repository\LieuRepository;
use App\Repository\VilleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LieuController extends AbstractController
{
    /**
     * @Route("/lieu", name="lieu")
     */
    public function index(): Response
    {
        return $this->render('lieu/home.html.twig', [
            'controller_name' => 'LieuController',
        ]);
   }
    /**
     * @Route("/recherche", name="lieu_recherche", methods={"GET"})
     */
    public function lieu_search(LieuRepository $lieuRepository): Response
    {

        return $this->render('lieu/home.html.twig', [
            'lieu' => $lieuRepository->findAll(),
        ]);
    }
    /**
     * @Route("/nouveau", name="lieu_nouveau", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $lieu = new Lieu();
        $form = $this->createForm(LieuType::class, $lieu);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($lieu);
            $entityManager->flush();
            $this->addFlash("success", "Le lieu vient d'être ajouté");
            return $this->redirectToRoute('lieu');
        }

        return $this->render('lieu/new.html.twig', [
            'lieu' => $lieu,
            'form' => $form->createView(),
        ]);
    }


}
