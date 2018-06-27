<?php

namespace App\Controller\backend;

use App\Entity\Question;
use App\Form\QuestionType;
use App\Repository\QuestionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/backend/question")
 */
class QuestionController extends Controller
{
    

    /**
     * @Route("/new", name="question_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $question = new Question();
        $form = $this->createForm(QuestionType::class, $question);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $question->setAuthor($this->getUser());
            $question->setCreatedAt(new \DateTime());
            $em = $this->getDoctrine()->getManager();
            $em->persist($question);
            $em->flush();

            return $this->redirectToRoute('home');
        }

        return $this->render('question/new.html.twig', [
            'question' => $question,
            'form' => $form->createView(),
        ]);
    }

   

    /**
     * @Route("/{id}/edit", name="question_edit", methods="GET|POST")
     */
    public function edit(Request $request, Question $question): Response
    {
        $form = $this->createForm(QuestionType::class, $question);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this-> addFlash('success', 'Votre question a été modifiée!');

            return $this->redirectToRoute('home');
        }


        return $this->render('question/edit.html.twig', [
            'question' => $question,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="question_delete", methods="DELETE")
     */
    public function delete(Request $request, Question $question): Response
    {
        if ($this->isCsrfTokenValid('delete'.$question->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($question);
            $em->flush();
        }

        return $this->redirectToRoute('question_index');
    }
    /**
     * @Route("/{id}/unallowed", name="question_unallowed", methods="POST")
     */
    //fonction pour modérer les questions
    public function unallow(Question $question) {

        $this->denyAccessUnlessGranted('ROLE_MODERATOR', null, 'Impossible d\'accéder à cette page!');

        $question->setIsAllowed(false);
        $em = $this->getDoctrine()->getManager();
        $em->flush();
        $this->addFlash('dark', 'La question a été modérée');

        return $this->redirectToRoute('home');

    }
}
