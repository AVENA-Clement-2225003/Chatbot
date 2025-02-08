<?php

namespace App\Tests\Controller;

use App\Tests\BaseTestCase;
use Symfony\Component\HttpFoundation\Response;

class RegistrationControllerTest extends BaseTestCase
{
    public function testSuccessfulRegistration(): void
    {
        $email = 'newuser@example.com';
        $password = 'newpassword123';

        $this->client->request('POST', '/api/register', [], [], [
            'CONTENT_TYPE' => 'application/json'
        ], json_encode([
            'email' => $email,
            'password' => $password
        ]));

        $this->assertResponseIsSuccessful();
        $response = json_decode($this->client->getResponse()->getContent(), true);

        $this->assertArrayHasKey('token', $response);
        $this->assertArrayHasKey('message', $response);
        $this->assertEquals('User registered successfully', $response['message']);

        // Try to login with the new credentials
        $this->client->request('POST', '/api/login', [], [], [
            'CONTENT_TYPE' => 'application/json'
        ], json_encode([
            'email' => $email,
            'password' => $password
        ]));

        $this->assertResponseIsSuccessful();
    }

    public function testRegistrationWithExistingEmail(): void
    {
        $this->client->request('POST', '/api/register', [], [], [
            'CONTENT_TYPE' => 'application/json'
        ], json_encode([
            'email' => 'test@example.com', // This email is already used in BaseTestCase
            'password' => 'somepassword123'
        ]));

        $this->assertResponseStatusCodeSame(Response::HTTP_CONFLICT);
        $response = json_decode($this->client->getResponse()->getContent(), true);
        
        $this->assertArrayHasKey('message', $response);
        $this->assertEquals('User already exists', $response['message']);
    }

    public function testRegistrationWithInvalidData(): void
    {
        $this->client->request('POST', '/api/register', [], [], [
            'CONTENT_TYPE' => 'application/json'
        ], json_encode([
            'email' => 'invalid-email',
            'password' => 'short'
        ]));

        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
        $response = json_decode($this->client->getResponse()->getContent(), true);
        
        $this->assertArrayHasKey('message', $response);
        $this->assertStringContainsString('Invalid', $response['message']);
    }
}
