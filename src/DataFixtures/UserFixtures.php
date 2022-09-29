<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $user = new User();

        $user->setUsername('Usuario')->setPassword('$argon2id$v=19$m=65536,t=4,p=1$kGL6VSZ/mb7Z5CfXvx9jyg$wmZeMBRFSky6Q76iS5OnQm1MKZ7uA8Uk/sU1KTEFVM4');
        $manager->persist($user);
        $manager->flush();
    }
}
