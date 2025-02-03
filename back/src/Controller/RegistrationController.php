<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{
    public function __construct(
        private UserPasswordHasherInterface $userPasswordHasher,
        private EntityManagerInterface $entityManager
    ) {
    }

    #[Route('/api/register', name: 'app_register', methods: ['POST'])]
    public function register(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['email']) || !isset($data['password'])) {
            return new JsonResponse(['message' => 'Invalid request: Missing email or password'], Response::HTTP_BAD_REQUEST);
        }

        // Validate email format
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            return new JsonResponse(['message' => 'Invalid email format'], Response::HTTP_BAD_REQUEST);
        }

        // Validate password length
        if (strlen($data['password']) < 8) {
            return new JsonResponse(['message' => 'Invalid password: Must be at least 8 characters'], Response::HTTP_BAD_REQUEST);
        }

        // Check if user exists
        $existingUser = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $data['email']]);
        if ($existingUser) {
            return new JsonResponse(['message' => 'User already exists'], Response::HTTP_CONFLICT);
        }

        $user = new User();
        $user->setEmail($data['email']);
        $user->setPassword(
            $this->userPasswordHasher->hashPassword(
                $user,
                $data['password']
            )
        );

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        // Create a token for the new user
        $token = bin2hex(random_bytes(32));
        $user->setApiToken($token);
        $this->entityManager->flush();

        return new JsonResponse([
            'message' => 'User registered successfully',
            'token' => $token
        ], Response::HTTP_CREATED);
    }
}
