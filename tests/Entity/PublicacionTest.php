<?php

namespace App\Entity;

use App\Repository\PublicacionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * @ORM\Entity(repositoryClass=PublicacionRepository::class)
 */
class PublicacionTest extends WebTestCase
{

    public function getId(): ?int
    {
        return $this->id;
    }

    public function testGetTitulo(): ?string
    {   
        $this->titulo='titulo';
        $this->assertIsString($this->titulo);
        return $this->titulo;
    }

    


    public function getFechaYHora(): ?\DateTimeInterface
    {

        return $this->fechaYHora;
    }


    public function getUsuarioCreador(): ?User
    {
        return $this->usuarioCreador;
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
