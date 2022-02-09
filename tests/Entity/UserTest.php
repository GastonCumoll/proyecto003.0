<?php

namespace App\Entity;

use App\Form\RegistrationType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use PHPUnit\Framework\TestCase;
use Doctrine\ORM\Mapping as ORM;

use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 */
class UserTest extends WebTestCase
{
    public function testGetId(): ?int
    {
        $this->id=4;
        $this->assertIsInt($this->id);
        return $this->id;
    }

    public function testGetEmail(): ?string
    {   
        
        
        $this->email='gaston@hotmail.com';
        

        $this->assertIsString($this->email);
        //$this->assertSelectorTextContains($this->email, '@');
        return $this->email;
    }

    
    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return Collection|Publicacion[]
     */
    public function getPublicacionesDeUsuario(): Collection
    {
        return $this->publicacionesDeUsuario;
    }

    public function addPublicacionesDeUsuario(Publicacion $publicacionesDeUsuario): self
    {
        if (!$this->publicacionesDeUsuario->contains($publicacionesDeUsuario)) {
            $this->publicacionesDeUsuario[] = $publicacionesDeUsuario;
            $publicacionesDeUsuario->setUsuarioCreador($this);
        }

        return $this;
    }

    public function removePublicacionesDeUsuario(Publicacion $publicacionesDeUsuario): self
    {
        if ($this->publicacionesDeUsuario->removeElement($publicacionesDeUsuario)) {
            // set the owning side to null (unless already changed)
            if ($publicacionesDeUsuario->getUsuarioCreador() === $this) {
                $publicacionesDeUsuario->setUsuarioCreador(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Edicion[]
     */
    public function getEdiciones(): Collection
    {
        return $this->ediciones;
    }

    public function addEdicione(Edicion $edicione): self
    {
        if (!$this->ediciones->contains($edicione)) {
            $this->ediciones[] = $edicione;
            $edicione->setUsuarioCreador($this);
        }

        return $this;
    }

    public function removeEdicione(Edicion $edicione): self
    {
        if ($this->ediciones->removeElement($edicione)) {
            // set the owning side to null (unless already changed)
            if ($edicione->getUsuarioCreador() === $this) {
                $edicione->setUsuarioCreador(null);
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
            $suscripcione->setUsuario($this);
        }

        return $this;
    }

    public function removeSuscripcione(Suscripcion $suscripcione): self
    {
        if ($this->suscripciones->removeElement($suscripcione)) {
            // set the owning side to null (unless already changed)
            if ($suscripcione->getUsuario() === $this) {
                $suscripcione->setUsuario(null);
            }
        }

        return $this;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }



    

    
}