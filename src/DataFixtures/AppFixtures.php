<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;
use App\Entity\Roles;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(
        private UserPasswordHasherInterface $passwordHasher
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        //set roles
        $roles = ['user', 'admin', 'super_admin'];
        
        $roleEntities = [];
        foreach ($roles as $roleName) {
            $role = new Roles();
            $role->setName($roleName);
            $manager->persist($role);
            $roleEntities[$roleName] = $role;
        }
        
        for ($i = 0; $i < 10; $i++) {
            $user = new User();
            $user->setEmail('test' . $i . '@gmail.com');
            
            $hashedPassword = $this->passwordHasher->hashPassword(
                $user,
                'test'
            );
            $user->setPassword($hashedPassword);
            
            // Assuming you want to assign the 'user' role to all users
            $user->setRole($roleEntities['user']);
            
            $manager->persist($user);
        }
        $manager->flush();
    }

   
}

