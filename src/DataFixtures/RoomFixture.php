<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Building;
use App\Entity\Room;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class RoomFixture extends Fixture implements DependentFixtureInterface
{
    public const ROOM_101 = 'room_101';

    public const ROOM_102 = 'room_102';

    public const ROOM_ONLINE = 'room_online';

    public function load(ObjectManager $manager)
    {
        $room1 = new Room();
        $room1->name = 'CAMERA 101';
        $room1->capacity = 30;
        /** @var Building */
        $building = $this->getReference(BuildingFixture::BUILDING_FIXTURE);
        $room1->setBuilding($building);
        $manager->persist($room1);

        $room2 = new Room();
        $room2->name = 'CAMERA 102';
        $room2->capacity = 3;
        /** @var Building */
        $building = $this->getReference(BuildingFixture::BUILDING_FIXTURE);
        $room2->setBuilding($building);
        $manager->persist($room2);

        $room3 = new Room();
        $room3->name = 'http://zoom.com';
        $room3->capacity = 3;
        $manager->persist($room3);

        $manager->flush();

        $this->addReference(self::ROOM_101, $room1);
        $this->addReference(self::ROOM_102, $room2);
        $this->addReference(self::ROOM_ONLINE, $room3);
    }

    public function getDependencies(): array
    {
        return [
            BuildingFixture::class,
        ];
    }
}
