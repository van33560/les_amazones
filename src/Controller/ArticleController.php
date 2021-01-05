<?php


namespace App\Controller;

use App\Repository\ArticleRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ArticleController extends AbstractController
{
    /**
     * @Route("Front/article/articles", name="Front_articlelist")
     * @param ArticleRepository $articlesRepository
     * @return Response
     */
    //je créer une function qui me permet de récuperer mes articles
    public function Articlelist(ArticleRepository $articlesRepository)
        {    //find all est une méthode qui permet de récuperer tous les articles
            //doctrine éffectue la requête pour moi ici select*from article
            $articles = $articlesRepository->findAll();
            //la fonction render me permet de renvoyer vers mon fichier twig les infos via sa route
                return $this->render("Front/articles.html.twig",[
                    'articles' => $articles
                ]);
        }


    /**
     * @route("Front/article/show/{id}",name="Front_articleShow")
     * @param $id
     * @param ArticleRepository $articlesRepository
     * @return Response;
     */
    //je créer une function qui me permet de récuperer un article et son id
    public function articleShow($id, ArticleRepository $articlesRepository)
        {   //find($id) est une méthode qui permet de récuperer une category via son id
            //doctrine éffectue la requête pour moi ici select category.name form category where id =
            $article = $articlesRepository->find($id);
            //la fonction render me permet de renvoyer vers un fichier twig via sa route
                return $this->render("Front/article.html.twig", [
                    'article' => $article
                ]);

        }
}