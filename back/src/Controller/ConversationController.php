<?php

namespace App\Controller;

use App\Entity\Conversation;
use App\Entity\Message;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

/**
 * Conversation Controller
 * 
 * This controller handles all conversation-related operations including:
 * - Creating new conversations
 * - Listing user's conversations
 * - Retrieving conversation details
 * - Adding messages to conversations
 * - Deleting conversations
 *
 * All endpoints require user authentication.
 * 
 * @author AVENA DELMAS KHADRAOUI NGUYEN
 */
#[Route('/api')]
class ConversationController extends AbstractController
{
    /**
     * ConversationController constructor
     * 
     * @param EntityManagerInterface $entityManager For database operations
     */
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {
    }

    /**
     * Lists all conversations for the authenticated user
     * 
     * @return JsonResponse List of conversations with their metadata
     */
    #[Route('/conversations', name: 'app_conversations_list', methods: ['GET'])]
    public function list(): JsonResponse
    {
        $user = $this->getUser();
        $conversations = $this->entityManager->getRepository(Conversation::class)
            ->findBy(['user' => $user], ['createdAt' => 'DESC']);

        $data = array_map(function (Conversation $conversation) {
            return [
                'id' => $conversation->getId(),
                'title' => $conversation->getTitle(),
                'createdAt' => $conversation->getCreatedAt()->format('c'),
                'updatedAt' => $conversation->getUpdatedAt()->format('c'),
            ];
        }, $conversations);

        return new JsonResponse($data);
    }

    /**
     * Creates a new conversation with an initial message
     * 
     * @param Request $request The HTTP request containing the initial message
     * 
     * @return JsonResponse The created conversation data or error response
     */
    #[Route('/conversations', name: 'app_conversation_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        if (!isset($data['message'])) {
            return new JsonResponse(['error' => 'Message is required'], Response::HTTP_BAD_REQUEST);
        }

        $conversation = new Conversation();
        $conversation->setTitle('New Conversation');
        $conversation->setUser($this->getUser());
        $this->entityManager->persist($conversation);

        // Create initial message
        $message = new Message();
        $message->setContent($data['message']);
        $message->setIsFromAi(false);
        $message->setRole('user');
        $message->setConversation($conversation);
        $this->entityManager->persist($message);

        $this->entityManager->flush();

        return new JsonResponse([
            'id' => $conversation->getId(),
            'title' => $conversation->getTitle(),
            'createdAt' => $conversation->getCreatedAt()->format('c'),
            'updatedAt' => $conversation->getUpdatedAt()->format('c'),
            'messages' => [[
                'id' => $message->getId(),
                'content' => $message->getContent(),
                'role' => $message->getRole(),
                'isFromAi' => $message->isFromAi(),
                'createdAt' => $message->getCreatedAt()->format('c'),
            ]],
        ], Response::HTTP_CREATED);
    }

    /**
     * Retrieves a conversation by its ID
     * 
     * @param Conversation $conversation The conversation to retrieve
     * 
     * @return JsonResponse The conversation data or error response
     */
    #[Route('/conversations/{id}', name: 'app_conversations_get', methods: ['GET'])]
    public function get(Conversation $conversation): JsonResponse
    {
        if ($conversation->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException('You cannot access this conversation');
        }

        return $this->json($conversation);
    }

    /**
     * Lists all messages in a conversation
     * 
     * @param Conversation $conversation The conversation to retrieve messages from
     * 
     * @return JsonResponse List of messages in the conversation
     */
    #[Route('/conversations/{id}/messages', name: 'app_conversation_messages', methods: ['GET'])]
    public function messages(Conversation $conversation): JsonResponse
    {
        // Check if the conversation belongs to the current user
        if ($conversation->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException('You cannot access this conversation');
        }

        $messages = $conversation->getMessages();
        $data = array_map(function (Message $message) {
            return [
                'id' => $message->getId(),
                'content' => $message->getContent(),
                'role' => $message->getRole(),
                'isFromAi' => $message->isFromAi(),
                'createdAt' => $message->getCreatedAt()->format('c'),
            ];
        }, $messages->toArray());

        return new JsonResponse($data);
    }

    /**
     * Adds a new message to a conversation
     * 
     * @param Request $request The HTTP request containing the message content
     * @param Conversation $conversation The conversation to add the message to
     * 
     * @return JsonResponse The created message data or error response
     */
    #[Route('/conversations/{id}/messages', name: 'app_conversation_add_message', methods: ['POST'])]
    public function addMessage(Request $request, Conversation $conversation): JsonResponse
    {
        // Check if the conversation belongs to the current user
        if ($conversation->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException('You cannot access this conversation');
        }

        $data = json_decode($request->getContent(), true);
        if (!isset($data['content'])) {
            return new JsonResponse(['error' => 'Content is required'], Response::HTTP_BAD_REQUEST);
        }

        // Create user message
        $message = new Message();
        $message->setContent($data['content']);
        $message->setIsFromAi(false);
        $message->setRole($data['role'] ?? 'user');
        $message->setConversation($conversation);
        $this->entityManager->persist($message);
        $this->entityManager->flush();

        return new JsonResponse([
            'id' => $message->getId(),
            'content' => $message->getContent(),
            'role' => $message->getRole(),
            'isFromAi' => $message->isFromAi(),
            'createdAt' => $message->getCreatedAt()->format('c'),
        ], Response::HTTP_CREATED);
    }
}
