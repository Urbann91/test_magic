<?php

namespace App\Controller;

use App\Entity\Quiz\Answer;
use App\Entity\Quiz\AnswerOption;
use App\Entity\Quiz\Attempt;
use App\Entity\Quiz\Question;
use App\Entity\Quiz\Test;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class QuizController extends AbstractController
{
    #[Route('/quiz', name: 'quiz_index')]
    public function index(EntityManagerInterface $em): Response
    {
        $questions = $em->getRepository(Question::class)->findAll();

        return $this->render('quiz/index.html.twig', [
            'questions' => $questions,
        ]);
    }

    #[Route('/quiz/start/{testId}', name: 'quiz_start')]
    public function start(int $testId, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        $test = $em->getRepository(Test::class)->find($testId);

        $attempt = new Attempt();
        $attempt->setUser($user);
        $attempt->setTest($test);
        $attempt->setStartTime(new \DateTime());

        $em->persist($attempt);
        $em->flush();

        return $this->redirectToRoute('quiz_index', ['attemptId' => $attempt->getId()]);
    }

    #[Route('/quiz/submit/{attemptId}', name: 'quiz_submit', methods: ['POST'])]
    public function submit(int $attemptId, Request $request, EntityManagerInterface $em): Response
    {
        $attempt = $em->getRepository(Attempt::class)->find($attemptId);
        $userAnswers = $request->request->get('answers', []);

        $questions = $em->getRepository(Question::class)->findAll();
        $correctAnswers = 0;

        foreach ($questions as $question) {
            $correctBitmask = 0;
            $userBitmask = 0;

            foreach ($question->getAnswerOptions() as $answerOption) {
                if (in_array($answerOption->getId(), $userAnswers)) {
                    $userBitmask |= $answerOption->getBitMask();
                }
                if ($answerOption->getIsCorrect()) {
                    $correctBitmask |= $answerOption->getBitMask();
                }
            }

            if (($userBitmask & $correctBitmask) === $correctBitmask) {
                $correctAnswers++;
            }

            foreach ($userAnswers as $answerId) {
                $answerOption = $em->getRepository(AnswerOption::class)->find($answerId);
                $answer = new Answer();
                $answer->setAttempt($attempt);
                $answer->setAnswerOption($answerOption);
                $em->persist($answer);
            }
        }

        $attempt->setEndTime(new \DateTime());
        $em->flush();

        return $this->render('quiz/result.html.twig', [
            'correctAnswers' => $correctAnswers,
            'totalQuestions' => count($questions),
        ]);
    }
}
