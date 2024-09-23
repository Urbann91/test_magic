<?php

namespace App\Controller;

use App\Entity\Quiz\Answer;
use App\Entity\Quiz\AnswerOption;
use App\Entity\Quiz\Attempt;
use App\Entity\Quiz\Question;
use App\Entity\Quiz\Test;
use App\Entity\User\AppUser;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class QuizController extends AbstractController
{
    #[Route('/quiz', name: 'quiz_index')]
    public function index(): Response
    {
        return $this->render('quiz/index.html.twig');
    }

    #[Route('/quiz/start', name: 'quiz_start', methods: ['POST'])]
    public function start(Request $request, EntityManagerInterface $em): Response
    {
        $username = $request->request->get('username');

        $user = $em->getRepository(AppUser::class)->findOneBy(['username' => $username]);

        if (!$user) {
            $user = new AppUser();
            $user->setUsername($username);
            $em->persist($user);
            $em->flush();
        }

        $test = $em->getRepository(Test::class)->findOneBy([]);

        $attempt = new Attempt();
        $attempt->setUser($user);
        $attempt->setTest($test);
        $attempt->setStartTime(new \DateTime());
        $em->persist($attempt);
        $em->flush();

        return $this->redirectToRoute('quiz_questions', ['attemptId' => $attempt->getId()]);
    }

    #[Route('/quiz/questions/{attemptId}', name: 'quiz_questions')]
    public function showQuestions(int $attemptId, EntityManagerInterface $em): Response
    {
        $attempt = $em->getRepository(Attempt::class)->find($attemptId);
        $questions = $em->getRepository(Question::class)->findAll();

        return $this->render('quiz/start.html.twig', [
            'attemptId' => $attempt->getId(),
            'startTime' => $attempt->getStartTime(),
            'questions' => $questions,
        ]);
    }

    #[Route('/quiz/submit', name: 'quiz_submit', methods: ['POST'])]
    public function submit(Request $request, EntityManagerInterface $em): Response
    {
        $attemptId = $request->request->get('attemptId');
        $attempt = $em->getRepository(Attempt::class)->find($attemptId);

        $userAnswers = $request->request->all('answers');
        $questions = $em->getRepository(Question::class)->findAll();

        $correctAnswers = 0;

        foreach ($questions as $question) {
            $correctBitmask = 0;
            $userBitmask = 0;

            foreach ($question->getAnswerOptions() as $answerOption) {
                if (isset($userAnswers[$question->getId()])) {
                    foreach ($userAnswers[$question->getId()] as $answerId) {
                        if ($answerId == $answerOption->getId()) {
                            $userBitmask |= $answerOption->getBitMask();
                        }
                    }
                }

                if ($answerOption->isCorrect()) {
                    $correctBitmask |= $answerOption->getBitMask();
                }
            }

            $hasTrueAnswers = ($userBitmask & $correctBitmask) !== 0;
            $hasFalseAnswers = ($userBitmask & ~$correctBitmask) !== 0;

            if ($hasTrueAnswers && !$hasFalseAnswers) {
                $correctAnswers++;
            }

            foreach ($userAnswers as $answer) {
                if (isset($answer['id'])) {
                    $answerOption = $em->getRepository(AnswerOption::class)->find($answer['id']);
                    if ($answerOption) {
                        $answerEntity = new Answer();
                        $answerEntity->setAttempt($attempt);
                        $answerEntity->setAnswerOption($answerOption);
                        $em->persist($answer);
                    }
                }
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
