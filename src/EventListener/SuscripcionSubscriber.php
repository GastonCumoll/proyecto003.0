<?php

namespace App\EventListener;

use DateTime;
use App\Entity\Log;
use Doctrine\ORM\Events;
use App\Entity\Suscripcion;
use App\Entity\Edicion;
use App\Repository\SuscripcionRepository;
use App\Repository\EdicionRepository;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Doctrine\Bundle\DoctrineBundle\EventSubscriber\EventSubscriberInterface;
use App\Repository\LogRepository;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;


class SuscripcionSubscriber implements EventSubscriberInterface 
{
    private $suscripcionRepositorio;
    private $mailer;

    public function __construct(SuscripcionRepository $repositorio, MailerInterface $mailer){
        $this->suscripcionRepositorio = $repositorio;
        $this->mailer = $mailer;
    }
    // this method can only return the event names; you cannot define a
    // custom method name to execute when each event triggers
    public function getSubscribedEvents(): array
    {
        return [
            Events::postPersist,
            Events::onFlush,
        ];
    }
    public function postPersist(LifecycleEventArgs $args): void
    {
        //aca la logica de enviar mail
        //$args= la entidad
        


        

    }

    public function onFlush(OnFlushEventArgs $eventArgs)
    {   
        $em = $eventArgs->getEntityManager();
        $uow = $em->getUnitOfWork();
        foreach ($uow->getScheduledEntityInsertions() as $entity) {
            
            if ($entity instanceof Suscripcion){
                $log=new Log();
                $log->setTipoOperacion("Suscripcion");
                $today=new DateTime();
                $log->setfechaYHora($today);
                $log->setTipoPublicacion($entity->getTipoPublicacion()->getNombre());
                $log->setUsuario($entity->getUsuario()->getEmail());
                $em->persist($log);
                $uow->computeChangeSet($em->getClassMetaData(get_class($log)),$log);

                $email=(new Email())
                ->from('carrascoalexis_30@outlook.com')
                ->to('alex_20@gmail.com')
                ->subject('BIENVENIDO!!!')
                ->text('Suscripción al tipo de publicación "'.$log->getTipoPublicacion().'" realizada correctamente');
                $this->mailer->send($email);

            }

            if ($entity instanceof Edicion){

                $today = new DateTime();
                //fecha formateada a string
                $fecha = $today->format('Y-m-d H:i:s');

                $email=(new Email())
                ->from('carrascoalexis_30@outlook.com')
                ->to('alex_20@gmail.com')
                ->subject('NUEVA PUBLICACIÓN "'.$entity->getPublicacion()->getTitulo().'".')
                ->text('Se realizó una nueva publicación de tipo "'.$entity->getPublicacion()->getTipoPublicacion()->getNombre().'" titulada como "'.$entity->getPublicacion()->getTitulo().'" creada por "'.$entity->getUsuarioCreador().'" en la fecha '.$fecha.'. Inicie sesión en su cuenta para verla.');
                $this->mailer->send($email);

            }
       
        }

        foreach ($uow->getScheduledEntityDeletions() as $entity) {
            if ($entity instanceof Suscripcion){
                $log=new Log();
                $log->setTipoOperacion("Desuscripcion");
                $today=new DateTime();
                $log->setfechaYHora($today);
                $log->setTipoPublicacion($entity->getTipoPublicacion()->getNombre());
                $log->setUsuario($entity->getUsuario()->getEmail());
                $em->persist($log);
                $uow->computeChangeSet($em->getClassMetaData(get_class($log)),$log);

                $email=(new Email())
                ->from('carrascoalexis_30@outlook.com')
                ->to('alex_20@gmail.com')
                ->subject('BIENVENIDO!!!')
                ->text('Desuscripción al tipo de publicación "'.$log->getTipoPublicacion().'" realizada correctamente');
                $this->mailer->send($email);
            }
        }
    }


}