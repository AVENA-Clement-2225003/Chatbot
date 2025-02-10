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

    #[Route('/conversation/{id}/messages', name: 'conversation_message', methods: ['POST'])]
    public function sendMessage(string $id, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $content = $data['message'] ?? null;

        if (!$content) {
            return $this->json(['error' => 'Missing message content'], Response::HTTP_BAD_REQUEST);
        }

        $chatSession = $this->entityManager->getRepository(ChatSession::class)->find($id);
        if (!$chatSession) {
            return $this->json(['error' => 'Conversation not found'], Response::HTTP_NOT_FOUND);
        }

        // Create user message
        $message = new Message();
        $message->setChatSession($chatSession)
            ->setContent($content)
            ->setIsBot(false);

        $this->entityManager->persist($message);
        
        // Send message to AI API
        $response = $this->generateBotResponse($content);
        
        if (isset($response['error'])) {
            return $this->json(['error' => 'AI API error: ' . $response['error']], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        // Create bot response message
        $botMessage = new Message();
        $botMessage->setChatSession($chatSession)
            ->setContent($response['response'])
            ->setIsBot(true);

        $this->entityManager->persist($botMessage);
        $this->entityManager->flush();

        return $this->json([
            'status' => 'success',
            'response' => $response['response']
        ], Response::HTTP_OK);
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

    private function generateBotResponse(string $message): array
    {
        $client = new \GuzzleHttp\Client();
        
        try {
            $response = $client->post('http://localhost:8000/chat', [
                'json' => [
                    'prompt' => $message
                ]
            ]);

            return json_decode($response->getBody()->getContents(), true);
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }
}
