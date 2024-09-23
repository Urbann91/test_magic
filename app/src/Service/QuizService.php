<?php

namespace App\Service;

use App\Entity\Quiz\Attempt;
use App\Entity\User\AppUser;
use App\Factory\AnswerFactory;
use App\Repository\AnswerRepository;
use App\Repository\AttemptRepository;
use App\Repository\QuestionRepository;
use App\Repository\TestRepository;
use Doctrine\ORM\EntityManagerInterface;

class QuizService
{
    public function __construct(
        private AnswerFactory $answerFactory,
        private EntityManagerInterface $em,
        private AnswerRepository $answerRepository,
        private QuestionRepository $questionRepository,
        private AttemptRepository $attemptRepository,
        private TestRepository $testRepository
    ) {
    }

    public function createAttempt(AppUser $user): Attempt
    {
        $test = $this->testRepository->findFirstTest();

        $attempt = new Attempt();
        $attempt->setUser($user);
        $attempt->setTest($test);
        $attempt->setStartTime(new \DateTime());

        $this->em->persist($attempt);
        $this->em->flush();

        return $attempt;
    }

    public function checkAnswers(Attempt $attempt, array $userAnswers): array
    {
        $questions = $this->questionRepository->findAllQuestions();
        $correctAnswers = 0;
        $questionResults = [];

        foreach ($questions as $question) {
            $correctBitmask = 0;
            $userBitmask = 0;
            $correctOptions = [];
            $userSelectedOptions = [];

            foreach ($question->getAnswerOptions() as $answerOption) {
                if ($answerOption->isCorrect()) {
                    $correctOptions[] = $answerOption;
                    $correctBitmask |= $answerOption->getBitMask();
                }

                if (isset($userAnswers[$question->getId()])) {
                    foreach ($userAnswers[$question->getId()] as $answerId) {
                        if ($answerId == $answerOption->getId()) {
                            $userSelectedOptions[] = $answerOption;
                            $userBitmask |= $answerOption->getBitMask();
                        }
                    }
                }
            }

            $hasTrueAnswers = ($userBitmask & $correctBitmask) !== 0;
            $hasFalseAnswers = ($userBitmask & ~$correctBitmask) !== 0;

            if ($hasTrueAnswers && !$hasFalseAnswers) {
                $correctAnswers++;
            }

            $questionResults[] = [
                'question' => $question,
                'correctOptions' => $correctOptions,
                'userSelectedOptions' => $userSelectedOptions
            ];

            foreach ($userSelectedOptions as $selectedOption) {
                $answer = $this->answerFactory->create($attempt, $selectedOption);
                $this->answerRepository->save($answer);
            }
        }

        $attempt->setEndTime(new \DateTime());
        $this->em->flush();

        return [
            'correctAnswers' => $correctAnswers,
            'totalQuestions' => count($questions),
            'questionResults' => $questionResults
        ];
    }
}
