<?php

namespace App\Entity\Quiz;

use App\Entity\Traits\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity()]
class AnswerOption
{
    use TimestampableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $text;

    #[ORM\Column(type: 'boolean')]
    private $isCorrect = false;

    #[ORM\Column(type: 'integer')]
    private $bitMask;

    #[ORM\ManyToOne(targetEntity: Question::class, inversedBy: 'answerOptions')]
    #[ORM\JoinColumn(nullable: false)]
    private $question;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;
        return $this;
    }

    public function isCorrect(): bool
    {
        return $this->isCorrect;
    }

    public function setIsCorrect(bool $isCorrect): self
    {
        $this->isCorrect = $isCorrect;
        return $this;
    }

    public function getBitMask(): int
    {
        return $this->bitMask;
    }

    public function setBitMask(int $bitMask): self
    {
        $this->bitMask = $bitMask;
        return $this;
    }

    public function getQuestion(): ?Question
    {
        return $this->question;
    }

    public function setQuestion(?Question $question): self
    {
        $this->question = $question;
        return $this;
    }
}
