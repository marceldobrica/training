<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class User
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public string $email = '';

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $password;

    /**
     * @ORM\Column(type="json")
     */
    private Collection $roles;

    /**
     * @ORM\Column(type="string", length=13, nullable="false")
     */
    public string $cnp = '';

    /**
     * @ORM\Column(type="string", length=255, nullable="false")
     */
    public string $firstName = '';

    /**
     * @ORM\Column(type="string", length=255, nullable="false")
     */
    public string $lastName = '';

    /**
     * @ORM\ManyToMany(targetEntity="Programme", mappedBy="customers")
     */
    private Collection $programmes;

    public function __construct()
    {
        $this->programmes = new ArrayCollection();
        $this->roles = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getRoles(): Collection
    {
        return $this->roles;
    }

    public function setRoles(Collection $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function addRole(string $role): self
    {
        if ($this->roles->contains($role)) {
            return $this; //TODO log message already have this role....should never be here
        }
        $this->roles->add($role);

        return $this;
    }

    public function removeRole(string $role): self
    {
        if (!$this->roles->contains($role)) {
            return $this; //TODO log message you don't have this role....should never be here
        }
        $this->roles->removeElement($role);

        return $this;
    }


    public function getProgrammes(): Collection
    {
        return $this->programmes;
    }

    public function setProgrammes(Collection $programmes): self
    {
        $this->programmes = $programmes;

        return $this;
    }

    public function addProgramme(Programme $programme): self
    {
        if ($this->programmes->contains($programme)) {
            return $this; //TODO ... send message from User or from Programme...not from both
        }

        $this->programmes->add($programme);
        $programme->addCustomer($this);

        return $this;
    }

    public function removeProgramme(Programme $programme): self
    {
        if (!$this->programmes->contains($programme)) {
            return $this; //TODO ... send message from User or from Programme...not from both
        }

        $this->programmes->removeElement($programme);
        $programme->removeCustomer($this);

        return $this;
    }
}
