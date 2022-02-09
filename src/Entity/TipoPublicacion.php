<?php

namespace App\Entity;

use App\Repository\TipoPublicacionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TipoPublicacionRepository::class)
 */
class TipoPublicacion
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
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity=Publicacion::class, mappedBy="tipoPublicacion", orphanRemoval=true)
     */
    private $publicacions;

    /**
     * @ORM\OneToMany(targetEntity=Suscripcion::class, mappedBy="tipoPublicacion", orphanRemoval=true)
     */
    private $Suscripciones;



    public function __construct()
    {
        $this->publicacions = new ArrayCollection();
        $this->suscripcions = new ArrayCollection();
        $this->Suscripciones = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * @return Collection|Publicacion[]
     */
    public function getPublicacions(): Collection
    {
        return $this->publicacions;
    }

    public function addPublicacion(Publicacion $publicacion): self
    {
        if (!$this->publicacions->contains($publicacion)) {
            $this->publicacions[] = $publicacion;
            $publicacion->setTipoPublicacion($this);
        }

        return $this;
    }

    public function removePublicacion(Publicacion $publicacion): self
    {
        if ($this->publicacions->removeElement($publicacion)) {
            // set the owning side to null (unless already changed)
            if ($publicacion->getTipoPublicacion() === $this) {
                $publicacion->setTipoPublicacion(null);
            }
        }

        return $this;
    }

    public function __toString() {
        return $this->nombre;
    }

    /**
     * @return Collection|Suscripcion[]
     */
    public function getSuscripcions(): Collection
    {
        return $this->suscripcions;
    }

    /**
     * @return Collection|Suscripcion[]
     */
    public function getSuscripciones(): Collection
    {
        return $this->Suscripciones;
    }

    public function addSuscripcione(Suscripcion $suscripcione): self
    {
        if (!$this->Suscripciones->contains($suscripcione)) {
            $this->Suscripciones[] = $suscripcione;
            $suscripcione->setTipoPublicacion($this);
        }

        return $this;
    }

    public function removeSuscripcione(Suscripcion $suscripcione): self
    {
        if ($this->Suscripciones->removeElement($suscripcione)) {
            // set the owning side to null (unless already changed)
            if ($suscripcione->getTipoPublicacion() === $this) {
                $suscripcione->setTipoPublicacion(null);
            }
        }

        return $this;
    }

}
