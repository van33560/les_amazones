<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class MentionsLegalsController extends AbstractController
{
    /**
     * @Route ("Mentions/mentions_legals", name = "mentions_legals")
     * @return Response
     */
    // je cree une function qui renvoi vers le lien de ma home page
    public function mentionsLegals() {

        return $this->render('Mentions/mentions_legals.html.twig');

    }
}