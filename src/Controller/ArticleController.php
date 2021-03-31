<?php


namespace App\Controller;



use App\Repository\ArticleRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ArticleController extends AbstractController
{
    /**
     * @Route("Front/article/articles", name="Front_articlelist")
     * @param ArticleRepository $articleRepository
     * @param Request $request
     * @return Response
     */
    //je créer une function qui me permet de récuperer mes articles

     public function Articlelist(ArticleRepository $articleRepository, Request $request)
        {
           //find all est une méthode qui permet de récuperer tous les articles
            //doctrine éffectue la requête pour moi ici select*from article
            //find by me permet de faire un select ( requete ) en passant des parametres ( 1er param = critere/ 2iem param = orderBy)
            $articles = $articleRepository->findBy([], ['date'=>'DESC']);
            //la fonction render me permet de renvoyer vers mon fichier twig les infos via sa route
                return $this->render("Front/articles.html.twig",[
                    'articles' => $articles
                ]);


        }

    /**
     * @Route ("/articles/search", name="search_articles")
     * @param Request $request
     * @param ArticleRepository $articleRepository
     * @return Response
     */
    // function qui renvois les articles par rapport a ma recherche
    public function searchArticles(Request $request, ArticleRepository $articleRepository)
    {
        //récupère le contenu de mon input qui porte le name search
        $search = $request->query->get('search');


        $articles = $articleRepository->searchByTerm($search);

              return $this->render("Front/articles.html.twig",[
        'articles' => $articles,

    ]);

    }

    /**
     * @Route ("Front/article/show/{id}",name="Front_articleShow")
     * @param $id
     * @param ArticleRepository $articleRepository
     * @return Response;
     */
    //je créer une function qui me permet de récuperer un article et son id
    public function articleShow($id, ArticleRepository $articleRepository)
        {   //find($id) est une méthode qui permet de récuperer une category via son id
            //doctrine éffectue la requête pour moi ici select category.name form category where id =
            $article = $articleRepository->find($id);
            //la fonction render me permet de renvoyer vers un fichier twig via sa route
                return $this->render("Front/article.html.twig", [
                    'article' => $article
                ]);

        }



}