<?php

namespace App\Entity\Quiz;

use App\Entity\Traits\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity()]
#[ORM\Table(name: 'answers')]
class Answer
{
    use TimestampableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: Attempt::class)]
    private $attempt;

    #[ORM\ManyToOne(targetEntity: AnswerOption::class)]
    private $answerOption;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAttempt(): ?Attempt
    {
        return $this->attempt;
    }

    public function setAttempt(?Attempt $attempt): self
    {
        $this->attempt = $attempt;
        return $this;
    }

    public function getAnswerOption(): ?AnswerOption
    {
        return $this->answerOption;
    }

    public function setAnswerOption(?AnswerOption $answerOption): self
    {
        $this->answerOption = $answerOption;
        return $this;
    }
}
