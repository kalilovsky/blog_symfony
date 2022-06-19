<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Category;
use App\Entity\Comment;
use App\Entity\Message;
use App\Entity\Users;
use App\Form\AddArticleType;
use App\Form\CommentType;
use App\Form\MessageType;
use App\Repository\ArticleRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class BlogController extends AbstractController
{
    /**
     * @Route("/blog", name="app_blog")
     */
    public function index(): Response
    {
        return $this->render('blog/index.html.twig', [
            'controller_name' => 'BlogController',
        ]);
    }
    /**
     * Undocumented function
     *
     * @Route ("/", name="home")
     *     
     * */
    public function home(ArticleRepository $repo): Response
    {
        $articles = $repo->findAll();
        return $this->render('blog/home.html.twig', [
            'articles' => $articles
        ]);
    }
    /**
     * Undocumented function
     *
     * @Route ("/article/{id}", name="article_page")
     */
    public function articlePage(Article $article)
    {
        $form = $this->createForm(CommentType::class);
        return $this->renderForm('blog/articlepage.html.twig', [
            'form'=>$form,
            'article' => $article,
            'messageComment'=> null,
            
        ]);
    }

    /**
     * @Route("/article/add_comment/{id}", name="add_comment")
     */
    public function addComment(Article $article, EntityManagerInterface $manager, Request $request): Response
    {
        $user = $this->getUser();
        $comment = new Comment();
        $form = $this->createForm(CommentType::class,$comment);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $comment->setArticle($article);
            $comment->setUser($user);
            $comment->setDatecreated(new DateTime());
            $comment->setStatus(false);
            $manager->persist($comment);
            $manager->flush();
            unset($form);
            $form = $this->createForm(CommentType::class);
            $messageComment = 'Votre commentaire a bien été envoyé et en attente de validation.';
        }
        return $this->renderForm('blog/articlepage.html.twig', [
            'article' => $article,
            'messageComment'=> $messageComment,
            'form'=>$form
        ]);
    }
    /**
     * Undocumented function
     *
     * @param Article $articles
     * @Route ("/allarticles/", name="all_articles")
     * @return void
     */
    public function allArticle(ArticleRepository $repo)
    {
        $articles = $repo->findAll();
        return $this->render('blog/all_articles.html.twig', [
            'articles' => $articles
        ]);
    }
    /**
     * Undocumented function
     * @Route ("/about/", name="about")
     * @return void
     */
    public function about()
    {
        return $this->render('blog/about.html.twig');
    }

    /**
     * Undocumented function
     * @Route ("/contact/", name="contact")
     * @return void
     */
    public function contact(Request $request,EntityManagerInterface $manager) : Response
    {
        $message = new Message();
        $messageToUser = null;
        $form = $this->createForm(MessageType::class,$message);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $message->setDate(new DateTime());
            $message->setStatus(false);
            $manager->persist($message);
            $manager->flush();
            $messageToUser = 'Votre message a bien été envoyé';
        }
        return $this->renderForm('blog/contact.html.twig',['form'=>$form,'messageToUser'=>$messageToUser]);
    }

    /**
     * @Route("\addarticlepage", name="add_article")
     */
    public function addArticlePage(ManagerRegistry $doctrine): Response
    {
        $article = new Article();
        // Récupération de toutes les catégories
        $repository = $doctrine->getRepository(Category::class);
        $allCategories= $repository->findAll();
        // réordoner les catégorie et leur id pour l'affichages dans le form
        $orderedCategories = [];
        foreach($allCategories as $category){
            $orderedCategories[$category->getCatname()]= $category->getId();
        }

        $form = $this->createForm(AddArticleType::class,$article)
                     ->add('category', ChoiceType::class,['choices'=> $orderedCategories, 'label' => 'Categorie :']);
        return $this->renderForm('blog/add_article.html.twig', [
            'form' => $form
        ]);
    }

    /**
     * @Route("addarticle", name="add_article_function")
     */
    public function addArticleFunction(Request $request, SluggerInterface $slugger, EntityManagerInterface $manager): Response
    {
        $article = new Article();
        $form = $this->createForm(AddArticleType::class,$article);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $photo = $form->get('photoarticle')->getData();
            if($photo){
                $originalFilename = $photo->getClientOriginalName();
                $safeFilename = $slugger->slug($originalFilename);
                try{
                    $photo->move(
                        $this->getParameter('article_photo_directory'),
                        $safeFilename
                    );
                    $t='rr';
                } catch (FileException $e){

                }
                $article->setPhotoarticle($safeFilename)
                        ->setCreationdate(new DateTime())
                        ->setUser($this->getUser())
                        ->setUpdatedate(new DateTime());
                $manager->persist($article);
                $manager->flush();
            }
            return $this->render('blog/articlepage.html.twig', [
                'article' => $article
            ]);
        }
        return $this->redirectToRoute('add_article');
    }
}
