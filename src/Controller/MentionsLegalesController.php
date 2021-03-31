<?php


namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class MentionsLegalesController extends AbstractController
{
    /**
     * @Route ("/mentionslegales", name="mentionlegales")
     */
    // je cree une function qui renvoi vers ma home page
    public function mentionsLegales() {

        return $this->render('Mentions/mentionslegales.html.twig');

    }
}