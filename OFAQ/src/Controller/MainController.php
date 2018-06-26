<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Repository\QuestionRepository;
use App\Entity\Question;
use App\Form\AnswerType;
use App\Entity\Answer;
use App\Repository\AnswerRepository;
use Symfony\Component\HttpFoundation\Request;

class MainController extends Controller
{
    /**
     * @Route("/", name="home", methods="GET")
     */
    public function home(QuestionRepository $questionRepository)
   
    {
        $questions = $questionRepository->sortQuestionsByDate();

        return $this->render('main/index.html.twig', [
            'questions' => $questions,
        ]);
    }
     /**
     * @Route("/question/{id}", name="question_show", methods="GET", requirements= {"id": "\d+"} )
     */
    public function show(Question $question,  Request $request)
    {
        
        $answers = $question->getAnswers();
        dump($answers);
       
        return $this->render('question/show.html.twig', [
            'question' => $question,
            'answers' => $answers,
           
        ]);
    }

    /**
     * @Route("/cgu", name="cgu", methods="GET")
     */
    public function cgu(){
        return $this->render('main/cgu.html.twig');
    }
}
