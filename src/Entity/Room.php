<?php

namespace App\Entity;

use App\Controller\Dto\RoomDto;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity()
 */
class Room
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups ("api:programme:all")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups ("api:programme:all")
     */
    public string $name = '';

    /**
     * @ORM\Column(type="integer")
     */
    public int $capacity = 0;

    /**
     * @ORM\ManyToOne(targetEntity="Building")
     * @ORM\JoinColumn(name="building_id", referencedColumnName="id")
     */
    private ?Building $building;

    public function getId(): int
    {
        return $this->id;
    }

    public function getBuilding(): ?Building
    {
        return $this->building;
    }

    public function setBuilding(?Building $building): self
    {
        $this->building = $building;

        return $this;
    }

    public static function createFromDto(RoomDto $roomDto): self
    {
        $room = new self();
        $room->name = $roomDto->name;
        $room->capacity = $roomDto->capacity;
        $room->setBuilding($roomDto->building);

        return $room;
    }

    public function __toString(): string
    {
        return $this->name;
    }
}
