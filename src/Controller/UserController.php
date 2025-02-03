<?php

namespace App\Controller;

use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Entity\User;

class UserController
{
    public function createUser(UserPasswordHasherInterface $passwordHasher, string $plainPassword): User
    {
        $user = new User();

        $hashedPassword = $passwordHasher->hashPassword(
            $user,
            $plainPassword
        );
        $user->setPassword($hashedPassword);

        // Save the user to the database
        // $entityManager->persist($user);
        // $entityManager->flush();

        return $user;
    }
} 