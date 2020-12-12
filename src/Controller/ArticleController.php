<?php


namespace App\Controller;

use App\Repository\ArticleRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ArticleController extends AbstractController
{
/**
 * @Route("admin/article/articles", name="admin_articlelist")
 * @param ArticleRepository $articlesRepository
 * @return Response
 */
//ma methode acticle repository me permet de recuperer via la bdd les données et de les afficher avec return render
public function Articlelist(ArticleRepository $articlesRepository)
    {    //find all est une methode qui permet de recuperer tous les articles
        //doctrine effectue la requete pour moi ici select*from article
        $articles = $articlesRepository->findAll();
        //la fonction render me permet d'envoyer a twig les infos qui seront affichés
        return $this->render("Front/articles.html.twig",[
            'articles' => $articles
        ]);
    }

// chemin de ma route avec id
/**
 * @route("/article/show/{id}",name="articleShow")
 * @param $id
 * @param ArticleRepository $articlesRepository
 * @return Response;
 */
// ma methode articlerepository me permet de recuperer les données de ma bdd et de retourner un resultat via la propriete render
public function articleShow($id, ArticleRepository $articlesRepository)
    {   //find($id) est une methode qui permet de recuperer une category via son id
        //doctrine effectue la requete pour moi ici select category.name form category where id=
        $article = $articlesRepository->find($id);
        //la fonction render me permet d'envoyer a twig les infos qui seront affichés
        return $this->render("Front/article.html.twig", [
            'article' => $article
        ]);

    }
}