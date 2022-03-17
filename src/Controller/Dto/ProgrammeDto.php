<?php

namespace App\Controller\Dto;

use App\Entity\Programme;
use Symfony\Component\Validator\Constraints as Assert;

class ProgrammeDto
{
    public int $id;

    /**
     * @Assert\NotBlank
     * @Assert\Regex("'/^[\p{Lu}].+/'")
     */
    public string $name;

    public ?string $description;

    /**
     * @Assert\DateTime
     */
    public \DateTime $startDate;

    /**
     * @Assert\DateTime
     */
    public \DateTime $endDate;

    public bool $isOnline;

    public static function createFromProgramme(Programme $programme): self
    {
        $dto = new self();
        $dto->name = $programme->name;
        $dto->description = $programme->description;
        $dto->startDate = $programme->getStartDate();
        $dto->endDate = $programme->getEndDate();
        $dto->isOnline = $programme->isOnline;

        return $dto;
    }
}
