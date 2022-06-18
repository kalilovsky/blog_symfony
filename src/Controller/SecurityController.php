<?php

namespace App\Controller;

use App\Entity\Users;
use App\Form\RegistrationType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class SecurityController extends AbstractController
{
    /**
     * @Route("\login",name="security_login")
     */

    public function login(AuthenticationUtils $authenticationUtils, ArticleRepository $repo) : Response {

        $error = $authenticationUtils->getLastAuthenticationError();
        
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        $articles = $repo->findAll();
              return $this->render('security/login.html.twig', [
            'articles'=>$articles,
            'last_username' => $lastUsername,
            'error'         => $error
        ]);

    }
    /**
     * @Route("\register",name="security_registration")
     */
    public function registration(UserPasswordHasherInterface $passwordHasher,Request $request, EntityManagerInterface $manager){

        $user = new Users();

        $form = $this->createForm(RegistrationType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $plaintTextPwd = $user->getPassword();
            $hashedPwd = $passwordHasher->hashPassword($user,$plaintTextPwd);
            $user->setPwd($hashedPwd);
            $user->setUserType('normal');
            $user->setPhotouser('account_default.png');
            $manager->persist($user);
            $manager->flush();
        }
        return $this->renderForm('security/regisration.html.twig',[
            'form' => $form
        ]);
    }
    /**
     * Undocumented function
     * @Route("\logout",name="security_logout")
     * @return void
     */
    public function logout(){

    }
}
