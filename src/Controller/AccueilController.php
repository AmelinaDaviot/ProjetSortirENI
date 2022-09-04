<?php

namespace App\Controller;

use App\Repository\CampusRepository;
use App\Repository\EtatRepository;
use App\Repository\SortieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccueilController extends AbstractController
{
    /*
    private function __construct()
    {
        $sortieRepository = new SortieRepository();

    }
    */

    /**
     * @Route("/", name="accueil")
     */
    public function accueil(
        Request $request,
        SortieRepository $sortieRepository,
        CampusRepository $campusRepository,
        EtatRepository $etatRepository
    ): Response
    {


        //recuperation de tous les campus
        $campus = $campusRepository->findAll();
        //recuperation de tous les etats
        $etats = $etatRepository->findAll();

        $sorties = $sortieRepository->findAll();

        //délégation du travail au twig liste.html.twig en y passant en parametre les sorties filtrées, les sites et les etats
        return $this->render("accueil/accueil.html.twig", [
            'sorties' => $sorties,
            'campus' => $campus,
            'etats' => $etats
        ]);
    }

    /**
     * @Route("/rechercher", name="rechercher")
     */
    public function rechercher(
        Request $request,
        SortieRepository $sortieRepository,
        CampusRepository $campusRepository,
        EtatRepository $etatRepository
    ): Response
    {

        //recuperation des champs saisies dans le formulaire provenant du contexte de requete
       //Si l'utilisateur n'est pas encore connecté, il lui sera demandé de se connecter (par exemple redirigé vers la page de connexion).
        //Si l'utilisateur est connecté, mais n'a pas le rôle ROLE_USER, il verra la page 403 (accès refusé) … il faudra créer de belles pages d’erreur 404 et 403 visible en prod
        $this->denyAccessUnlessGranted('ROLE_USER');

        //dd($request->query->get('recherche_campus'), $request->query->get('recherche_etat') );
        //appel de la methode rechercheDetaillee dans SortieRepository afin de recupérer les sorties filtrées
        // le repository se charge d'exécuter la requête SQL
        $sorties = $sortieRepository->rechercheDetaillee(
            ($request->query->get('recherche_campus') != null ? $request->query->get('recherche_campus') : null),
            ($request->query->get('recherche_terme') != null ? $request->query->get('recherche_terme') : null),
            ($request->query->get('recherche_etat') != null ? $request->query->get('recherche_etat') : null),
            ($request->query->get('date_debut') != null ? $request->query->get('date_debut') : null),
            ($request->query->get('date_fin') != null ? $request->query->get('date_fin') : null),
            ($request->query->get('cb_organisateur') != null ? $request->query->get('cb_organisateur') : null),
            ($request->query->get('cb_inscrit') != null ? $request->query->get('cb_inscrit') : null),
            ($request->query->get('cb_non_inscrit') != null ? $request->query->get('cb_non_inscrit') : null),
            ($request->query->get('cb_passee') != null ? $request->query->get('cb_passee') : null)
        );
        //recuperation de tous les campus
        $campus = $campusRepository->findAll();
        //recuperation de tous les etats
        $etats = $etatRepository->findAll();


        //délégation du travail au twig liste.html.twig en y passant en parametre les sorties filtrées, les sites et les etats
        return $this->render("accueil/accueil.html.twig", [
            'sorties' => $sorties,
            'campus' => $campus,
            'etats' => $etats
        ]);
    }


}
