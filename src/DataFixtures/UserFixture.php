<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixture extends Fixture
{
    private UserPasswordHasherInterface $encoder;

    public function __construct(UserPasswordHasherInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setPassword($this->encoder->hashPassword($user, 'Mi5@sua1'));
        $user->email = 'marceldobrica66@gmail.com';
        $user->firstName = 'Marcel';
        $user->lastName = 'Dobrica';
        $user->cnp = '1660713034972';
        $user->addRole('ROLE_ADMIN');

        $manager->persist($user);
        $manager->flush();
    }
}
