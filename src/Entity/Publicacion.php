<?php

namespace App\Entity;

use App\Repository\PublicacionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PublicacionRepository::class)
 */
class Publicacion
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $titulo;

    

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $fechaYHora;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="publicacionesDeUsuario")
     * @ORM\JoinColumn(nullable=false)
     */
    private $usuarioCreador;

    /**
     * @ORM\OneToMany(targetEntity=Edicion::class, mappedBy="publicacion", orphanRemoval=true)
     */
    private $edidicones;

    /**
     * @ORM\OneToMany(targetEntity=Suscripcion::class, mappedBy="publicacion", orphanRemoval=true)
     */
    private $suscripciones;

    /**
     * @ORM\ManyToOne(targetEntity=TipoPublicacion::class, inversedBy="publicacions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $tipoPublicacion;




    public function __toString() {
        return $this->titulo;
    }
    public function __construct()
    {
        $this->edidicones = new ArrayCollection();
        $this->suscripciones = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitulo(): ?string
    {
        return $this->titulo;
    }

    public function setTitulo(string $titulo): self
    {
        $this->titulo = $titulo;

        return $this;
    }


    public function getFechaYHora(): ?\DateTimeInterface
    {
        return $this->fechaYHora;
    }

    public function setFechaYHora(\DateTimeInterface $fechaYHora): self
    {
        $this->fechaYHora = $fechaYHora;

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

    /**
     * @return Collection|Edicion[]
     */
    public function getEdidicones(): Collection
    {
        return $this->edidicones;
    }

    public function addEdidicone(Edicion $edidicone): self
    {
        if (!$this->edidicones->contains($edidicone)) {
            $this->edidicones[] = $edidicone;
            $edidicone->setPublicacion($this);
        }

        return $this;
    }

    public function removeEdidicone(Edicion $edidicone): self
    {
        if ($this->edidicones->removeElement($edidicone)) {
            // set the owning side to null (unless already changed)
            if ($edidicone->getPublicacion() === $this) {
                $edidicone->setPublicacion(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Suscripcion[]
     */
    public function getSuscripciones(): Collection
    {
        return $this->suscripciones;
    }

    public function addSuscripcione(Suscripcion $suscripcione): self
    {
        if (!$this->suscripciones->contains($suscripcione)) {
            $this->suscripciones[] = $suscripcione;
            $suscripcione->setPublicacion($this);
        }

        return $this;
    }

    public function removeSuscripcione(Suscripcion $suscripcione): self
    {
        if ($this->suscripciones->removeElement($suscripcione)) {
            // set the owning side to null (unless already changed)
            if ($suscripcione->getPublicacion() === $this) {
                $suscripcione->setPublicacion(null);
            }
        }

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
