<?php

namespace App\Controller;

use App\Entity\Quiz;
use App\Entity\Question;
use App\Entity\Note ; 
use App\Entity\Bons ;
use Symfony\Component\HttpFoundation\JsonResponse;

use App\Form\QuizType;
use App\Repository\QuizRepository;
use App\Repository\MatiereRepository;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('IS_AUTHENTICATED_FULLY')]
final class QuizController extends AbstractController
{
    #[Route('/quiz', name: 'app_quiz_index', methods: ['GET'])]
    public function index(QuizRepository $quizRepository): Response
    {
        $user = $this->getUser(); 
    
        if (!$user) {
            throw $this->createAccessDeniedException('No user logged in');
        }
    
        if ($user->getRoles()[0] == 'ROLE_ADMIN') {
            return $this->render('Admin/quiz/quiz.html.twig', [
                'quizzes' => $quizRepository->findAll(),
                'user' => $user, 
            ]);
        } elseif ($user->getRoles()[0] == 'ROLE_ENSEIGNANT') {
            return $this->render('Quiz/Quizlist.html.twig', [
                'quizzes' => $quizRepository->findAll(),
                'user' => $user, 
            ]);
        } else {
            return $this->render('home/quiz.html.twig', [
                'user' => $user, 
            ]);
        }
    }
    

    #[Route('quiz/new', name: 'app_quiz_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser(); 

        if (!$user) {
            throw $this->createAccessDeniedException('No user logged in');
        }
        
        $quiz = new Quiz();
        $quiz->setIdUser($user);
        $form = $this->createForm(QuizType::class, $quiz);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($quiz);
            $entityManager->flush();

            return $this->redirectToRoute('app_quiz_index', [], Response::HTTP_SEE_OTHER);
        }

        
        if ($user->getRoles()[0] == 'ROLE_ADMIN') {
            return $this->render('Admin/quiz/modifquiz.html.twig', [
                'form' => $form , 
                'user' => $user, 
                
            ]);
        }
    
        return $this->render('quiz/newquiz.html.twig', [
            'quiz' => $quiz,
            'form' => $form,
            'user' => $user , 
        ]);
    }

    #[Route('quiz/{id}', name: 'app_quiz_show', methods: ['GET'])]
    public function show(Quiz $quiz): Response
    {
        return $this->render('quiz/show.html.twig', [
            'quiz' => $quiz,
        ]);
    }

    #[Route('quiz/{id}/edit', name: 'app_quiz_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Quiz $quiz, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser(); 

        if (!$user) {
            throw $this->createAccessDeniedException('No user logged in');
        }
        $form = $this->createForm(QuizType::class, $quiz);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_quiz_index', [], Response::HTTP_SEE_OTHER);
        }

        if ($user->getRoles()[0] == 'ROLE_ADMIN') {
            return $this->render('Admin/quiz/profile.html.twig', [
                'form' => $form , 
                'user' => $user, 
            ]);
        } 
        return $this->render('Quiz/admin-settings.html.twig' , [ 'quiz' => $quiz,
        'form' => $form , 
        'user' => $user
        
        ]
    
    );
    }

    #[Route('quiz/{id}', name: 'app_quiz_delete', methods: ['POST'])]
    public function delete(Request $request, Quiz $quiz, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$quiz->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($quiz);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_quiz_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/quiz/ajout/{id}/submit', name: 'quiz_submit', methods: ['POST'])]
    public function submitQuiz(Request $request, EntityManagerInterface $entityManager, $id): Response
    {
        $quiz = $entityManager->getRepository(Quiz::class)->find($id);
        
        if (!$quiz) {
            return new JsonResponse([
                'error' => 'Quiz not found'
            ], JsonResponse::HTTP_NOT_FOUND);
        }
    
        $totalQuestionsInQuiz = $entityManager->createQueryBuilder()
            ->select('COUNT(q.id)')
            ->from(Question::class, 'q')
            ->where('q.quiz = :quiz')
            ->setParameter('quiz', $quiz)
            ->getQuery()
            ->getSingleScalarResult();
    
        $data = json_decode($request->request->get('quiz_data'), true);
    
        if (!$data || !is_array($data)) {
            return new JsonResponse([
                'error' => 'Invalid quiz data received'
            ], JsonResponse::HTTP_BAD_REQUEST);
        }
    
        $score = 0;
        $results = [];
    
        foreach ($data as $questionId => $userAnswer) {
            $questionId = (int) $questionId; 
    
            $question = $entityManager->getRepository(Question::class)->find($questionId);
    
            if ($question) {
                $correctAnswer = $question->getCorrectAnswer();
                $isCorrect = ($correctAnswer == $userAnswer);
                if ($isCorrect) {
                    $score++;
                }
    
                $results[] = [
                    'question' => $question->getTitle(),
                    'correct' => $isCorrect,
                    'userAnswer' => $userAnswer,
                    'correctAnswer' => $correctAnswer
                ];
            }
        }
    
        $successRate = ($totalQuestionsInQuiz > 0) ? ($score / $totalQuestionsInQuiz) * 100 : 0;
        $note = new Note();
        $note->setQuiz($quiz);
        $note->setNote($score);
        $note->setIdUser($this->getUser());
    
        $entityManager->persist($note);
        $entityManager->flush();
    
        if ($successRate >= 70) {
            $bon = new Bons();
            $bon->setCode($this->generateRandomCode()); 
            $bon->setDateDeb(new \DateTime()); 
            $bon->setDateFin((new \DateTime())->modify('+10 days'));
            $bon->setIdUser($this->getUser());
    
            $entityManager->persist($bon);
            $entityManager->flush();
    
            return $this->render('Quiz/success.html.twig', [
                'score' => $score,
                'dateDeb' => $bon->getDateDeb(),
                'dateFin' => $bon->getDateFin(),
                'totalQuestions' => $totalQuestionsInQuiz,
                'details' => $results,
                'successRate' => $successRate,
                'bonCode' => $bon->getCode()
            ]);
        } else {
            return $this->render('Quiz/failed.html.twig', [
                'score' => $score,
                'totalQuestions' => $totalQuestionsInQuiz,
                'details' => $results,
            ]);
        }
    }
    
    private function generateRandomCode(): string
    {
        return strtoupper(substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 8));
    }
    

#[Route('/{id}/{id1}/quizlist', name: 'user_quizzes_list')]
public function userQuizlist($id , $id1 , QuizRepository $quizRepository): Response
{
    $user = $this->getUser();
    
    $quizzes = $quizRepository->findByMatierAndNiveau($id1, $id);


    return $this->render('quiz/quizuser.html.twig', [
        'quizzes' => $quizzes , 
        'user' => $user
    ]);
}


#[Route('/{id}/{id1}/quizlist/list', name: 'user_quizzes_list_user')]
public function userQuizlistUSER($id , $id1 , QuizRepository $quizRepository): Response
{
    $user = $this->getUser();
    
    $quizzes = $quizRepository->findByMatierAndNiveauAndUser($id1, $id, $user->getId());
    
    return $this->render('quiz/Quizlist.html.twig', [
        'quizzes' => $quizzes, 
        'user' => $user ,         
    ]);
}


#[Route('/quiz/results', name: 'quiz_results' , methods: ['GET'])]
public function results(Request $request): Response
{
    $score = $request->query->get('score');
    $totalQuestions = $request->query->get('totalQuestions'); 
    $details = $request->query->get('details');  
    dump($score, $totalQuestions, $details);

    return $this->render('quiz/scorrection.html.twig', [
        'score' => $score,
        'totalQuestions' => $totalQuestions,
        'details' => $details,
    ]);
}



}
