<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Programme;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ProgrammeFixture extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $trainer = $this->getReference(UserFixture::USER_TRAINER);
        $user1 = $this->getReference(UserFixture::USER_1);
        $user2 = $this->getReference(UserFixture::USER_2);
        $admin = $this->getReference(UserFixture::USER_ADMIN);

        $room101 = $this->getReference(RoomFixture::ROOM_101);
        $room102 = $this->getReference(RoomFixture::ROOM_102);
        $roomOnline = $this->getReference(RoomFixture::ROOM_ONLINE);

        $startDate = new \DateTime('+1 day');
        $startDate->setTime(9, 0, 0);
        $endDate = new \DateTime('+1 day');
        $endDate->setTime(11, 0, 0);

        $startDate2 = new \DateTime('+1 day');
        $startDate2->setTime(12, 0, 0);
        $endDate2 = new \DateTime('+1 day');
        $endDate2->setTime(14, 0, 0);

        $programme1 = new Programme();
        $programme1->name = "Yoga incepatori";
        $programme1->isOnline = false;
        $programme1->maxParticipants = 20;
        $programme1->setStartDate($startDate2);
        $programme1->setEndDate($endDate2);
        $programme1->setRoom($room101);
        $programme1->setTrainer($trainer);
        $programme1->addCustomer($user1);
        $programme1->addCustomer($user2);
        $programme1->addCustomer($admin);
        $manager->persist($programme1);

        $programme2 = new Programme();
        $programme2->name = "Yoga avansati online";
        $programme2->isOnline = true;
        $programme2->maxParticipants = 100;
        $programme2->setStartDate($startDate);
        $programme2->setEndDate($endDate);
        $programme2->setRoom($roomOnline);
        $programme2->setTrainer($admin);
        $programme2->addCustomer($user1);
        $programme2->addCustomer($user2);
        $manager->persist($programme2);

        $programme3 = new Programme();
        $programme3->name = "Street Dance";
        $programme3->isOnline = false;
        $programme3->maxParticipants = 20;
        $programme3->setStartDate($startDate);
        $programme3->setEndDate($endDate);
        $programme3->setRoom($room102);
        $programme3->setTrainer($trainer);
        $programme3->addCustomer($user1);
        $programme3->addCustomer($user2);
        $manager->persist($programme3);

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixture::class,
            RoomFixture::class
        ];
    }
}
