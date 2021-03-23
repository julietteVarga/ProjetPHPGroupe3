<?php

namespace App\Repository;

use App\Data\SearchData;
use App\Entity\Outing;
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
    public function findSearch(SearchData $search) {

        $query = $this
            //On récupère les sorties :
            ->createQueryBuilder('o')
            ->select('c', 'o')
            //On fait une liaison avec Campus :
            ->join('o.campusOrganizer', 'c');

        dump($search->q);
        //Si la barre de recherche n'est pas vide :
        if(!empty($search->q)) {
            $query = $query
                ->andWhere('o.name LIKE :q')
                ->setParameter('q', "%{$search->q}%");
        }

        if(!empty($search->campus)) {
            $query = $query
                ->andWhere('c.id IN (:campus)')
                ->setParameter('campus', $search->campus);
        }

        if(!empty($search->mindate)) {
            $query = $query
                ->andWhere('o.startingDateTime >= :mindate')
                ->setParameter('min', $search->mindate)
                ;
        }

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
