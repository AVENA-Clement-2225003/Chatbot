<?php

namespace App\Tests;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class BaseTestCase extends WebTestCase
{
    protected KernelBrowser $client;
    protected ?EntityManagerInterface $entityManager = null;
    protected UserPasswordHasherInterface $passwordHasher;
    protected User $testUser;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->client = static::createClient();
        $container = static::getContainer();

        $this->entityManager = $container->get(EntityManagerInterface::class);
        $this->passwordHasher = $container->get(UserPasswordHasherInterface::class);

        // Reset database
        $schemaTool = new SchemaTool($this->entityManager);
        $metadata = $this->entityManager->getMetadataFactory()->getAllMetadata();
        
        $schemaTool->dropSchema($metadata);
        $schemaTool->createSchema($metadata);

        // Create test user
        $this->testUser = new User();
        $this->testUser->setEmail('test@example.com');
        $this->testUser->setPassword(
            $this->passwordHasher->hashPassword(
                $this->testUser,
                'password123'
            )
        );

        $this->entityManager->persist($this->testUser);
        $this->entityManager->flush();
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        if ($this->entityManager) {
            $this->entityManager->close();
            $this->entityManager = null;
        }

        self::ensureKernelShutdown();
    }
}
