<?php

declare(strict_types=1);

namespace App\Controller\Dto;

use App\Entity\Building;
use Symfony\Component\Validator\Constraints as Assert;

class BuildingDto
{
    public int $id;

    public \DateTime $startTime;

    public \DateTime $endTime;

    /**
     * @Assert\Regex("/^[\p{Lu}].+/", message="The value should start with an uppercase letter.")
     */
    public string $address = '';

    public static function createFromBuilding(Building $building): self
    {
        $dto = new self();
        $dto->id = $building->getId();
        $dto->startTime = $building->getStartTime();
        $dto->endTime = $building->getEndTime();
        $dto->address = $building->address;

        return $dto;
    }
}
