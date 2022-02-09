<?php

namespace App\Entity;

use App\Repository\EdicionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EdicionRepository::class)
 */
class Edicion
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
    private $fechaDeEdicion;

    /**
     * @ORM\Column(type="integer")
     */
    private $cantidadImpresiones;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $fechaYHoraCreacion;

    

    /**
     * @ORM\ManyToOne(targetEntity=Publicacion::class, inversedBy="edidicones")
     * @ORM\JoinColumn(nullable=false)
     */
    private $publicacion;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="ediciones")
     * @ORM\JoinColumn(nullable=false)
     */
    private $usuarioCreador;

    public function __toString() {
        return $this->name;
    }
    

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFechaDeEdicion(): ?\DateTimeInterface
    {
        return $this->fechaDeEdicion;
    }

    public function setFechaDeEdicion(\DateTimeInterface $fechaDeEdicion): self
    {
        $this->fechaDeEdicion = $fechaDeEdicion;

        return $this;
    }

    public function getCantidadImpresiones(): ?int
    {
        return $this->cantidadImpresiones;
    }

    public function setCantidadImpresiones(int $cantidadImpresiones): self
    {
        $this->cantidadImpresiones = $cantidadImpresiones;

        return $this;
    }

    public function getFechaYHoraCreacion(): ?\DateTimeInterface
    {
        return $this->fechaYHoraCreacion;
    }

    public function setFechaYHoraCreacion(\DateTimeInterface $fechaYHoraCreacion): self
    {
        $this->fechaYHoraCreacion = $fechaYHoraCreacion;

        return $this;
    }



    public function getPublicacion(): ?Publicacion
    {
        return $this->publicacion;
    }

    public function setPublicacion(?Publicacion $publicacion): self
    {
        $this->publicacion = $publicacion;

        return $this;
    }

    public function getUsuarioCreador(): ?User
    {
        return $this->usuarioCreador;
    }

    public function setUsuarioCreador(?User $usuarioCreador): self
    {
        $this->usuarioCreador = $usuarioCreador;

        return $this;
    }
}
