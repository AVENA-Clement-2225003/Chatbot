<?php

namespace App\Tests\Controller;

use App\Entity\Conversation;
use App\Entity\User;
use App\Tests\BaseTestCase;
use Symfony\Component\HttpFoundation\Response;

class ConversationControllerTest extends BaseTestCase
{
    protected $client;
    protected $entityManager;
    protected $testUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = static::createClient();
        $this->entityManager = static::getContainer()->get('doctrine')->getManager();
        
        // Get test user
        $userRepository = static::getContainer()->get('doctrine')->getRepository(User::class);
        $this->testUser = $userRepository->findOneByEmail('test@example.com');

        // Login the test user
        $this->client->request(
            'POST',
            '/api/login',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'email' => 'test@example.com',
                'password' => 'password123'
            ])
        );

        $this->assertResponseIsSuccessful();
    }

    public function testListConversations(): void
    {
        $this->client->request('GET', '/api/conversations');

        $this->assertResponseIsSuccessful();
        $response = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertIsArray($response);
    }

    public function testCreateConversation(): void
    {
        $this->client->request(
            'POST',
            '/api/conversations',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'title' => 'Test Conversation'
            ])
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
        $response = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('id', $response);
        $this->assertEquals('Test Conversation', $response['title']);

        // Verify conversation exists in database
        $conversation = $this->entityManager->getRepository(Conversation::class)->find($response['id']);
        $this->assertNotNull($conversation);
        $this->assertEquals($this->testUser, $conversation->getUser());
    }

    public function testGetMessages(): void
    {
        // Create a test conversation
        $conversation = new Conversation();
        $conversation->setTitle('Test Conversation');
        $conversation->setUser($this->testUser);
        $this->entityManager->persist($conversation);
        $this->entityManager->flush();

        $this->client->request('GET', '/api/conversations/'.$conversation->getId().'/messages');

        $this->assertResponseIsSuccessful();
        $response = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertIsArray($response);
    }

    public function testAddMessage(): void
    {
        // Create a test conversation
        $conversation = new Conversation();
        $conversation->setTitle('Test Conversation');
        $conversation->setUser($this->testUser);
        $this->entityManager->persist($conversation);
        $this->entityManager->flush();

        $this->client->request(
            'POST',
            '/api/conversations/'.$conversation->getId().'/messages',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'content' => 'Test message'
            ])
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
        $response = json_decode($this->client->getResponse()->getContent(), true);
        
        $this->assertArrayHasKey('userMessage', $response);
        $this->assertArrayHasKey('aiMessage', $response);
        $this->assertEquals('Test message', $response['userMessage']['content']);
        $this->assertFalse($response['userMessage']['isFromAi']);
        $this->assertTrue($response['aiMessage']['isFromAi']);
    }

    public function testAccessOtherUserConversation(): void
    {
        // Create another user
        $otherUser = new User();
        $otherUser->setEmail('other@example.com');
        $otherUser->setPassword(
            $this->passwordHasher->hashPassword(
                $otherUser,
                'password123'
            )
        );
        $otherUser->setIsVerified(true);
        $this->entityManager->persist($otherUser);

        // Create a conversation for the other user
        $conversation = new Conversation();
        $conversation->setTitle('Other User Conversation');
        $conversation->setUser($otherUser);
        $this->entityManager->persist($conversation);
        $this->entityManager->flush();

        // Try to access the other user's conversation
        $this->client->request('GET', '/api/conversations/'.$conversation->getId().'/messages');
        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);

        // Try to send a message to the other user's conversation
        $this->client->request(
            'POST',
            '/api/conversations/'.$conversation->getId().'/messages',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'content' => 'Test message'
            ])
        );
        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }
}
