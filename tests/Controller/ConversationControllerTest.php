<?php

namespace App\Tests\Controller;

use App\Entity\Conversation;
use App\Entity\Message;
use App\Entity\User;
use App\Tests\BaseTestCase;
use Symfony\Component\HttpFoundation\Response;

class ConversationControllerTest extends BaseTestCase
{
    private Conversation $conversation;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a conversation for the test user
        $this->conversation = new Conversation();
        $this->conversation->setUser($this->testUser);
        $this->conversation->setTitle('Test Conversation');
        $this->entityManager->persist($this->conversation);

        // Create a message in the conversation
        $message = new Message();
        $message->setContent('Test message');
        $message->setRole('user');
        $message->setConversation($this->conversation);
        $this->entityManager->persist($message);

        $this->entityManager->flush();
    }

    public function testListConversations(): void
    {
        $this->client->request('GET', '/api/conversations', [], [], [
            'HTTP_AUTHORIZATION' => 'Bearer ' . $this->getToken(),
        ]);

        $this->assertResponseIsSuccessful();
        $response = json_decode($this->client->getResponse()->getContent(), true);
        
        $this->assertIsArray($response);
        $this->assertCount(1, $response);
        $this->assertEquals($this->conversation->getId(), $response[0]['id']);
    }

    public function testCreateConversation(): void
    {
        $this->client->request('POST', '/api/conversations', [], [], [
            'HTTP_AUTHORIZATION' => 'Bearer ' . $this->getToken(),
            'CONTENT_TYPE' => 'application/json',
        ], json_encode([
            'message' => 'Hello, this is a test message',
        ]));

        $this->assertResponseIsSuccessful();
        $response = json_decode($this->client->getResponse()->getContent(), true);
        
        $this->assertArrayHasKey('id', $response);
        $this->assertArrayHasKey('messages', $response);
        $this->assertCount(1, $response['messages']);
    }

    public function testGetMessages(): void
    {
        $this->client->request('GET', sprintf('/api/conversations/%d/messages', $this->conversation->getId()), [], [], [
            'HTTP_AUTHORIZATION' => 'Bearer ' . $this->getToken(),
        ]);

        $this->assertResponseIsSuccessful();
        $response = json_decode($this->client->getResponse()->getContent(), true);
        
        $this->assertIsArray($response);
        $this->assertCount(1, $response);
        $this->assertEquals('Test message', $response[0]['content']);
    }

    public function testAddMessage(): void
    {
        $this->client->request('POST', sprintf('/api/conversations/%d/messages', $this->conversation->getId()), [], [], [
            'HTTP_AUTHORIZATION' => 'Bearer ' . $this->getToken(),
            'CONTENT_TYPE' => 'application/json',
        ], json_encode([
            'content' => 'New test message',
            'role' => 'user',
        ]));

        $this->assertResponseIsSuccessful();
        $response = json_decode($this->client->getResponse()->getContent(), true);
        
        $this->assertArrayHasKey('id', $response);
        $this->assertEquals('New test message', $response['content']);
        $this->assertEquals('user', $response['role']);
    }

    public function testAccessOtherUserConversation(): void
    {
        // Create another user
        $otherUser = new User();
        $otherUser->setEmail('other@example.com');
        $otherUser->setPassword($this->passwordHasher->hashPassword($otherUser, 'password123'));
        $this->entityManager->persist($otherUser);

        // Create a conversation for the other user
        $otherConversation = new Conversation();
        $otherConversation->setUser($otherUser);
        $otherConversation->setTitle('Other User Conversation');
        $this->entityManager->persist($otherConversation);
        $this->entityManager->flush();

        $this->client->request('GET', sprintf('/api/conversations/%d/messages', $otherConversation->getId()), [], [], [
            'HTTP_AUTHORIZATION' => 'Bearer ' . $this->getToken(),
        ]);

        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }

    private function getToken(): string
    {
        $this->client->request('POST', '/api/login', [], [], [
            'CONTENT_TYPE' => 'application/json'
        ], json_encode([
            'email' => 'test@example.com',
            'password' => 'password123'
        ]));

        $response = json_decode($this->client->getResponse()->getContent(), true);
        return $response['token'];
    }
}
