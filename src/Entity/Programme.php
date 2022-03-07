<?php

namespace App\Entity;

use Doctrine\Common\Collections\Collection;

class Programme
{
    private int $id;

    public string $name = '';

    private \DateTime $startDate;

    private \DateTime $endDate;

    private ?User $trainer;

    private Room $room;

    private Collection $customers;

    public bool $isOnline = false;

    public function getId(): int
    {
        return $this->id;
    }

    public function getStartDate(): \DateTime
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTime $startDate): self
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): \DateTime
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTime $endDate): self
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getTrainer(): ?User
    {
        return $this->trainer;
    }

    public function setTrainer(?User $trainer): self
    {
        $this->trainer = $trainer;

        return $this;
    }

    public function getRoom(): Room
    {
        return $this->room;
    }

    public function setRoom(Room $room): self
    {
        $this->room = $room;

        return $this;
    }

    public function getCustomers(): ?Collection
    {
        return $this->customers;
    }

    public function setCustomers(?Collection $customers): self
    {
        $this->customers = $customers;

        return $this;
    }
}
