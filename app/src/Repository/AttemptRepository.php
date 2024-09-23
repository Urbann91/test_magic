<?php

namespace App\Repository;

namespace App\Repository;

use App\Entity\Quiz\Attempt;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\DBAL\LockMode;

class AttemptRepository
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    public function findAttemptByIdWithLock(int $attemptId): ?Attempt
    {
        return $this->em->getRepository(Attempt::class)->find($attemptId, LockMode::PESSIMISTIC_WRITE);
    }
}
