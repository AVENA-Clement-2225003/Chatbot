<?php

namespace App\Controller;

use App\Entity\Conversation;
use App\Entity\Message;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api')]
class ConversationController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {
    }

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

    #[Route('/conversations', name: 'app_conversation_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $title = $data['title'] ?? 'New Conversation';

        $conversation = new Conversation();
        $conversation->setTitle($title);
        $conversation->setUser($this->getUser());

        $this->entityManager->persist($conversation);
        $this->entityManager->flush();

        return new JsonResponse([
            'id' => $conversation->getId(),
            'title' => $conversation->getTitle(),
            'createdAt' => $conversation->getCreatedAt()->format('c'),
            'updatedAt' => $conversation->getUpdatedAt()->format('c'),
        ], Response::HTTP_CREATED);
    }

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
                'isFromAi' => $message->isFromAi(),
                'createdAt' => $message->getCreatedAt()->format('c'),
            ];
        }, $messages->toArray());

        return new JsonResponse($data);
    }

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
        $message->setConversation($conversation);
        $this->entityManager->persist($message);

        // TODO: Get AI response
        $aiMessage = new Message();
        $aiMessage->setContent('AI response will be implemented here');
        $aiMessage->setIsFromAi(true);
        $aiMessage->setConversation($conversation);
        $this->entityManager->persist($aiMessage);

        $this->entityManager->flush();

        return new JsonResponse([
            'userMessage' => [
                'id' => $message->getId(),
                'content' => $message->getContent(),
                'isFromAi' => $message->isFromAi(),
                'createdAt' => $message->getCreatedAt()->format('c'),
            ],
            'aiMessage' => [
                'id' => $aiMessage->getId(),
                'content' => $aiMessage->getContent(),
                'isFromAi' => $aiMessage->isFromAi(),
                'createdAt' => $aiMessage->getCreatedAt()->format('c'),
            ],
        ], Response::HTTP_CREATED);
    }
}
