<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route('/api/login', name: 'app_login', methods: ['POST'])]
    public function login(Request $request): JsonResponse
    {
        $user = $this->getUser();
        
        if (!$user) {
            return new JsonResponse([
                'error' => 'Invalid credentials',
            ], Response::HTTP_UNAUTHORIZED);
        }

        return new JsonResponse([
            'user' => [
                'email' => $user->getUserIdentifier(),
                'roles' => $user->getRoles(),
            ],
        ]);
    }

    #[Route('/api/logout', name: 'app_logout', methods: ['POST'])]
    public function logout(): void
    {
        // This method can be blank - it will be intercepted by the logout key on your firewall
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
