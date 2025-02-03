<?php

namespace App\DataFixtures;

use App\Entity\Conversation;
use App\Entity\Message;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(
        private UserPasswordHasherInterface $passwordHasher,
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        // Create test user
        $user = new User();
        $user->setEmail('test@example.com');
        $user->setPassword(
            $this->passwordHasher->hashPassword(
                $user,
                'password123'
            )
        );
        $user->setIsVerified(true);
        $manager->persist($user);

        // Create some test conversations
        for ($i = 1; $i <= 3; $i++) {
            $conversation = new Conversation();
            $conversation->setTitle("Test Conversation {$i}");
            $conversation->setUser($user);
            $manager->persist($conversation);

            // Add some messages to each conversation
            $userMessage = new Message();
            $userMessage->setContent("User question {$i}");
            $userMessage->setIsFromAi(false);
            $userMessage->setConversation($conversation);
            $manager->persist($userMessage);

            $aiMessage = new Message();
            $aiMessage->setContent("AI response to question {$i}");
            $aiMessage->setIsFromAi(true);
            $aiMessage->setConversation($conversation);
            $manager->persist($aiMessage);
        }

        $manager->flush();
    }
}
