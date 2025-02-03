<?php

namespace App\Tests\Controller;

use App\Entity\Conversation;
use App\Entity\User;
use App\Tests\BaseTestCase;
use Symfony\Component\HttpFoundation\Response;

class ConversationControllerTest extends BaseTestCase
{
    private string $token;

    protected function setUp(): void
    {
        parent::setUp();

        // Login to get the token
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

        $response = json_decode($this->client->getResponse()->getContent(), true);
        $this->token = $response['token'];
    }

    public function testListConversations(): void
    {
        $this->client->request(
            'GET',
            '/api/conversations',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => 'Bearer ' . $this->token
            ]
        );

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
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => 'Bearer ' . $this->token
            ],
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

        $this->client->request(
            'GET',
            '/api/conversations/'.$conversation->getId().'/messages',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => 'Bearer ' . $this->token
            ]
        );

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
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => 'Bearer ' . $this->token
            ],
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
        $this->client->request(
            'GET',
            '/api/conversations/'.$conversation->getId().'/messages',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => 'Bearer ' . $this->token
            ]
        );
        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);

        // Try to send a message to the other user's conversation
        $this->client->request(
            'POST',
            '/api/conversations/'.$conversation->getId().'/messages',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => 'Bearer ' . $this->token
            ],
            json_encode([
                'content' => 'Test message'
            ])
        );
        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }
}
