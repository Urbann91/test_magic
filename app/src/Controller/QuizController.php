<?php

namespace App\Controller;

use App\Entity\Quiz\Attempt;
use App\Entity\Quiz\Question;
use App\Entity\User\AppUser;
use App\Repository\AttemptRepository;
use App\Service\QuizService;
use Doctrine\DBAL\Exception\LockWaitTimeoutException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class QuizController extends AbstractController
{
    public function __construct(
        private QuizService $quizService,
        private EntityManagerInterface $entityManager
    ) {
    }

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

        $attempt = $this->quizService->createAttempt($user);

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

    /**
     * @throws \Exception
     */
    #[Route('/quiz/submit', name: 'quiz_submit', methods: ['POST'])]
    public function submit(Request $request, AttemptRepository $attemptRepository): Response
    {
        $attemptId = $request->request->get('attemptId');
        $userAnswers = $request->request->all('answers');

        $this->entityManager->beginTransaction();

        try {
            $attempt = $attemptRepository->findAttemptByIdWithLock($attemptId);

            if (!$attempt) {
                throw $this->createNotFoundException('Attempt not found.');
            }

            $resultData = $this->quizService->checkAnswers($attempt, $userAnswers);

            $attempt->setEndTime(new \DateTime());
            $this->entityManager->flush();
            $this->entityManager->commit();

            return $this->render('quiz/result.html.twig', [
                'correctAnswers' => $resultData['correctAnswers'] ?? [],
                'totalQuestions' => $resultData['totalQuestions'] ?? [],
                'questionResults' => $resultData['questionResults'] ?? []
            ]);
        } catch (LockWaitTimeoutException $exception) {
            $this->entityManager->rollback();
            throw new \Exception('Failed to acquire lock, please try again later.');
        } catch (\Exception $exception) {
            $this->entityManager->rollback();
            throw $exception;
        }
    }
}
