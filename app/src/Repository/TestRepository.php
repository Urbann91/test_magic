<?php

namespace App\Repository;

use App\Entity\Quiz\Test;
use Doctrine\ORM\EntityManagerInterface;

class TestRepository
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function findFirstTest(): ?Test
    {
        return $this->em->getRepository(Test::class)->findOneBy([]);
    }
}
