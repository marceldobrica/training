<?php

declare(strict_types=1);

namespace App\Controller\Dto;

use App\Entity\Building;

class BuildingDto
{
    public int $id;

    /**
     * @Assert\Type("\DateTimeInterface")
     */
    public \DateTime $startTime;

    /**
     * @Assert\Type("\DateTimeInterface")
     */
    public \DateTime $endTime;

    public static function createFromBuilding(Building $building): self
    {
        $dto = new self();
        $dto->id = $building->getId();
        $dto->startTime = $building->getStartTime();
        $dto->endTime = $building->getEndTime();

        return $dto;
    }
}
