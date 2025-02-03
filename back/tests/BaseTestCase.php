<?php

namespace App\Tests;

use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Entity\User;

class BaseTestCase extends WebTestCase
{
    protected KernelBrowser $client;
    protected $entityManager;
    protected $testUser;
    protected $passwordHasher;

    public function setUp(): void
    {
        $this->client = static::createClient();
        $container = static::getContainer();

        $this->entityManager = $container->get('doctrine')->getManager();
        $this->passwordHasher = $container->get(UserPasswordHasherInterface::class);

        // Create database schema
        $schemaTool = new SchemaTool($this->entityManager);
        $metadata = $this->entityManager->getMetadataFactory()->getAllMetadata();

        try {
            $schemaTool->dropSchema($metadata);
            $schemaTool->createSchema($metadata);
        } catch (\Exception $e) {
            throw new \RuntimeException($e->getMessage());
        }

        // Create test user
        $this->testUser = new User();
        $this->testUser->setEmail('test@example.com');
        $this->testUser->setPassword(
            $this->passwordHasher->hashPassword(
                $this->testUser,
                'password123'
            )
        );
        $this->testUser->setIsVerified(true);

        $this->entityManager->persist($this->testUser);
        $this->entityManager->flush();
    }

    public function tearDown(): void
    {
        parent::tearDown();
        
        if ($this->entityManager) {
            $this->entityManager->close();
            $this->entityManager = null;
        }

        self::ensureKernelShutdown();
    }
}
