<?php

namespace App\Controller\backend;

use App\Entity\Answer;
use App\Entity\Question;
use App\Form\AnswerType;
use App\Repository\AnswerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/backend/answer")
 */
class AnswerController extends Controller
{
    /**
     * @Route("/", name="answer_index", methods="GET")
     */
    public function index(AnswerRepository $answerRepository): Response
    {
        return $this->render('answer/index.html.twig', ['answers' => $answerRepository->findAll()]);
    }

    /**
     * @Route("/new/{id}", name="answer_new", methods="POST")
     */
    public function new(Request $request, Question $question): Response
    {
            $answer = new Answer();
         
            $body= $request->request->get('a-body');
            if(!empty($body)  ){
                $answer->setBody($body);
            }
            $answer->setQuestion($question);
            $answer->setCreatedAt(new \DateTime);
            $answer->setAuthor($this->getUser());
         
            $em = $this->getDoctrine()->getManager();
            $em->persist($answer);
            $em->flush();

            return $this->redirectToRoute('question_show',['id'=> $question->getId()]);
        

      
    }

    /**
     * @Route("/{id}", name="answer_show", methods="GET")
     */
    public function show(Answer $answer): Response
    {
        return $this->render('answer/show.html.twig', ['answer' => $answer]);
    }

    /**
     * @Route("/{id}/edit", name="answer_edit", methods="GET|POST")
     */
    public function edit(Request $request, Answer $answer): Response
    {
        $form = $this->createForm(AnswerType::class, $answer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('answer_edit', ['id' => $answer->getId()]);
        }

        return $this->render('answer/edit.html.twig', [
            'answer' => $answer,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="answer_delete", methods="DELETE")
     */
    public function delete(Request $request, Answer $answer): Response
    {
        if ($this->isCsrfTokenValid('delete'.$answer->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($answer);
            $em->flush();
        }

        return $this->redirectToRoute('answer_index');
    }

    //fonction permettant de rendre la réponse préférée

    /**
     * @Route("/{id}/preferred", name="answer_preferred", methods="POST")
     */

     public function setPreferred(Answer $answer) 
     {
        $answer->setIsPreferred(true);
        $em = $this->getDoctrine()->getManager();
        $em->flush();

        return $this->redirectToRoute('question_show', ['id'=> $answer->getQuestion()->getId()]);
     }

     /**
     * @Route("/{id}/unallowed", name="answer_preferred", methods="POST")
     */
    //fonction pour modérer les réponses
    public function unallow(Answer $answer) {

        $this->denyAccessUnlessGranted('ROLE_MODERATOR', null, 'Impossible d\'accéder à cette page!');

        $answer->setIsAllowed(false);
        $em = $this->getDoctrine()->getManager();
        $em->flush();
        $this->addFlash('dark', 'La réponse a été modérée');

        return $this->redirectToRoute('question_show', ['id'=> $answer->getQuestion()->getId()]);

    }
}
