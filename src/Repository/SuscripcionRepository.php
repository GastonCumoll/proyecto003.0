<?php

namespace App\Repository;

use App\Entity\Suscripcion;
use App\Entity\TipoPublicacion;
use App\Entity\Publicacion;
use App\Entity\Edicion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Suscripcion|null find($id, $lockMode = null, $lockVersion = null)
 * @method Suscripcion|null findOneBy(array $criteria, array $orderBy = null)
 * @method Suscripcion[]    findAll()
 * @method Suscripcion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SuscripcionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Suscripcion::class);
    }


    /**
     * @return Publicacion[]
     */
    public function buscarTipoPublicacion($usuario): array
    {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery(
            'SELECT ediciones FROM App:Publicacion publicaciones, App:Edicion ediciones WHERE (ediciones.publicacion = publicaciones) AND  publicaciones.tipoPublicacion IN (SELECT tipoPublicacion From App:Suscripcion suscripcion, App:TipoPublicacion tipoPublicacion 
            where suscripcion.usuario = :usuario AND suscripcion.tipoPublicacion = tipoPublicacion)
            ORDER BY ediciones.fechaYHoraCreacion DESC' )
        ->setParameter('usuario', $usuario);
        return $query->getResult();
    }

    // /**
    //  * @return TipoPublicacion[]
    //  */
    // public function buscarTipoPublicacion($usuario): array
    // {
    //     $entityManager = $this->getEntityManager();
    //     $query = $entityManager->createQuery(
    //         'SELECT tipoPublicacion FROM App:Publicacion publicaciones, App:Edicion ediciones WHERE (ediciones.publicacion = publicaciones) AND  publicaciones.tipoPublicacion IN (SELECT tipoPublicacion From App:Suscripcion suscripcion, App:TipoPublicacion tipoPublicacion 
    //         where suscripcion.usuario = :usuario AND suscripcion.tipoPublicacion = tipoPublicacion)
    //         ORDER BY ediciones.fechaYHoraCreacion')
    //     ->setParameter('usuario', $usuario);
    //     return $query->getResult();
    //}
    
    // /**
    //  * @return Suscripcion[] Returns an array of Suscripcion objects
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
    public function findOneBySomeField($value): ?Suscripcion
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
