<?php

namespace App\Entity\Quiz;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Traits\TimestampableTrait;

#[ORM\Entity]
#[ORM\Table(name: 'questions')]
class Question
{
    use TimestampableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $text;

    #[ORM\ManyToMany(targetEntity: Test::class, mappedBy: 'questions')]
    private $tests;

    #[ORM\OneToMany(mappedBy: 'question', targetEntity: AnswerOption::class, cascade: ['persist', 'remove'])]
    private $answerOptions;

    public function __construct()
    {
        $this->tests = new ArrayCollection();
        $this->answerOptions = new ArrayCollection();
    }

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

    /**
     * @return Collection|Test[]
     */
    public function getTests(): Collection
    {
        return $this->tests;
    }

    public function addTest(Test $test): self
    {
        if (!$this->tests->contains($test)) {
            $this->tests[] = $test;
            $test->addQuestion($this);
        }

        return $this;
    }

    public function removeTest(Test $test): self
    {
        if ($this->tests->removeElement($test)) {
            $test->removeQuestion($this);
        }

        return $this;
    }

    /**
     * @return Collection|AnswerOption[]
     */
    public function getAnswerOptions(): Collection
    {
        return $this->answerOptions;
    }
}