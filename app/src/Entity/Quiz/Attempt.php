<?php

namespace App\Entity\Quiz;

use App\Entity\Traits\TimestampableTrait;
use App\Entity\User\AppUser;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity()]
#[ORM\Table(name: 'attempts')]
class Attempt
{
    use TimestampableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: AppUser::class)]
    private $user;

    #[ORM\ManyToOne(targetEntity: Test::class)]
    private $test;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private $startTime;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private $endTime;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?AppUser
    {
        return $this->user;
    }

    public function setUser(?AppUser $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getTest(): ?Test
    {
        return $this->test;
    }

    public function setTest(?Test $test): self
    {
        $this->test = $test;

        return $this;
    }

    public function getStartTime(): ?\DateTimeInterface
    {
        return $this->startTime;
    }

    public function setStartTime(\DateTimeInterface $startTime): self
    {
        $this->startTime = $startTime;

        return $this;
    }

    public function getEndTime(): ?\DateTimeInterface
    {
        return $this->endTime;
    }

    public function setEndTime(\DateTimeInterface $endTime): self
    {
        $this->endTime = $endTime;

        return $this;
    }
}
