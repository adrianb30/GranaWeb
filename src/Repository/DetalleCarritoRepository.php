<?php

namespace App\Repository;

use App\Entity\DetalleCarrito;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DetalleCarrito>
 *
 * @method DetalleCarrito|null find($id, $lockMode = null, $lockVersion = null)
 * @method DetalleCarrito|null findOneBy(array $criteria, array $orderBy = null)
 * @method DetalleCarrito[]    findAll()
 * @method DetalleCarrito[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DetalleCarritoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DetalleCarrito::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(DetalleCarrito $entity, bool $flush = true): void
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
    public function remove(DetalleCarrito $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return DetalleCarrito[] Returns an array of DetalleCarrito objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?DetalleCarrito
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
