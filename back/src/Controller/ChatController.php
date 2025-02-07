<?php

namespace App\Controller;

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

    #[Route('/messages', name: 'get_messages', methods: ['GET'])]
    public function getMessages(): JsonResponse
    {
        $messages = $this->entityManager->getRepository(Message::class)->findBy([], ['createdAt' => 'ASC']);

        $formattedMessages = array_map(function(Message $message) {
            return [
                'id' => $message->getId(),
                'text' => $message->getContent(),
                'isBot' => $message->isFromAi(),
                'timestamp' => $message->getCreatedAt()->format('c')
            ];
        }, $messages);

        return $this->json($formattedMessages, Response::HTTP_OK, [
            'Access-Control-Allow-Origin' => 'http://localhost:5173',
            'Access-Control-Allow-Methods' => 'GET, POST, OPTIONS',
            'Access-Control-Allow-Headers' => 'Content-Type',
        ]);
    }

    #[Route('/messages', name: 'send_message', methods: ['POST'])]
    public function sendMessage(Request $request): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);
            $content = $data['text'] ?? null;

            if (!$content) {
                return $this->json(
                    ['error' => 'Missing message text'], 
                    Response::HTTP_BAD_REQUEST,
                    $this->getCorsHeaders()
                );
            }

            // Create user message
            $message = new Message();
            $message->setContent($content)
                ->setIsFromAi(false)
                ->setRole('user');

            $this->entityManager->persist($message);
            $this->entityManager->flush();

            // Generate and save bot response
            $botMessage = new Message();
            $botMessage->setContent($this->generateBotResponse($content))
                ->setIsFromAi(true)
                ->setRole('assistant');

            $this->entityManager->persist($botMessage);
            $this->entityManager->flush();

            return $this->json(
                ['status' => 'Message sent successfully'],
                Response::HTTP_OK,
                $this->getCorsHeaders()
            );

        } catch (\Exception $e) {
            return $this->json(
                ['error' => 'Failed to process message'],
                Response::HTTP_INTERNAL_SERVER_ERROR,
                $this->getCorsHeaders()
            );
        }
    }

    #[Route('/messages', name: 'messages_preflight', methods: ['OPTIONS'])]
    public function handlePreflightRequest(): Response
    {
        return new Response(
            null,
            Response::HTTP_NO_CONTENT,
            $this->getCorsHeaders()
        );
    }

    private function generateBotResponse(string $message): string
    {
        // Simple response generation - you can replace this with your actual bot logic
        $responses = [
            "Je comprends votre message.",
            "Pouvez-vous m'en dire plus ?",
            "Je vais vous aider avec ça.",
            "C'est une excellente question.",
            "Laissez-moi réfléchir à cela."
        ];
        
        return $responses[array_rand($responses)];
    }

    private function getCorsHeaders(): array
    {
        return [
            'Access-Control-Allow-Origin' => 'http://localhost:5173',
            'Access-Control-Allow-Methods' => 'GET, POST, OPTIONS',
            'Access-Control-Allow-Headers' => 'Content-Type',
        ];
    }
}
