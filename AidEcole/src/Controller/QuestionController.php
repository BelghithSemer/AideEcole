<?php

namespace App\Controller;

use App\Entity\Question;
use App\Entity\Quiz;

use App\Form\QuestionType;
use App\Repository\QuestionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('IS_AUTHENTICATED_FULLY')]
#[Route('/question')]
final class QuestionController extends AbstractController
{
    #[Route(name: 'app_question_index', methods: ['GET'])]
    public function index(QuestionRepository $questionRepository): Response
    {
        return $this->render('question/index.html.twig', [
            'questions' => $questionRepository->findAll(),
        ]);
    }

    #[Route('/{id}/new', name: 'app_question_new', methods: ['GET', 'POST'])]
    public function new(int $id, Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser(); 

        if (!$user) {
            throw $this->createAccessDeniedException('No user logged in');
        }
        $quiz = $entityManager->getRepository(Quiz::class)->find($id);
    
        if (!$quiz) {
            throw $this->createNotFoundException('Quiz not found');
        }
    
        $question = new Question();
        $question->setQuiz($quiz); 
    
        $form = $this->createForm(QuestionType::class, $question);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($question);
            $entityManager->flush();
    
            return $this->redirectToRoute('app_quiz_questions', ['id' => $id], Response::HTTP_SEE_OTHER);
        }

        if ($user->getRoles()[0] == 'ROLE_ADMIN') {
            return $this->render('Admin/quiz/ajoutquestion.html.twig', [
                'form' => $form , 
                'user' => $user, 
                
            ]);
        }
    
        return $this->render('question/Ajout.html.twig', [
            'question' => $question,
            'form' => $form,
            'user' => $user
        ]);
    }
    



    #[Route('/{id}', name: 'app_question_show', methods: ['GET'])]
    public function show(Question $question): Response
    {
        return $this->render('question/show.html.twig', [
            'question' => $question,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_question_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Question $question, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser(); 

        if (!$user) {
            throw $this->createAccessDeniedException('No user logged in');
        }
        $quiz = $question->getQuiz(); 
        
        $form = $this->createForm(QuestionType::class, $question);
        
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
    
            return $this->redirectToRoute('app_quiz_questions', ['id' => $quiz->getId()], Response::HTTP_SEE_OTHER);
        }


    if ($user->getRoles()[0] == 'ROLE_ADMIN') {
            return $this->render('Admin/quiz/modifquestion.html.twig', [
                'form' => $form , 
                'user' => $user, 
            ]);
        } 

        return $this->render('question/modifier.html.twig', [
            'question' => $question,
            'form' => $form,
            'user' => $user
            
        ]);
    }
    

    #[Route('/delete/{id}', name: 'app_question_delete', methods: ['GET', 'POST'])]
    public function delete(Request $request, Question $question, EntityManagerInterface $entityManager, int $id): Response
    {
        if ($this->isCsrfTokenValid('delete'.$question->getId(), $request->get('_token'))) {
            $entityManager->remove($question);
            $entityManager->flush();
        }
    
        return $this->redirectToRoute('app_quiz_questions', ['id' => $id], Response::HTTP_SEE_OTHER);
    }

    #[Route('/quiz/{id}/questions', name: 'app_quiz_questions')]
    public function showQuestions(int $id, QuestionRepository $questionRepository): Response
    {
        $user = $this->getUser(); 

        if (!$user) {
            throw $this->createAccessDeniedException('No user logged in');
        }
        $questions = $questionRepository->findAllByQuizId($id);

        if ($user->getRoles()[0] == 'ROLE_ADMIN') {

            return $this->render('Admin/quiz/questions.html.twig', [
                'questions' => $questions,
                'quizId' => $id,
                'user' => $user
            ]);
        } 
    
        return $this->render('question/listbyquiz.html.twig', [
            'questions' => $questions,
            'quizId' => $id,
            'user' => $user
        ]);
    }
    
    #[Route('/quiz/{id}/con', name: 'app_quiz_questions_consult')]
    public function showQuestionConsult(int $id, QuestionRepository $questionRepository): Response
    {
        $questions = $questionRepository->findAllByQuizId($id);
    
        return $this->render('quiz/consult-quiz.html.twig', [
            'questions' => $questions,
            'quizId' => $id,
        ]);
    }
}
