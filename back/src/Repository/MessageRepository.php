<?php

namespace App\Repository;

use App\Entity\Message;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class MessageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Message::class);
    }

    public function findByChatSession(string $sessionId)
    {
        return $this->createQueryBuilder('m')
            ->join('m.chatSession', 'cs')
            ->where('cs.sessionId = :sessionId')
            ->setParameter('sessionId', $sessionId)
            ->orderBy('m.createdAt', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
