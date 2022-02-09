<?php

namespace App\Entity;

use App\Repository\LogRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LogRepository::class)
 */
class Log
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
    private $tipoOperacion;

    /**
     * @ORM\Column(type="datetime")
     */
    private $fechaYHora;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $tipoPublicacion;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $usuario;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTipoOperacion(): ?string
    {
        return $this->tipoOperacion;
    }

    public function setTipoOperacion(string $tipoOperacion): self
    {
        $this->tipoOperacion = $tipoOperacion;

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

    public function getTipoPublicacion(): ?string
    {
        return $this->tipoPublicacion;
    }

    public function setTipoPublicacion(string $tipoPublicacion): self
    {
        $this->tipoPublicacion = $tipoPublicacion;

        return $this;
    }

    public function getUsuario(): ?string
    {
        return $this->usuario;
    }

    public function setUsuario(string $usuario): self
    {
        $this->usuario = $usuario;

        return $this;
    }
}
