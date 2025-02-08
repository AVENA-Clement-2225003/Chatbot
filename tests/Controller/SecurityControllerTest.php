<?php

namespace App\Tests\Controller;

use App\Tests\BaseTestCase;
use Symfony\Component\HttpFoundation\Response;

class SecurityControllerTest extends BaseTestCase
{
    public function testSuccessfulLogin(): void
    {
        $this->client->request('POST', '/api/login', [], [], [
            'CONTENT_TYPE' => 'application/json'
        ], json_encode([
            'email' => 'test@example.com',
            'password' => 'password123'
        ]));

        $this->assertResponseIsSuccessful();
        $response = json_decode($this->client->getResponse()->getContent(), true);

        $this->assertArrayHasKey('token', $response);
    }

    public function testLoginWithInvalidCredentials(): void
    {
        $this->client->request('POST', '/api/login', [], [], [
            'CONTENT_TYPE' => 'application/json'
        ], json_encode([
            'email' => 'test@example.com',
            'password' => 'wrongpassword'
        ]));

        $this->assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
        $response = json_decode($this->client->getResponse()->getContent(), true);
        
        $this->assertArrayHasKey('message', $response);
        $this->assertEquals('Invalid credentials.', $response['message']);
    }

    public function testLogout(): void
    {
        // First login to get a token
        $this->client->request('POST', '/api/login', [], [], [
            'CONTENT_TYPE' => 'application/json'
        ], json_encode([
            'email' => 'test@example.com',
            'password' => 'password123'
        ]));

        $loginResponse = json_decode($this->client->getResponse()->getContent(), true);
        $token = $loginResponse['token'];

        // Then try to logout
        $this->client->request('POST', '/api/logout', [], [], [
            'HTTP_AUTHORIZATION' => 'Bearer ' . $token
        ]);

        $this->assertResponseIsSuccessful();
        $response = json_decode($this->client->getResponse()->getContent(), true);
        
        $this->assertArrayHasKey('message', $response);
        $this->assertEquals('Logged out successfully', $response['message']);
    }
}
