<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixture extends Fixture
{
    public const USER_ADMIN = 'user_admin';

    public const USER_TRAINER = 'user_trainer';

    public const USER_1 = 'user1';

    public const USER_2 = 'user2';

    private UserPasswordHasherInterface $encoder;

    public function __construct(UserPasswordHasherInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager): void
    {
        $userAdmin = new User();
        $userAdmin->setPassword($this->encoder->hashPassword($userAdmin, 'Mi5@sua1'));
        $userAdmin->email = 'marceldobrica66@gmail.com';
        $userAdmin->firstName = 'Marcel';
        $userAdmin->lastName = 'Dobrica';
        $userAdmin->cnp = '1660713034972';
        $userAdmin->addRole('ROLE_ADMIN');
        $manager->persist($userAdmin);

        $userTrainer = new User();
        $userTrainer->setPassword($this->encoder->hashPassword($userTrainer, 'Mi5@sua1'));
        $userTrainer->email = 'trainer@helpmanager.ro';
        $userTrainer->firstName = 'Trainer';
        $userTrainer->lastName = 'User';
        $userTrainer->cnp = '1660713034972';
        $userTrainer->addRole('ROLE_TRAINER');
        $manager->persist($userTrainer);

        $user1 = new User();
        $user1->setPassword($this->encoder->hashPassword($user1, 'Mi5@sua1'));
        $user1->email = 'user1@helpmanager.ro';
        $user1->firstName = 'User1';
        $user1->lastName = 'User1';
        $user1->cnp = '1660713034972';
        $user1->addRole('ROLE_USER');
        $manager->persist($user1);

        $user2 = new User();
        $user2->setPassword($this->encoder->hashPassword($user2, 'Mi5@sua1'));
        $user2->email = 'user2@helpmanager.ro';
        $user2->firstName = 'User2';
        $user2->lastName = 'User2';
        $user2->cnp = '1660713034972';
        $user2->addRole('ROLE_USER');
        $manager->persist($user2);

        $manager->flush();

        $this->addReference(self::USER_ADMIN, $userAdmin);
        $this->addReference(self::USER_TRAINER, $userTrainer);
        $this->addReference(self::USER_1, $user1);
        $this->addReference(self::USER_2, $user2);
    }
}
