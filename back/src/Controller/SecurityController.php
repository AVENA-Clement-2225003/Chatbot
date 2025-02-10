<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class SecurityController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserPasswordHasherInterface $passwordHasher,
        private JWTTokenManagerInterface $jwtManager
    ) {
    }

    #[Route('/api/login', name: 'app_login', methods: ['POST'])]
    public function login(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        
        if (!isset($data['email']) || !isset($data['password'])) {
            return new JsonResponse(['message' => 'Missing credentials'], Response::HTTP_BAD_REQUEST);
        }

        $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $data['email']]);

        if (!$user || !$this->passwordHasher->isPasswordValid($user, $data['password'])) {
            return new JsonResponse(['message' => 'Invalid credentials'], Response::HTTP_UNAUTHORIZED);
        }

        $token = $this->jwtManager->create($user);

        return new JsonResponse([
            'token' => $token,
            'message' => 'Login successful'
        ]);
    }

    #[Route('/api/logout', name: 'app_logout', methods: ['POST'])]
    public function logout(): JsonResponse
    {
        return new JsonResponse([
            'message' => 'Logged out successfully'
        ]);
    }
}
