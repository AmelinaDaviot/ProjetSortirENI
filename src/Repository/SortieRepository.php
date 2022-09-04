<?php

namespace App\Repository;

use App\Entity\Participant;
use App\Entity\Sortie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;
use Exception;

/**
 * @extends ServiceEntityRepository<Sortie>
 *
 * @method Sortie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sortie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sortie[]    findAll()
 * @method Sortie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SortieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sortie::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Sortie $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Sortie $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }



    /**
     * Fonction permettant de traiter le formulaire de filtre du twig liste.html.twig
     *
     * @param null $recherche_terme Recherche les sorties par mot-clé
     * @param null $recherche_campus Recherche les sorties par l'identifiant du site
//     * @param null $etat Recherche les sorties par l'identifiant de l'état
     * @param null $date_debut Recherche les sorties dont la date de debut est supérieure à une date selectionnée
     * @param null $date_fin Recherche les sorties dont la date de fin est inférieurs à une date selectionnée
     * @param null $organisateur Recherche les sorties dont je suis l'organisateur.trice
     * @param null $inscrit Recherche les sorties auxquelles je suis inscrit.e
     * @param null $non_inscrit Recherche les sorties auxquelles je ne suis pas inscrit.e
     * @param null $passee Recherche les sorties passées
     * @return Query
     * @throws Exception
     */
    public function rechercheDetaillee($recherche_campus = null, $recherche_terme = null, $recherche_etat = null, $date_debut = null,
                                       $date_fin = null, $cb_organisateur = null, $cb_inscrit = null,
                                       $cb_non_inscrit = null, $cb_passee = null) {
        $qb = $this->createQueryBuilder('sortie')
            ->join('sortie.campus', 'campus')
            ->join('sortie.organisateur', 'organisateur')
            ->join('sortie.etat' , 'etat')
            ->addSelect('campus')
            ->addSelect('organisateur')
            ->addSelect('etat');

        if($recherche_terme != null){
            $qb->andWhere('sortie.nom LIKE :recherche_terme')
                ->setParameter('recherche_terme', '%'.$recherche_terme.'%');
        }
        if($recherche_campus > 0){
            $qb->andWhere('campus.id = :recherche_campus')
                ->setParameter('recherche_campus', $recherche_campus);
        }
        if($recherche_etat > 0){
            $qb->andWhere('etat.id = :etat')
                ->setParameter('etat', $recherche_etat);
        }
        if($date_debut != null){
            $qb->andWhere('sortie.dateHeureDebut > :date_debut')
                ->setParameter('date_debut', new \DateTime($date_debut));
        }
        if($date_fin != null){
            $qb->andWhere('sortie.dateHeureDebut < :date_fin')
                ->setParameter('date_fin', new \DateTime($date_fin));
        }
        if($cb_organisateur != null){
            $organisateur = $user = $this->getEntityManager()->getRepository(Participant::class)->find($cb_organisateur);
            $qb->andWhere('sortie.organisateur = :organisateur')
                ->setParameter('organisateur', $organisateur);
        }
        if($cb_inscrit != null){
            $user = $this->getEntityManager()->getRepository(Participant::class)->find($cb_inscrit);
            $qb->andWhere(':inscrit MEMBER OF sortie.participants')
                ->setParameter('inscrit', $user);
        }
        if($cb_non_inscrit != null){
            $user = $this->getEntityManager()->getRepository(Participant::class)->find($cb_non_inscrit);
            $qb->andWhere(':inscrit NOT MEMBER OF sortie.participants')
                ->setParameter('inscrit', $user);
        }
        if($cb_passee != null){
            $qb->andWhere('etat.libelle = :etatLib')
                ->setParameter('etatLib', 'Passée');
        }
        $result = $qb->getQuery()->getResult();
        return $result;
    }


    // /**
    //  * @return Sortie[] Returns an array of Sortie objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Sortie
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
