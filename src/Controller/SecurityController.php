<?php

namespace App\Controller;





use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     */
    //je cree une function login et la methode authentiction me permet de authentifier l'utilisateur
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        //$user = new User();

        //je recupere une erreur si il y en a une
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

           return $this->render('Connexion/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);

        if ('ROLE_ADMIN') {
            return $this->redirectToRoute('admin_page');
        }



    }

    /**
     * @Route("/logout", name="app_logout")
     */
    //je cree une fonction de deconnexion
    public function logout()
    {
        throw new \LogicException('Cette méthode peut être vide elle sera interceptée 
        par la clé de déconnexion sur votre parefeu.');
    }

    ///**
    // * @route("/admin/home", name="admin_home")
     //* @return Response
     //*/

    //public function redirectAction(){

        //$authCheker =$this->container->get('security.authorization_cheker');
        //if($authCheker->isGranted('ROLE_ADMIN')){
            //return $this->render('Admin/index.html.twig');
        //}else if ($authCheker->isGranted('ROLE_USER')){
             //return $this->render('home.html.twig');
        //}else{
           // return $this->render('home.html.twig');
        //}

    //}




}
