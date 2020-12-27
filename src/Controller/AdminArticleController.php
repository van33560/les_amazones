<?php


namespace App\Controller;


use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;


class AdminArticleController extends AbstractController
{
    /**
     * @Route("Article/Admin/articles", name="admin_articleList")
     * @param ArticleRepository $articleRepository
     * @return Response
     */
//ma methode acticle repository me permet de recuperer via la bdd les données et de les afficher avec return render
public function ArticleList(ArticleRepository $articleRepository)
{       //find all est une methode qui permet de recuperer tous les articles
        //doctrine effectue la requete pour moi ici select*from article
        $articles = $articleRepository->findAll();
        //la fonction render me permet d'envoyer a twig les infos qui seront affichés
        return $this->render("Article/Admin/articles.html.twig",[
        'articles' => $articles
    ]);
}
    /**
     * @route ("admin/article/insert",name="admin_article_insert")
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param SluggerInterface $slugger
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */

        //je crée une methode pour créer un formulaire avec la methode insertArticle en parametre methode request pour recuperer
        //les infos post get url entitymanager gerer les entités creation  d'un nouvel objet Slugger, je change le nom de mon image
        // et pour sortir tous les caractères spéciaux
 public function insertArticle(Request $request, EntityManagerInterface $entityManager,SluggerInterface $slugger)
{       //j' indique a sf que je crée un nouvelle objet
        $article = new Article();

        //je crée un formulaire grâce à la fonction createFrom et je passe en paramétre le chemin vers le fichierArticleType
        // Je lie mon gabarit de mon formulaire à mon Entité Article grâce à la méthode createForm
        $form = $this->createForm(ArticleType::class, $article);
        //avec la methode handle de la class form je récupère les données en post
        $form->handleRequest($request); //autowire fait le liens entre les fichiers dependance

        if($form->isSubmitted() && $form->isValid())
        {
            $illustration=$form->get('illustration')->getData();

            if($illustration){
                //je recupere le nom d'origine de l'image
                $originalFilename = pathinfo($illustration->getClientOriginalName(), PATHINFO_FILENAME);
                // grâce à la classe Slugger, je change le nom de mon image
                // et pour sortir tous les caractères spéciaux
                $safeFilename = $slugger->slug($originalFilename);

                $newFilename = $safeFilename.'-'.uniqid().'.'.$illustration->guessExtension();
                //je déplace l'image dans un dossier temporaire services.yaml ou je precise  en parametre son nom
                $illustration->move(
                    $this->getParameter('images_directory'),//recuperer envoyer les données vers parametres
                    $newFilename
                );
                $article->setIllustration($newFilename);
            }

            // je récupère le fichier uploadé dans le formulaire
            //je recupere le contenu du champ imageFileName
            //je fait une contidion si mon formulaire et envoyer et valide alors je pré-sauvegarde
            //avec la fonction persist
            $entityManager->persist($article);
            // j'insere avec la fonction flush
            $entityManager->flush();

            $this->addFlash(
                "sucess",
                "l'article a ete ajouté"
            );
            return $this->redirectToRoute('admin_articleList');
        }
            //je crée grâce à la fonction createview une vue qui pourra  en suite être lu par twig
            $formView = $form-> createView();

            //la fonction render me permet d'envoyer a twig les infos qui seront affichés
            return $this->render('Article/Admin/article_insert.html.twig',[
            'formView' => $formView
        ]);


}
    /**
     * @route("admin/article/update/{id}",name="admin_article_update")
     * @param $id
     * @param ArticleRepository $articleRepository
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
//je crée une methode updateArticle pour modifier le contenu du formulaire je lui passe en parametre id pour pouvoir
//  modifier un article grace a son id,la prropriété repository me permettra de modifier les données de la bdd et
// la propriéte request me permettra de recuperer les modification
    public function updateArticle($id, ArticleRepository $articleRepository,Request $request, EntityManagerInterface $entityManager)
    {
        //je récupére en bdd  l'id wild card qui correspond a celui renseigner dans url
        $article = $articleRepository -> find($id);

        if(is_null($article)){
            return $this->redirectToRoute('admin_articleList');
        }

        //je crée un formulaire grâce à la fonction createFrom et je passe en paramétre le chemin vers le fichierArticleType
        $form = $this->createForm(ArticleType::class, $article);

        //avec la methode handle de la class form je récupère les données en post
        $form->handleRequest($request);
        //je fait une contidion si mon formulaire et envoyer et valide alors je pré-sauvegarde
        //avec la fonction persist et j'insere avec la fonction flush

        if($form->isSubmitted() && $form->isValid()){
            $entityManager->persist($article);
            $entityManager->flush();

            //j'ajoute un message de type flash qui s'affichera  la suppression de l'article
            $this->addFlash(
                "sucess",
                "l'article a été modifié"
            );

        }
        //je crée grâce à la fonction createview une vue qui pourra  en suite être lu par twig
        $formView = $form-> createView();
        //la fonction render me permet d'envoyer a twig les infos qui seront affichés
        return $this->render('Article/Admin/article_update.html.twig',[
            'formView' => $formView
        ]);
    }

    /**
     * @route("Admin/Article/delete/{id}",name="admin_article_delete")
     * @param $id
     * @param ArticleRepository $articleRepository
     * @param EntityManagerInterface $entityManager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deletearticle($id,ArticleRepository $articleRepository,EntityManagerInterface $entityManager)
    {
        //je récupére en bdd  l'id wild card qui correspond a celui renseigner dans url
        $article = $articleRepository->find($id);
        //je fait une condition qui va permettra de verifier si il y a un temoignage et un fois le temoignage
        // et son id supprimé la methode add flash affichera un message d'erreur
        if(!is_null($article)){
            //entitymanager avec le methode remove effacera l'article dont l'id est renseigné dans url
            $entityManager->remove($article);
            //la fonction flush insere les nouvelles modifs
            $entityManager->flush();
            //j'ajoute un message de type flash qui s'affichera a la suppression du temoignage
            $this->addFlash(
                "success",
                "l'article a été supprimé"
            );

        }
        // la fonction redirecttoroute permet de retrouner un visuel via le name de mon fichier 'articlelist'
        return $this->redirectToRoute('admin_articleList');

    }

}


