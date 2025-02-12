<?php

namespace App\Entity;

use App\Repository\MessageRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;

/**
 * Message Entity
 * 
 * This entity represents a chat message in the system.
 * Messages can be either from users or from the AI assistant.
 * Each message is associated with a user and contains content,
 * creation timestamp, and a flag indicating if it's from the AI.
 *
 * @author AVENA DELMAS KHADRAOUI NGUYEN
 */
#[ORM\Entity(repositoryClass: MessageRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ApiResource]
class Message
{
    /**
     * @var int|null Unique identifier for the message
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @var string The content/text of the message
     */
    #[ORM\Column(type: 'text')]
    private string $content;

    /**
     * @var bool Flag indicating if the message is from the AI assistant
     */
    #[ORM\Column]
    private bool $isFromAi = false;

    /**
     * @var \DateTimeImmutable|null Timestamp when the message was created
     */
    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    /**
     * @var string|null Role of the message sender (user/assistant)
     */
    #[ORM\Column(type: 'string', length: 255)]
    private ?string $role = null;

    /**
     * @var User The user who sent or received the message
     */
    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private User $user;

    /**
     * Constructor
     * 
     * Initializes the creation timestamp when a new message is created
     */
    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    /**
     * Pre-persist lifecycle callback
     * 
     * This method is called before the entity is persisted to the database.
     * It is currently empty as the creation timestamp is handled in the constructor.
     */
    #[ORM\PrePersist]
    public function setCreatedAtValue(): void
    {
        // Removed the line $this->createdAt = new \DateTimeImmutable(); as it is now handled in the constructor
    }

    /**
     * Gets the unique identifier for the message
     * 
     * @return int|null The message ID
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Gets the content/text of the message
     * 
     * @return string The message content
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * Sets the content/text of the message
     * 
     * @param string $content The new message content
     * @return static The updated message object
     */
    public function setContent(string $content): static
    {
        $this->content = $content;
        return $this;
    }

    /**
     * Checks if the message is from the AI assistant
     * 
     * @return bool True if the message is from the AI, false otherwise
     */
    public function isFromAi(): bool
    {
        return $this->isFromAi;
    }

    /**
     * Sets the flag indicating if the message is from the AI assistant
     * 
     * @param bool $isFromAi The new value for the flag
     * @return static The updated message object
     */
    public function setIsFromAi(bool $isFromAi): static
    {
        $this->isFromAi = $isFromAi;
        return $this;
    }

    /**
     * Gets the timestamp when the message was created
     * 
     * @return \DateTimeImmutable|null The creation timestamp
     */
    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * Gets the role of the message sender (user/assistant)
     * 
     * @return string|null The role of the message sender
     */
    public function getRole(): ?string
    {
        return $this->role;
    }

    /**
     * Sets the role of the message sender (user/assistant)
     * 
     * @param string $role The new role of the message sender
     * @return static The updated message object
     */
    public function setRole(string $role): static
    {
        $this->role = $role;
        return $this;
    }

    /**
     * Gets the user who sent or received the message
     * 
     * @return User The user associated with the message
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * Sets the user who sent or received the message
     * 
     * @param User $user The new user associated with the message
     * @return static The updated message object
     */
    public function setUser(User $user): static
    {
        $this->user = $user;
        return $this;
    }
}
