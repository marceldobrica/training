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
     * @Assert\Regex("/^[\p{Lu}].+/", message="The value should start with an uppercase letter.")
     */
    public string $name;

    public string $description = '';

    public \DateTime $startDate;

    public \DateTime $endDate;

    public ?User $trainer;

    public ?Room $room;

    public Collection $customers;

    public bool $isOnline;

    public int $maxParticipants = 0;

    public static function createFromProgramme(Programme $programme): self
    {
        $dto = new self();
        $dto->name = $programme->name;
        $dto->description = $programme->description;
        $dto->startDate = $programme->getStartDate();
        $dto->endDate = $programme->getEndDate();
        $dto->trainer = $programme->getTrainer();
        $dto->room = $programme->getRoom();
        $dto->isOnline = $programme->isOnline;
        $dto->maxParticipants = $programme->maxParticipants;

        return $dto;
    }
}
