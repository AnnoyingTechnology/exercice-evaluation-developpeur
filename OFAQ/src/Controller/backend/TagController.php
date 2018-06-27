<?php

namespace App\Controller\backend;

use App\Entity\Tag;
use App\Form\TagType;
use App\Repository\TagRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/backend/tag")
 */
class TagController extends Controller
{
    

    /**
     * @Route("/new", name="tag_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $this->denyAccessUnlessGranted('ROLE_MODERATOR', null, 'Impossible d\'accéder à cette page!');

        $tag = new Tag();
        $form = $this->createForm(TagType::class, $tag);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($tag);
            $em->flush();

            return $this->redirectToRoute('tag_index');
        }

        return $this->render('tag/new.html.twig', [
            'tag' => $tag,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="tag_show", methods="GET")
     */
   /* public function show(Tag $tag): Response
    {
        return $this->render('tag/show.html.twig', ['tag' => $tag]);
    }*/

    /**
     * @Route("/{id}/edit", name="tag_edit", methods="GET|POST")
     */
    public function edit(Request $request, Tag $tag): Response
    {
        $this->denyAccessUnlessGranted('ROLE_MODERATOR', null, 'Impossible d\'accéder à cette page!');
        $form = $this->createForm(TagType::class, $tag);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('tag_edit', ['id' => $tag->getId()]);
        }

        return $this->render('tag/edit.html.twig', [
            'tag' => $tag,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="tag_delete", methods="DELETE")
     */
    public function delete(Request $request, Tag $tag): Response
    {
        $this->denyAccessUnlessGranted('ROLE_MODERATOR', null, 'Impossible d\'accéder à cette page!');
        if ($this->isCsrfTokenValid('delete'.$tag->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($tag);
            $em->flush();
        }

        return $this->redirectToRoute('tag_index');
    }


        //Affiche les questions affiliées à un tag 

    /**
     * @Route("/{id}/questions", name="tag_questions", methods="GET")
     */
    public function questionList(Tag $tag) {

        $questions = $tag->getQuestions();

       return $this->render('question/index.html.twig', [
            'questions'=>$questions,
        ] );

    }
}
