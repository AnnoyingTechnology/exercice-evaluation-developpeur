<?php

namespace App\Controller\backend;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use App\Repository\RoleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/backend/user")
 */
class UserController extends Controller
{
    

    /**
     * @Route("/new", name="user_new", methods="GET|POST")
     */
    public function new(Request $request, RoleRepository $repo, UserPasswordEncoderInterface $encoder): Response
    {
        $user = new User();
        // On utilise la fonction créée findUserRole afin de ne pas instancier  x fois la classe Role pour créer un ROLE_USER
        $roleUser = $repo->findUserRole();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setRole($roleUser);
            $user->setPassword($encoder->encodePassword($user, $user->getPassword()));
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            $this->addFlash('success', 'compte utilisateur créé!');
            return $this->redirectToRoute('home');
        }

        return $this->render('user/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="user_show", methods="GET")
     */
    public function show(User $user): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER', null, 'Impossible d\'accéder à cette  page!');

        return $this->render('user/show.html.twig', ['user' => $user]);
    }

    /**
     * @Route("/{id}/edit", name="user_edit", methods="GET|POST")
     */
    public function edit(Request $request, User $user, UserPasswordEncoderInterface $encoder): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER', null,  'Impossible d\'accéder à cette  page!');

       /* // On stocke le mot de passe courant (celui en base)
        $currentPassword = $user->getPassword();
        // Et on le passe à vide...
        $user->setPassword('');*/

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
             // On compare le nouveau (form) et l'ancien (current)
             /*if(empty($user->getPassword())) {
                // On remets l'ancien
                $user->setPassword($currentPassword);
                } else {*/
                $user->setPassword($encoder->encodePassword($user, $user->getPassword()));
                   // }

            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'compte utilisateur modifié!');

            return $this->redirectToRoute('user_edit', ['id' => $user->getId()]);
        } /*else {
            $this->addFlash('danger', 'Erreur!');
            return $this->redirectToRoute('user_edit', ['id' => $user->getId()]);
             }*/

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="user_delete", methods="DELETE")
     */
    public function delete(Request $request, User $user): Response
    {
        /*$this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Impossible d\'accéder à cette  page!');*/

        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($user);
            $em->flush();
        }

        return $this->redirectToRoute('home');
    }

    /**
     * @Route("/{id}/questions", name="user_questions", methods="GET")
     */
    public function userQuestions(User $user) 
    {   
        $this->denyAccessUnlessGranted('ROLE_USER', null, 'Impossible d\'accéder à cette  page!');

        $questions= $user->getQuestions();
        return $this->render('question/index.html.twig',[
            'questions' => $questions,
        ]);

    }

     /**
     * @Route("/{id}/answers", name="user_answers", methods="GET")
     */
    public function userAnswers(User $user) 
    {   
        $this->denyAccessUnlessGranted('ROLE_USER', null, 'Impossible d\'accéder à cette  page!');

        $answers= $user->getAnswers();
        return $this->render('answer/index.html.twig',[
            'answers' => $answers,
        ]);

    }
}
