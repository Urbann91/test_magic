<?php

namespace App\Repository;

use App\Entity\Quiz\Attempt;
use Doctrine\ORM\EntityManagerInterface;

class AttemptRepository
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    public function findAttemptById(int $attemptId): ?Attempt
    {
        return $this->em->getRepository(Attempt::class)->find($attemptId);
    }
}
