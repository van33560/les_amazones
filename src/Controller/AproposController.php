<?php


namespace App\Controller;




use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


class AproposController extends AbstractController
{
    /**
     * @Route("/apropos", name="apropos")
     */
    // je cree une function qui renvoi vers ma home page
    public function apropos() {

        return $this->render('Apropos/apropos.html.twig');

    }


}