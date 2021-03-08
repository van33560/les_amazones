<?php


namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
class Users  extends AbstractController
{
    /**
     * @Route ("/users", name="users")
     */
    public function index()
    {
        return $this->render('Front/users.html.twig');
    }
}