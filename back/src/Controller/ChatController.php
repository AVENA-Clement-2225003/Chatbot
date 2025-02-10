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

#[Route('/api', name: 'api_')]
class ChatController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {}

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
        error_log("[ChatController] Generating response for message: " . $message);
        $message = strtolower($message);
        
        // Check for common greetings
        if (preg_match('/(bonjour|salut|hey|hello|hi)/i', $message)) {
            error_log("[ChatController] Detected greeting pattern");
            $greetings = [
                "Bonjour! Comment puis-je vous aider aujourd'hui?",
                "Salut! Je suis là pour répondre à vos questions.",
                "Hello! Que puis-je faire pour vous?"
            ];
            $response = $greetings[array_rand($greetings)];
            error_log("[ChatController] Selected greeting response: " . $response);
            return $response;
        }
        
        // Check for questions
        if (preg_match('/\?$/', $message) || preg_match('/(quoi|comment|pourquoi|quand|où)/i', $message)) {
            error_log("[ChatController] Detected question pattern");
            $questionResponses = [
                "C'est une excellente question. D'après mon analyse, ",
                "Je vais essayer de répondre au mieux. ",
                "Voici ce que je peux vous dire à ce sujet: "
            ];
            $response = $questionResponses[array_rand($questionResponses)] . 
                       "Je simule actuellement une réponse d'IA, mais bientôt je serai connecté à un vrai modèle d'IA.";
            error_log("[ChatController] Selected question response: " . $response);
            return $response;
        }
        
        // Default responses for other cases
        error_log("[ChatController] Using default response pattern");
        $defaultResponses = [
            "Je comprends votre message. Pouvez-vous me donner plus de détails?",
            "Merci pour votre message. Je suis en train de traiter votre demande.",
            "Je suis là pour vous aider. Pourriez-vous préciser votre demande?",
            "Je vais faire de mon mieux pour vous assister. Que souhaitez-vous savoir exactement?",
            "Votre demande est intéressante. Permettez-moi de vous aider davantage."
        ];
        
        $response = $defaultResponses[array_rand($defaultResponses)];
        error_log("[ChatController] Selected default response: " . $response);
        return $response;
    }

    private function getCorsHeaders(): array
    {
        return [
            'Access-Control-Allow-Origin' => 'http://localhost:5173',
            'Access-Control-Allow-Credentials' => 'true',
            'Access-Control-Allow-Methods' => 'GET, POST, PUT, DELETE, OPTIONS',
            'Access-Control-Allow-Headers' => 'Content-Type, Authorization',
            'Access-Control-Max-Age' => '3600'
        ];
    }
}
