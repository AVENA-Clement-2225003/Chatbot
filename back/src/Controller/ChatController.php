<?php

namespace App\Controller;

use App\Entity\Message;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[Route('/api', name: 'api_')]
class ChatController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private HttpClientInterface $httpClient
    ) {}

    #[Route('/messages', name: 'get_messages', methods: ['GET'])]
    public function getMessages(): JsonResponse
    {
        try {
            if (!$this->isGranted('IS_AUTHENTICATED_FULLY')) {
                return $this->json(
                    ['error' => 'You must be logged in to access the chat.'],
                    Response::HTTP_UNAUTHORIZED,
                    $this->getCorsHeaders()
                );
            }

            error_log("[ChatController] Fetching all messages");
            $messages = $this->entityManager->getRepository(Message::class)->findBy([], ['createdAt' => 'ASC']);

            $formattedMessages = array_map(function(Message $message) {
                return [
                    'id' => $message->getId(),
                    'text' => $message->getContent(),
                    'isBot' => $message->isFromAi(),
                    'timestamp' => $message->getCreatedAt()->format('c')
                ];
            }, $messages);

            error_log("[ChatController] Returning " . count($formattedMessages) . " messages");
            return $this->json($formattedMessages, Response::HTTP_OK, $this->getCorsHeaders());
        } catch (\Exception $e) {
            error_log("[ChatController] Error fetching messages: " . $e->getMessage());
            return $this->json(
                ['error' => 'Failed to fetch messages'],
                Response::HTTP_INTERNAL_SERVER_ERROR,
                $this->getCorsHeaders()
            );
        }
    }

    #[Route('/messages', name: 'send_message', methods: ['POST'])]
    public function sendMessage(Request $request): JsonResponse
    {
        try {
            if (!$this->isGranted('IS_AUTHENTICATED_FULLY')) {
                return $this->json(
                    ['error' => 'You must be logged in to send messages.'],
                    Response::HTTP_UNAUTHORIZED,
                    $this->getCorsHeaders()
                );
            }

            error_log("[ChatController] Received new message request");
            $data = json_decode($request->getContent(), true);
            $content = $data['text'] ?? null;

            if (!$content) {
                error_log("[ChatController] Error: Missing message text");
                return $this->json(
                    ['error' => 'Missing message text'], 
                    Response::HTTP_BAD_REQUEST,
                    $this->getCorsHeaders()
                );
            }

            error_log("[ChatController] Processing user message: " . $content);
            
            // Create user message
            $message = new Message();
            $message->setContent($content)
                ->setIsFromAi(false)
                ->setRole('user');

            $this->entityManager->persist($message);
            $this->entityManager->flush();
            error_log("[ChatController] Saved user message with ID: " . $message->getId());

            // Generate and save bot response
            error_log("[ChatController] Generating bot response...");
            $botResponse = $this->generateBotResponse($content);
            $botMessage = new Message();
            $botMessage->setContent($botResponse)
                ->setIsFromAi(true)
                ->setRole('assistant');

            $this->entityManager->persist($botMessage);
            $this->entityManager->flush();
            error_log("[ChatController] Saved bot response with ID: " . $botMessage->getId());

            $response = [
                'status' => 'Message sent successfully',
                'botResponse' => [
                    'id' => $botMessage->getId(),
                    'text' => $botMessage->getContent(),
                    'isBot' => true,
                    'timestamp' => $botMessage->getCreatedAt()->format('c')
                ]
            ];
            error_log("[ChatController] Sending response: " . json_encode($response));

            return $this->json(
                $response,
                Response::HTTP_OK,
                $this->getCorsHeaders()
            );

        } catch (\Exception $e) {
            error_log("[ChatController] Error processing message: " . $e->getMessage());
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
        try {
            error_log("[ChatController] Calling AI API with message: " . $message);
            
            // Call the FastAPI endpoint
            $response = $this->httpClient->request('POST', 'http://127.0.0.1:8001/chat', [
                'json' => ['prompt' => $message],
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json'
                ],
                'timeout' => 30,
                'verify_peer' => false,
                'verify_host' => false
            ]);

            $content = $response->getContent();
            error_log("[ChatController] Raw response: " . $content);
            
            $data = json_decode($content, true);
            error_log("[ChatController] Decoded response: " . json_encode($data));
            
            if (!$data || !isset($data['response'])) {
                error_log("[ChatController] Invalid response format: " . $content);
                throw new \Exception("Invalid response from AI API");
            }

            error_log("[ChatController] AI response received: " . $data['response']);
            return $data['response'];

        } catch (\Exception $e) {
            error_log("[ChatController] Detailed error: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return "Désolé, je rencontre des difficultés techniques. Veuillez réessayer plus tard. Error: " . $e->getMessage();
        }
    }

    private function getCorsHeaders(): array
    {
        return [
            'Access-Control-Allow-Origin' => 'http://localhost:5173',
            'Access-Control-Allow-Credentials' => 'true',
            'Access-Control-Allow-Methods' => 'GET, POST, PUT, DELETE, OPTIONS',
            'Access-Control-Allow-Headers' => 'Content-Type, Authorization, Accept',
        ];
    }
}
