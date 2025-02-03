<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(
        private UserPasswordHasherInterface $passwordHasher
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 10; $i++) {
            $user = new User();
            $user->setEmail('test' . $i . '@gmail.com');
            
            $hashedPassword = $this->passwordHasher->hashPassword(
                $user,
                'test'
            );
            $user->setPassword($hashedPassword);
            $user->setRoles(1);
            
            $manager->persist($user);
        }
        $manager->flush();
    }
}

