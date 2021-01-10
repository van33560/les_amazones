<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class PagesController extends AbstractController
{
    /**
     * @Route("/home", name="home_page")
     */
    // je cree une function qui renvoi vers ma home page
    public function homepage() {

        return $this->render('home.html.twig');

    }


}