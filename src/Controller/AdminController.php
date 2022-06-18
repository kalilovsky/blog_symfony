<?php

namespace App\Controller;

use App\Repository\UsersRepository;
use App\Repository\ArticleRepository;
use App\Repository\CommentRepository;
use App\Repository\MessageRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin/dashboard", name="admin_dashboard")
     */
    public function index(ArticleRepository $articleRepo, UsersRepository $usersRepo, CommentRepository $commentsRepo, MessageRepository $messageRepo): Response
    {
        $messages = $messageRepo->findBy(array('status'=>'0'));
        $articles = $articleRepo->findAll();
        $users = $usersRepo->findAll();
        $comments = $commentsRepo->findBy(array('status'=>'0'));
        return $this->render('admin/dashboard.html.twig', [
            'articles' => $articles,
            'users'=>$users,
            'comments'=>$comments,
            'messages'=>$messages
        ]);
    }
}
