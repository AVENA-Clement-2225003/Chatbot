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

/**
 * Chat Controller
 * 
 * This controller handles all chat-related operations including:
 * - Retrieving message history
 * - Sending new messages
 * - Interacting with the AI assistant
 * - Managing chat sessions
 *
 * All endpoints require user authentication.
 * 
 * @author AVENA DELMAS KHADRAOUI NGUYEN
 */
#[Route('/api', name: 'api_')]
class ChatController extends AbstractController
{
    /**
     * ChatController constructor.
     * 
     * @param EntityManagerInterface $entityManager For database operations
     * @param HttpClientInterface $httpClient For making HTTP requests to AI service
     */
    public function __construct(
        private EntityManagerInterface $entityManager,
        private HttpClientInterface $httpClient
    ) {}

    /**
     * Retrieves all messages for the authenticated user
     * 
     * @return JsonResponse List of messages or error response
     * 
     * @throws AccessDeniedException If user is not authenticated
     */
    #[Route('/messages', name: 'get_messages', methods: ['GET'])]
    public function getMessages(): JsonResponse
    {
        try {
            // Check if user is authenticated
            if (!$this->isGranted('IS_AUTHENTICATED_FULLY')) {
                return $this->json(
                    ['error' => 'You must be logged in to access the chat.'],
                    Response::HTTP_UNAUTHORIZED,
                    $this->getCorsHeaders()
                );
            }

            $user = $this->getUser();
            error_log("[ChatController] Fetching messages for user: " . $user->getUserIdentifier());

            // Filter messages by current user
            $messages = $this->entityManager->getRepository(Message::class)->findBy(
                ['user' => $user],
                ['createdAt' => 'ASC']
            );

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

    /**
     * Sends a new message from the authenticated user
     * 
     * @param Request $request The incoming request with message data
     * 
     * @return JsonResponse Success response with bot response or error
     * 
     * @throws AccessDeniedException If user is not authenticated
     */
    #[Route('/messages', name: 'send_message', methods: ['POST'])]
    public function sendMessage(Request $request): JsonResponse
    {
        try {
            // Check if user is authenticated
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
                ->setRole('user')
                ->setUser($this->getUser());

            $this->entityManager->persist($message);
            $this->entityManager->flush();
            error_log("[ChatController] Saved user message with ID: " . $message->getId());

            // Generate and save bot response
            error_log("[ChatController] Generating bot response...");
            $botResponse = $this->generateBotResponse($content);
            $botMessage = new Message();
            $botMessage->setContent($botResponse)
                ->setIsFromAi(true)
                ->setRole('assistant')
                ->setUser($this->getUser());

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

    /**
     * Handles preflight requests for CORS
     * 
     * @return Response Empty response with CORS headers
     */
    #[Route('/messages', name: 'messages_preflight', methods: ['OPTIONS'])]
    public function handlePreflightRequest(): Response
    {
        return new Response(
            null,
            Response::HTTP_NO_CONTENT,
            $this->getCorsHeaders()
        );
    }

    /**
     * Generates a bot response to a given user message
     * 
     * @param string $message The user's message
     * 
     * @return string The bot's response
     * 
     * @throws \Exception If AI API request fails
     */
    private function generateBotResponse(string $message): string
    {
        try {
            error_log("[ChatController] Calling AI API with message: " . $message);

            // Call the FastAPI endpoint
            $response = $this->httpClient->request('POST', 'http://127.0.0.1:8001/api/messages', [
                'json' => [
                    'text' => $message,
                    'isBot' => false,
                    'timestamp' => time()
                ],
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

            if (!$data || !isset($data['botResponse']) || !isset($data['botResponse']['text'])) {
                error_log("[ChatController] Invalid response format: " . $content);
                throw new \Exception("Invalid response from AI API");
            }

            error_log("[ChatController] AI response received: " . $data['botResponse']['text']);
            return $data['botResponse']['text'];

        } catch (\Exception $e) {
            error_log("[ChatController] Detailed error: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return "Désolé, je rencontre des difficultés techniques. Veuillez réessayer plus tard. Error: " . $e->getMessage();
        }
    }

    /**
     * Returns CORS headers for API responses
     * 
     * @return array CORS headers
     */
    private function getCorsHeaders(): array
    {
        return [
            'Access-Control-Allow-Origin' => 'http://localhost:5173',
            'Access-Control-Allow-Credentials' => 'true',
            'Access-Control-Allow-Methods' => 'GET, POST, PUT, DELETE, OPTIONS',
            'Access-Control-Allow-Headers' => 'Content-Type, Authorization, Accept',
            'Access-Control-Max-Age' => '3600'
        ];
    }
}
