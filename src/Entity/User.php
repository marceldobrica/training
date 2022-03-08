<?php

namespace App\Entity;

use Doctrine\Common\Collections\Collection;

class User
{
    private int $id;

    private string $password;

    private Collection $roles;

    public string $cnp = '';

    public string $firstName = '';

    public string $lastName = '';

    private Collection $programmes;

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

    public function getPrograms(): Collection
    {
        return $this->programmes;
    }

    public function setPrograms(Collection $programmes): self
    {
        $this->programmes = $programmes;

        return $this;
    }
}
