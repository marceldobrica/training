<?php

namespace App\Entity;

use App\Controller\Dto\BuildingDto;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 */
class Building
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private \DateTime $startTime;

    /**
     * @ORM\Column(type="datetime")
     */
    private \DateTime $endTime;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Regex("/^[\p{Lu}].+/")
     */
    public string $address = 'Online';

    public function getId(): int
    {
        return $this->id;
    }

    public function getStartTime(): \DateTime
    {
        return $this->startTime;
    }

    public function setStartTime(\DateTime $startTime): self
    {
        $this->startTime = $startTime;

        return $this;
    }

    public function getEndTime(): \DateTime
    {
        return $this->endTime;
    }

    public function setEndTime(\DateTime $endTime): self
    {
        $this->endTime = $endTime;

        return $this;
    }

    public static function createFromDto(BuildingDto $buildingDto): self
    {
        $building = new self();
        $building->setStartTime($buildingDto->startTime);
        $building->setEndTime($buildingDto->endTime);

        return $building;
    }

    public function __toString()
    {
        return $this->address;
    }
}
