<?php


namespace App\Controller;


use App\Form\searchType;
use App\Repository\ArticleRepository;


use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ArticleController extends AbstractController
{
    /**
     * @Route("Front/article/articles", name="Front_articlelist")
     * @param ArticleRepository $articlesRepository
     * @param Request $request
     * @return Response
     */
    //je créer une function qui me permet de récuperer mes articles
    public function Articlelist(ArticleRepository $articlesRepository,Request $request)
        {
           // $search = $request->query->get('search');

            //if(!is_null($search)){
                //$articles = $articlesRepository->searchInTitle($search);

            //}
            //find all est une méthode qui permet de récuperer tous les articles
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


    /**
     * @Route("/recherche", name="search")
     * @param Request $request
     * @param ArticleRepository $repo
     * @param PaginatorInterface $paginator
     * @return Response
     */
    public function recherche(Request $request, ArticleRepository $repo, PaginatorInterface $paginator) {

        $form = $this->createForm(SearchType::class);
        $form->handleRequest($request);

        $donnees = $repo->findAll();

        if ($form->isSubmitted() && $form->isValid()) {
            $title = $form->getData()->getTitle();
            $donnees = $repo->search($title);
            if ($donnees == null) {
                $this->addFlash('erreur', 'Aucun article contenant ce mot clé dans le titre n\'a été trouvé, essayez en un autre.');

            }

        }       // Paginate the results of the query
                $articles = $paginator->paginate(
                // Doctrine Query, not results
                    $donnees,
                    // Define the page parameter
                    $request->query->getInt('page', 1),
                    // Items per page
                    4
                );
            $formView = $form-> createView();
                 return $this->render('search.html.twig',[
                    'articles' => $articles,
                    'formView' => $formView
                ]);
        return $this->render("search.html.twigch.html.twig", [
            'article' => $article
        ]);
            }

}