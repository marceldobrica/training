<?php

namespace App\Controller\Dto;

use App\Entity\Programme;
use App\Entity\Room;
use App\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class ProgrammeDto
{
    public int $id;

    public string $name;

    public string $description;

    public \DateTime $startDate;

    public \DateTime $endDate;

    public ?User $trainer;

    public Room $room;

    public Collection $customers;

    public bool $isOnline;

    public static function createFromProgramme(Programme $programme): self
    {
        $dto = new self();
        $dto->name = $programme->name;
        $dto->description = $programme->description;
        $dto->startDate = $programme->getStartDate();
        $dto->endDate = $programme->getEndDate();
        $dto->trainer = $programme->getTrainer();
        $dto->room = $programme->getRoom();
        $dto->customers = $programme->getCustomers();
        $dto->isOnline = $programme->isOnline;

        return $dto;
    }
}
