<?php


namespace App\Controller\Admin;
use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;



/**
 * @Route   ("/admin", name="admin_page")
 * @package App\controller\Admin
 */

class AdminController extends AbstractController
{

    /**
     * @Route("/", name="home")
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