<?php

namespace App\Controller\Dto;

use App\Entity\Programme;
use App\Entity\Room;
use App\Entity\User;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;

class ProgrammeDto
{
    public int $id;

    /**
     * @Assert\NotBlank
     * @Assert\Regex("/^[A-Z]+/")
     */
    public string $name;

    /**
     * @Assert\NotBlank
     */
    public string $description;

    /**
     * @Assert\DateTime
     */
    public \DateTime $startDate;

    /**
     * @Assert\DateTime
     */
    public \DateTime $endDate;

    public ?User $trainer;

    /**
     * @Assert\NotBlank
     */
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
