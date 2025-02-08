<?php

namespace App\Repository;

use App\Entity\ChatSession;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ChatSessionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ChatSession::class);
    }

    public function findBySessionId(string $sessionId): ?ChatSession
    {
        return $this->findOneBy(['sessionId' => $sessionId]);
    }
}
