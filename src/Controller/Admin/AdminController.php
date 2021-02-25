<?php


namespace App\Controller\Admin;
use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;





class AdminController extends AbstractController
{

    /**
     * @Route ("/admin", name="admin_page")
     * @package App\controller\Admin
     * @param ArticleRepository $articleRepository
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(ArticleRepository $articleRepository)
    {
        return $this->render('Admin/index.html.twig', [
            'articles' => $articleRepository->findAll()
        ]);


    }



}