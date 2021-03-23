<?php

namespace App\Repository;

use App\Data\SearchData;
use App\Entity\Outing;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Outing|null find($id, $lockMode = null, $lockVersion = null)
 * @method Outing|null findOneBy(array $criteria, array $orderBy = null)
 * @method Outing[]    findAll()
 * @method Outing[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OutingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Outing::class);
    }


    /**
     * @param SearchData $search
     * @return Outing[]
     * Permet de récupérer les sorties en lien avec une recherche
     * (on filtre les sorties selon des critères)
     */
    public function findSearch(SearchData $search, User $userInSession) {

        $query = $this
            //On récupère les sorties :
            ->createQueryBuilder('o')
            ->select('c', 'o', 'u', 's')
            //On fait une liaison avec Campus, User et State :
            ->join('o.campusOrganizer', 'c')
            ->join('o.organizer', 'u')
            ->join('o.state', 's')
            //On classe par ordre croissant du plus récent au moins récent :
            ->orderBy('o.startingDateTime', 'ASC')

            ;

        //Si la barre de recherche n'est pas vide :
        if(!empty($search->q)) {
            $query = $query
                ->andWhere('o.name LIKE :q')
                ->setParameter('q', "%{$search->q}%");
        }

        //TODO : gérer les filtres date !
        if(!empty($search->campus)) {
            $query = $query
                ->andWhere('c.id IN (:campus)')
                ->setParameter('campus', $search->campus);
        }


        if(!empty($search->mindate) && !empty($seach->maxdate) && ($search->maxdate) > ($search->mindate)) {
            $query = $query
                ->andWhere('o.startingDateTime BETWEEN :mindate AND :maxdate')
                ->setParameter('mindate', $search->mindate)
                ->setParameter('maxdate', $search->maxdate)
            ;

        }


        if(!empty($search->mindate)) {
            $query = $query
                ->andWhere('o.startingDateTime >= :mindate')
                ->setParameter('min', $search->mindate)
                ;
        }

        if(!empty($search->organizer)) {
            $query = $query
                ->andWhere('u = :u')
                ->setParameter('u', $userInSession);
        }

        if(!empty($search->participant)) {
            $query = $query
                ->andWhere(':u MEMBER OF o.participants')
                ->setParameter('u', $userInSession);
        }


        //TODO : not participant !
        if (!empty($search->notParticipant)) {
            $query;       }

        //TODO : sorties passées (fermées) !
        if (!empty($search->pastOutings)) {
            $query = $query
                ->andWhere('s = :s')
                ->setParameter('s', 'Fermée');
        }

        dump($query);
        dump($search);

        return $query->getQuery()->getResult();



    }

    // /**
    //  * @return Outing[] Returns an array of Outing objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Outing
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
