<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Repository\UsersRepository;
use App\Repository\ArticleRepository;
use App\Repository\CommentRepository;
use App\Repository\MessageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
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

    /**
     * @Route("admin/users", name="admin_users")
     */
    public function adminAllUsers(UsersRepository $usersRepo): Response
    {
        $users = $usersRepo->findAll();
        return $this->render('admin/admin_users.html.twig', [
            'users' => $users,
        ]);
    }

    /**
     * @Route("admin/comments", name="admin_comments")
     */
    public function adminAllComments(CommentRepository $commentsRepo): Response
    {
        $comments = $commentsRepo->findAll();
        return $this->render('admin/admin_comments.html.twig', [
            'comments'=>$comments
        ]);
    }

    /**
     * @Route("admin/articles", name="admin_articles")
     */
    public function adminAllArticles(ArticleRepository $articlesRepo): Response
    {
        $articles = $articlesRepo->findAll();
        return $this->render('admin/admin_articles.html.twig', [
            'articles'=>$articles
        ]);
    }

    /**
     * @Route("admin/messages", name="admin_messages")
     */
    public function adminAllMessages(MessageRepository $messagesRepo): Response
    {
        $messages = $messagesRepo->findAll();
        return $this->render('admin/admin_messages.html.twig', [
            'messages'=>$messages
        ]);
    }

    /**
     * @Route("admin/getComment/{id}", name="admin_get_comment")
     */
    public function adminGetComment(Comment $comment): Response
    {
        return $this->json($comment,200,[],['groups'=>'comment']);
    }

    /**
     * @Route("admin/setComment/{id}", name="admin_set_comment")
     */
    public function adminSetComment(Request $request, Comment $comment,EntityManagerInterface $doctrine): Response
    {
        $data = json_decode($request->getContent(),true);
        $comment->setStatus($data['status'] == 1 ? true : false);
        $comment->setContentcomment($data['contentcomment']);
        // $comments = $serializer->deserialize($request->getContent(),Comment::class,'json',['groups'=>'comment']);
        $doctrine->persist($comment);
        $doctrine->flush();
        return $this->json($comment->getId(),200,[],['groups'=>'comment']);
    }

    /**
     * @Route("admin/deleteComment/{id}", name="admin_delete_comment")
     */
    public function adminDeleteComment(Comment $comment,EntityManagerInterface $doctrine): Response
    {
        $id = $comment->getId();
        $doctrine->remove($comment);
        $doctrine->flush();
        return $this->json($id,200,[]);
    }
}
