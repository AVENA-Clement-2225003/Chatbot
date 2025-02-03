<?php

namespace App\Controller;

use App\Entity\ChatSession;
use App\Entity\Message;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api', name: 'api_')]
class ChatController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {}

    #[Route('/chat/session', name: 'chat_session', methods: ['POST'])]
    public function createSession(Request $request): JsonResponse
    {
        $chatSession = new ChatSession();
        
        // If user is authenticated, associate the session with them
        $user = $this->getUser();
        if ($user) {
            $chatSession->setUser($user);
        }

        $this->entityManager->persist($chatSession);
        $this->entityManager->flush();

        return $this->json([
            'sessionId' => $chatSession->getSessionId()
        ]);
    }

    #[Route('/chat/message', name: 'chat_message', methods: ['POST'])]
    public function sendMessage(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $sessionId = $data['sessionId'] ?? null;
        $content = $data['message'] ?? null;

        if (!$sessionId || !$content) {
            return $this->json(['error' => 'Missing required fields'], Response::HTTP_BAD_REQUEST);
        }

        $chatSession = $this->entityManager->getRepository(ChatSession::class)->findBySessionId($sessionId);
        if (!$chatSession) {
            return $this->json(['error' => 'Invalid session'], Response::HTTP_NOT_FOUND);
        }

        // Create user message
        $message = new Message();
        $message->setChatSession($chatSession)
            ->setContent($content)
            ->setIsBot(false);

        $this->entityManager->persist($message);
        
        // Create bot response
        $botMessage = new Message();
        $botMessage->setChatSession($chatSession)
            ->setContent($this->generateBotResponse($content))
            ->setIsBot(true);

        $this->entityManager->persist($botMessage);
        $this->entityManager->flush();

        return $this->json([
            'userMessage' => [
                'content' => $message->getContent(),
                'timestamp' => $message->getCreatedAt()->format('c')
            ],
            'botMessage' => [
                'content' => $botMessage->getContent(),
                'timestamp' => $botMessage->getCreatedAt()->format('c')
            ]
        ]);
    }

    #[Route('/chat/history/{sessionId}', name: 'chat_history', methods: ['GET'])]
    public function getHistory(string $sessionId): JsonResponse
    {
        $messages = $this->entityManager->getRepository(Message::class)->findByChatSession($sessionId);

        $formattedMessages = array_map(function(Message $message) {
            return [
                'content' => $message->getContent(),
                'isBot' => $message->isBot(),
                'timestamp' => $message->getCreatedAt()->format('c')
            ];
        }, $messages);

        return $this->json($formattedMessages);
    }

    private function generateBotResponse(string $message): string
    {
        // This is where you would integrate your chatbot logic
        // For now, we'll return a simple response
        return "Thank you for your message: \"$message\". This is a placeholder response.";
    }
}
