<?php

namespace App\Controller\backend;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Repository\UserRepository;
use App\Repository\QuestionRepository;
use App\Repository\TagRepository;
use App\Repository\AnswerRepository;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
 /**
     * @Route("backend/admin")
     */
class AdminController extends Controller
{
    /**
     * @Route("/", name="admin_home")
     */
    public function index()
    {

        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Impossible d\'accéder à cette  page!');

        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }
    /**
     * @Route("/users", name="admin_users", methods="GET")
     */
    public function Users(UserRepository $userRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Impossible d\'accéder à cette  page!');

        return $this->render('user/index.html.twig', ['users' => $userRepository->findAll()]);
    }
    /**
     * @Route("/questions", name="admin_questions", methods="GET")
     */
    public function questions(QuestionRepository $questionRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Impossible d\'accéder à cette  page!');

        return $this->render('question/index.html.twig', ['questions' => $questionRepository->findAll()]);
    }

    /**
     * @Route("/answers", name="admin_answers", methods="GET")
     */
    public function answers(AnswerRepository $answerRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Impossible d\'accéder à cette  page!');

        return $this->render('answer/index.html.twig', ['answers' => $answerRepository->findAll()]);
    }

    /**
     * @Route("/tags", name="admin_tags", methods="GET")
     */

    
    public function tags(TagRepository $tagRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_MODERATOR', null, 'Impossible d\'accéder à cette  page!');

        return $this->render('tag/index.html.twig', ['tags' => $tagRepository->findAll()]);
    }

    

    
}
