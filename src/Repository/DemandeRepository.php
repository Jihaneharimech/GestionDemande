<?php

namespace App\Repository;

use DateTime;
use App\Entity\Demande;
use App\Classe\CustomSearch;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Demande>
 *
 * @method Demande|null find($id, $lockMode = null, $lockVersion = null)
 * @method Demande|null findOneBy(array $criteria, array $orderBy = null)
 * @method Demande[]    findAll()
 * @method Demande[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DemandeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Demande::class);
    }

    public function save(Demande $entity, bool $flush = false): void
    { 
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Demande $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return Demande[] Returns an array of Demande objects
     */
    public function findByVille($ville): array
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.ville = :ville')
            ->setParameter('ville', $ville)
            ->orderBy('d.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return Demande[] 
     */
    public function findByIdUser($idManager): array
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.manager = :idManager')
            ->setParameter('idManager', $idManager)
            ->orderBy('d.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

            /**
     * Requete qui permet de recuperer les demandes en faction de la recherche de l'utilisateur
     * @return Demandes[] Returns an array of demande objects
    */
    public function findWithCustomSearch(CustomSearch $search, $idManager): array
    {
        $query = $this
            ->createQueryBuilder('d')
            ->select('v','d','s','a')
            ->join('d.ville','v')
            ->join('d.statut','s')
            ->join('d.typeAppareil','a');

            if(!empty($search->ville)){
                $query = $query
                ->andWhere('v.id IN (:ville)') 
                ->andWhere('d.manager = :idManager') 
                ->setParameter('ville', $search->ville)
                ->setParameter('idManager', $idManager);
            }

            if(!empty($search->typeAppareil)){
                $query = $query
                ->andWhere('a.id IN (:typeAppareil)') 
                ->andWhere('d.manager = :idManager') 
                ->setParameter('typeAppareil', $search->typeAppareil)
                ->setParameter('idManager', $idManager);
            }

            if(!empty($search->statut)){
                $query = $query
                ->andWhere('s.id IN (:statut)') 
                ->andWhere('d.manager = :idManager') 
                ->setParameter('statut', $search->statut)
                ->setParameter('idManager', $idManager);
            }

            if(!empty($search->string)){
                $query = $query
                ->andWhere('d.nomClient LIKE :string')
                ->andWhere('d.manager = :idManager')
                ->setParameter('string', "%{$search->string}%")
                ->setParameter('idManager', $idManager);;
            }

            return $query->getQuery()->getResult();
    }


//    /**
//     * @return Demande[] Returns an array of Demande objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('d.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Demande
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
