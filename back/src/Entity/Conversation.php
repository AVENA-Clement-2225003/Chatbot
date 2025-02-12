<?php

namespace App\Entity;

use App\Repository\ConversationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Conversation Entity
 * 
 * This entity represents a chat conversation in the system.
 * Each conversation belongs to a user and contains multiple messages.
 * Conversations are used to organize messages into distinct chat sessions.
 * They include metadata such as title and timestamps.
 *
 * @author AVENA DELMAS KHADRAOUI NGUYEN
 */
#[ORM\Entity(repositoryClass: ConversationRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Conversation
{
    /**
     * @var int|null Unique identifier for the conversation
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @var string|null Title of the conversation
     */
    #[ORM\Column(length: 255)]
    private ?string $title = null;

    /**
     * @var User|null The user who owns this conversation
     */
    #[ORM\ManyToOne(inversedBy: 'conversations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    /**
     * @var Collection<int, Message> Collection of messages in this conversation
     */
    #[ORM\OneToMany(mappedBy: 'conversation', targetEntity: Message::class, orphanRemoval: true)]
    private Collection $messages;

    /**
     * @var \DateTimeImmutable|null Timestamp when the conversation was created
     */
    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    /**
     * @var \DateTimeImmutable|null Timestamp when the conversation was last updated
     */
    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;

    /**
     * Constructor
     * 
     * Initializes a new conversation with an empty messages collection
     */
    public function __construct()
    {
        $this->messages = new ArrayCollection();
    }

    /**
     * Sets creation and update timestamps before persisting
     */
    #[ORM\PrePersist]
    public function setCreatedAtValue(): void
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
    }

    /**
     * Updates the updatedAt timestamp before updating the entity
     */
    #[ORM\PreUpdate]
    public function setUpdatedAtValue(): void
    {
        $this->updatedAt = new \DateTimeImmutable();
    }

    /**
     * Returns the unique identifier for the conversation
     * 
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Returns the title of the conversation
     * 
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * Sets the title of the conversation
     * 
     * @param string $title
     * @return static
     */
    public function setTitle(string $title): static
    {
        $this->title = $title;
        return $this;
    }

    /**
     * Returns the user who owns this conversation
     * 
     * @return User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * Sets the user who owns this conversation
     * 
     * @param User|null $user
     * @return static
     */
    public function setUser(?User $user): static
    {
        $this->user = $user;
        return $this;
    }

    /**
     * Returns the collection of messages in this conversation
     * 
     * @return Collection<int, Message>
     */
    public function getMessages(): Collection
    {
        return $this->messages;
    }

    /**
     * Adds a message to the conversation
     * 
     * @param Message $message
     * @return static
     */
    public function addMessage(Message $message): static
    {
        if (!$this->messages->contains($message)) {
            $this->messages->add($message);
            $message->setConversation($this);
        }
        return $this;
    }

    /**
     * Removes a message from the conversation
     * 
     * @param Message $message
     * @return static
     */
    public function removeMessage(Message $message): static
    {
        if ($this->messages->removeElement($message)) {
            if ($message->getConversation() === $this) {
                $message->setConversation(null);
            }
        }
        return $this;
    }

    /**
     * Returns the timestamp when the conversation was created
     * 
     * @return \DateTimeImmutable|null
     */
    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * Returns the timestamp when the conversation was last updated
     * 
     * @return \DateTimeImmutable|null
     */
    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }
}
