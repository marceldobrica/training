<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Building;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class BuildingFixture extends Fixture
{
    public const BUILDING_FIXTURE = 'building';

    public function load(ObjectManager $manager)
    {
        $building = new Building();
        $startTime = new \DateTime();
        $startTime->setTime(8, 0, 0);
        $endTime = new \DateTime();
        $endTime->setTime(22, 0, 0);
        $building->setStartTime($startTime);
        $building->setEndTime($endTime);
        $building->address = 'Calea Zarandului nr.640, Cluj';

        $manager->persist($building);
        $manager->flush();

        $this->addReference(self::BUILDING_FIXTURE, $building);
    }
}
