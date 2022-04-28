<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CampusController extends AbstractController
{
    /**
     * @Route("/campus", name="campus_index", methods={"GET"})
     */
    public function index(CampusRepository $campusRepository): Response
    {
        return $this->render('campus/index.html.twig', [

            'campus' => $campusRepository->findAll(),
        ]);
    }
    /**
     * @Route("/new", name="campus_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $campus = new Campus();
        $form = $this->createForm(CampusType::class, $campus);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($campus);
            $entityManager->flush();
            $this->addFlash("success", "Le campus vient d'être ajouté");
            return $this->redirectToRoute('campus');
        }

        return $this->render('campus/new.html.twig', [
            'campus' => $campus,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/show", name="campus_show", methods={"GET"})
     */
    public function show(Campus $campus): Response
    {
        return $this->render('campus/show.html.twig', [
            'campus' => $campus,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="campus_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Campus $campus): Response
    {
        $form = $this->createForm(campusType::class, $campus);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash("success", "Le campus vient d'être modifié");
            return $this->redirectToRoute('campus_index');
        }

        return $this->render('campus/edit.html.twig', [
            'campus' => $campus,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="campus_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Campus $campus): Response
    {
        if ($this->isCsrfTokenValid('delete'.$campus->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($campus);
            $entityManager->flush();
            $this->addFlash("danger", "Le campus vient d'être supprimé");
        }

        return $this->redirectToRoute('campus_index');
    }

//    /**
//     * @Route("/recherche/", name="campus_recherche", methods={"GET"})
//     */
//    public function rechercher(Request $request, EntityManagerInterface $entityManager): Response
//    {
//        $rechercher = true;
//        $request = Request::createFromGlobals();
//        $recherche = $request->query->get('recherche');
//        $listeCampus = $entityManager->getRepository('App:Campus')->getByMotCle($recherche);
//        /*$listeCampus = $entityManager->getRepository('App:Campus')->getByMotCle($recherche["nom"]);*/
//        return $this->render("campus/listeParticipants.html.twig", ["listeCampus" => $listeCampus, "rechercher" => $rechercher]);
//    }


}
