<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
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
}
