<?php

declare(strict_types=1);

namespace App\Controller\Dto;

use App\Entity\Building;
use App\Entity\Room;

class RoomDto
{
    public int $id;

    public string $name = '';

    public int $capacity = 0;

    public Building $building;

    public static function createFromRoom(Room $room): self
    {
        $dto = new self();
        $dto->id = $room->getId();
        $dto->name = $room->name;
        $dto->building = $room->getBuilding();

        return $dto;
    }
}
