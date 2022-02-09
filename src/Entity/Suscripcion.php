<?php

namespace App\Entity;

use App\Repository\SuscripcionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SuscripcionRepository::class)
 */
class Suscripcion
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $fechaSuscripcion;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="suscripciones")
     */
    private $usuario;

    /**
     * @ORM\ManyToOne(targetEntity=TipoPublicacion::class, inversedBy="Suscripciones")
     * @ORM\JoinColumn(nullable=false)
     */
    private $tipoPublicacion;



    public function getId(): ?int
    {
        return $this->id;
    }


    public function getFechaSuscripcion(): ?\DateTimeInterface
    {
        return $this->fechaSuscripcion;
    }

    public function setFechaSuscripcion(\DateTimeInterface $fechaSuscripcion): self
    {
        $this->fechaSuscripcion = $fechaSuscripcion;

        return $this;
    }

    public function getUsuario(): ?User
    {
        return $this->usuario;
    }

    public function setUsuario(?User $usuario): self
    {
        $this->usuario = $usuario;

        return $this;
    }

    public function getTipoPublicacion(): ?TipoPublicacion
    {
        return $this->tipoPublicacion;
    }

    public function setTipoPublicacion(?TipoPublicacion $tipoPublicacion): self
    {
        $this->tipoPublicacion = $tipoPublicacion;

        return $this;
    }

}
