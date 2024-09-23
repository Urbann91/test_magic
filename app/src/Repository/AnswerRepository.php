<?php

namespace App\Repository;

use App\Entity\Quiz\Answer;
use Doctrine\ORM\EntityManagerInterface;

class AnswerRepository
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    public function save(Answer $answer): void
    {
        $this->em->persist($answer);
        $this->em->flush();
    }
}
