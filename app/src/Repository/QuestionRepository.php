<?php

namespace App\Repository;

use App\Entity\Quiz\Question;
use Doctrine\ORM\EntityManagerInterface;

class QuestionRepository
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    public function findAllQuestions(): array
    {
        return $this->em->getRepository(Question::class)->findAll();
    }
}
