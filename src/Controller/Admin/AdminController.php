<?php


namespace App\Controller\Admin;
use App\Entity\User;
use App\Form\EditUserType;
use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;





class AdminController extends AbstractController
{

    /**
     * @Route ("/admin", name="admin_page")
     * @package App\controller\Admin
     * @param ArticleRepository $articleRepository
     * @return Response
     */
    public function index(ArticleRepository $articleRepository)
    {
        return $this->render('Admin/index.html.twig', [
            'articles' => $articleRepository->findAll()
        ]);


    }





}