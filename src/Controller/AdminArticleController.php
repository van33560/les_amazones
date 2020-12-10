<?php


namespace App\Controller;


use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;


class AdminArticleController extends AbstractController
{
    /**
     * @Route("admin/article/articles", name="admin_articlelist")
     * @param ArticleRepository $articleRepository
     * @return \Symfony\Component\HttpFoundation\Response
     */
    //ma methode acticle repository me permet de recuperer via la bdd les données et de les afficher avec return render
    public function ArticleList(ArticleRepository $articleRepository)
    {    //find all est une methode qui permet de recuperer tous les articles
        //doctrine effectue la requete pour moi ici select*from article
        $articles = $articleRepository->findAll();
        //la fonction render me permet d'envoyer a twig les infos qui seront affichés
        return $this->render("article/admin/articles.html.twig",[
            'articles' => $articles
        ]);
    }

    // chemin de ma route avec id
    /**
     * @route("/article/show/{id}",name="articleShow")
     * @param $id
     * @param ArticleRepository $articleRepository
     * @return \Symfony\Component\HttpFoundation\Response
     */
    // ma methode articlerepository me permet de recuperer les données de ma bdd et de retourner un resultat via la propriete render
    public function articleShow($id, ArticleRepository $articleRepository)
    {   //find($id) est une methode qui permet de recuperer une category via son id
        //doctrine effectue la requete pour moi ici select category.name form category where id=
        $article = $articleRepository->find($id);
        //la fonction render me permet d'envoyer a twig les infos qui seront affichés
        return $this->render("article/admin/article.html.twig", [
            'article' => $article
        ]);

    }
    /**
     * @route("admin/article/update/{id}",name="admin_article_update")
     * @param $id
     * @param ArticleRepository $articleRepository
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param SluggerInterface $slugger
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
//je crée une methode updateArticle pour modifier le contenu du formulaire je lui passe en parametre id pour pouvoir
//  modifier un article grace a son id,la prropriété repository me permettra de modifier les données de la bdd et
// la propriéte request me permettra de recuperer les modification
    public function updateArticle($id, ArticleRepository $articleRepository,Request $request, EntityManagerInterface $entityManager,SluggerInterface $slugger)
{
        //je récupére en bdd  l'id wild card qui correspond a celui renseigner dans url
        $article = $articleRepository -> find($id);

        if(is_null($article))
        {
            return $this->redirectToRoute('admin_articlelist');
        }
        //je crée un formulaire grâce à la fonction createFrom et je passe en paramétre le chemin vers le fichierArticleType
        $form = $this -> createForm(ArticleType::class,$article);

        //avec la methode handle de la class form je récupère les données en post
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            // je récupère le fichier uploadé dans le formulaire
            //je recupere le contenu du champ imageFileName
            $articlePicture = $form->get('articlePicture')->getData();

            if ($articlePicture) {
                //je recupere le nom d'origine de l'image
                $originalFilename = pathinfo($articlePicture->getClientOriginalName(), PATHINFO_FILENAME);
                // grâce à la classe Slugger, je change le nom de mon image
                // et pour sortir tous les caractères spéciaux
                $safeFilename = $slugger->slug($originalFilename);

                $newFilename = $safeFilename . '-' . uniqid() . '.' . $articlePicture->guessExtension();
                //je déplace l'image dans un dossier temporaire services.yaml ou je precise  en parametre son nom
                $articlePicture->move(
                    $this->getParameter('images_directory'),//recuperer envoyer les données vers parametres
                    $newFilename
                );
                $article->setarticlePicture($newFilename);

            }
            //je fait une contidion si mon formulaire et envoyer et valide alors je pré-sauvegarde
            //avec la fonction persist et j'insere avec la fonction flush
            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager->persist($article);
                $entityManager->flush();

                //j'ajoute un message de type flash qui s'affichera  la suppression de l'article
                $this->addFlash(
                    "sucess",
                    "l'article a été modifié"
                );

            }
            //je crée grâce à la fonction createview une vue qui pourra  en suite être lu par twig
            $formView = $form->createView();
            //la fonction render me permet d'envoyer a twig les infos qui seront affichés
            return $this->render('article/admin/article_update.html.twig', [
                'formView' => $formView
            ]);


       }
   }
}


