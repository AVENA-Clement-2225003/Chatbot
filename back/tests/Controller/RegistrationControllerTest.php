<?php

namespace App\Tests\Controller;

use App\Entity\User;
use App\Tests\BaseTestCase;
use Symfony\Component\HttpFoundation\Response;

class RegistrationControllerTest extends BaseTestCase
{
    public function testSuccessfulRegistration(): void
    {
        $email = 'newuser@example.com';
        
        // Make sure the user doesn't exist
        $existingUser = $this->entityManager->getRepository(User::class)->findOneByEmail($email);
        if ($existingUser) {
            $this->entityManager->remove($existingUser);
            $this->entityManager->flush();
        }

        $this->client->request(
            'POST',
            '/api/register',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'email' => $email,
                'password' => 'newpassword123'
            ])
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
        $response = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('message', $response);
        $this->assertArrayHasKey('user', $response);
        $this->assertEquals($email, $response['user']['email']);

        // Verify user exists in database
        $user = $this->entityManager->getRepository(User::class)->findOneByEmail($email);
        $this->assertNotNull($user);
        $this->assertTrue($user->isVerified());
    }

    public function testRegistrationWithExistingEmail(): void
    {
        $this->client->request(
            'POST',
            '/api/register',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'email' => 'test@example.com',
                'password' => 'password123'
            ])
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_CONFLICT);
        $response = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('error', $response);
    }

    public function testRegistrationWithInvalidData(): void
    {
        $this->client->request(
            'POST',
            '/api/register',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'email' => 'invalid-email',
                'password' => '123'
            ])
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
    }
}
