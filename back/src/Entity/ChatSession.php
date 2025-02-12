<?php

namespace App\Entity;

use App\Repository\ChatSessionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * ChatSession Entity
 * 
 * This entity represents a chat session between a user and the AI.
 * Each session has a unique identifier and maintains its own message history.
 * Sessions are used to maintain context and organize conversations between
 * users and the AI assistant.
 *
 * @author AVENA DELMAS KHADRAOUI NGUYEN
 */
#[ORM\Entity(repositoryClass: ChatSessionRepository::class)]
class ChatSession
{
    /**
     * @var int|null Unique identifier for the chat session
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @var User|null The user who owns this chat session
     */
    #[ORM\ManyToOne(targetEntity: User::class)]
    private ?User $user = null;

    /**
     * @var string Unique session identifier (UUID)
     */
    #[ORM\Column(length: 255, unique: true)]
    private string $sessionId;

    /**
     * @var \DateTimeImmutable Timestamp when the session was created
     */
    #[ORM\Column]
    private \DateTimeImmutable $createdAt;

    /**
     * @var Collection<int, Message> Collection of messages in this session
     */
    #[ORM\OneToMany(mappedBy: 'chatSession', targetEntity: Message::class, orphanRemoval: true)]
    private Collection $messages;

    /**
     * Constructor
     * 
     * Initializes a new chat session with:
     * - Empty messages collection
     * - Current timestamp
     * - Random unique session identifier
     */
    public function __construct()
    {
        $this->messages = new ArrayCollection();
        $this->createdAt = new \DateTimeImmutable();
        $this->sessionId = bin2hex(random_bytes(16));
    }

    /**
     * Gets the unique identifier for the chat session
     * 
     * @return int|null The session ID
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Gets the user who owns this chat session
     * 
     * @return User|null The associated user
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * Sets the user who owns this chat session
     * 
     * @param User|null $user The user to associate with this session
     * @return self
     */
    public function setUser(?User $user): self
    {
        $this->user = $user;
        return $this;
    }

    /**
     * Gets the unique session identifier
     * 
     * @return string The session UUID
     */
    public function getSessionId(): string
    {
        return $this->sessionId;
    }

    /**
     * Gets the timestamp when the session was created
     * 
     * @return \DateTimeImmutable The creation timestamp
     */
    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * Gets all messages in this chat session
     * 
     * @return Collection<int, Message> Collection of messages
     */
    public function getMessages(): Collection
    {
        return $this->messages;
    }

    /**
     * Adds a message to this chat session
     * 
     * @param Message $message The message to add
     * @return self
     */
    public function addMessage(Message $message): self
    {
        if (!$this->messages->contains($message)) {
            $this->messages[] = $message;
            $message->setChatSession($this);
        }
        return $this;
    }
}
