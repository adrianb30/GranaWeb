<?php

namespace App\Repository;

use App\Entity\CarritoDetalle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CarritoDetalle>
 *
 * @method CarritoDetalle|null find($id, $lockMode = null, $lockVersion = null)
 * @method CarritoDetalle|null findOneBy(array $criteria, array $orderBy = null)
 * @method CarritoDetalle[]    findAll()
 * @method CarritoDetalle[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CarritoDetalleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CarritoDetalle::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(CarritoDetalle $entity, bool $flush = true): void
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
    public function remove(CarritoDetalle $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }
    public function countItems($id)
    {
        return $this->createQueryBuilder('fc')
            ->andWhere('fc.carrito = :carrito')
            ->setParameter('carrito', $id)
            ->select('Count(fc.id) as total_Items')
            ->getQuery()
            ->getResult();
    }
     /**
     * @return CarritoDetalle[] Returns an array of CarritoDetalle objects
     */
    
    public function findByCarritoProducto($value1,$value2)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.producto = :val1 and c.carrito = :val2')
            ->setParameter('val1', $value1)
            ->setParameter('val2', $value2)
            ->getQuery()
            ->getResult()
        ;
    }
    




    // /**
    //  * @return CarritoDetalle[] Returns an array of CarritoDetalle objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CarritoDetalle
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
