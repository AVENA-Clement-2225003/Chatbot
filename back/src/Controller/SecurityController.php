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

/**
 * Security Controller
 * 
 * Handles user authentication and security-related operations.
 * Provides endpoints for user login and logout functionality
 * using JWT token-based authentication.
 *
 * @author AVENA DELMAS KHADRAOUI NGUYEN
 */
class SecurityController extends AbstractController
{
    /**
     * SecurityController constructor
     * 
     * @param EntityManagerInterface $entityManager For database operations
     * @param UserPasswordHasherInterface $passwordHasher For password validation
     * @param JWTTokenManagerInterface $jwtManager For JWT token operations
     */
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserPasswordHasherInterface $passwordHasher,
        private JWTTokenManagerInterface $jwtManager
    ) {
    }

    /**
     * Authenticates a user and provides a JWT token
     * 
     * Validates user credentials and generates a JWT token for
     * authenticated access to protected endpoints.
     *
     * @param Request $request The HTTP request containing login credentials
     * 
     * @return JsonResponse Success response with JWT token or error message
     */
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

    /**
     * Handles user logout
     * 
     * Note: With JWT, actual logout is handled client-side by removing the token.
     * This endpoint provides a standardized way to handle logout requests.
     *
     * @return JsonResponse Success response indicating logout
     */
    #[Route('/api/logout', name: 'app_logout', methods: ['POST'])]
    public function logout(): JsonResponse
    {
        return new JsonResponse([
            'message' => 'Logged out successfully'
        ]);
    }
}
