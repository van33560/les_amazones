<?php


namespace App\Controller;


use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
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
     //ma methode acticle repository me permet de récuperer via la bdd les données et de les affichés avec méthode render
     public function ArticleList(ArticleRepository $articleRepository)
     {      //find all est une methode qui permet de récuperer tous les articles
            //doctrine éffectue la requête pour moi ici select*from article
            $articles = $articleRepository->findAll();
                //la fonction render me permet d'envoyer à twig les infos qui seront affichés
                return $this->render("Front/articles.html.twig",[
                'articles' => $articles
               ]);
     }
    /**
     * @Route ("admin/article/insert",name="admin_article_insert")
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param SluggerInterface $slugger
     * @return RedirectResponse|Response
     */

    //je crée une methode pour créer un formulaire avec la function insertArticle en paramétre
    //  la méthode request pour récuperer les infos passées en post, get dans l'url
    // avec entitymanager via ses classes je pre-sauvegarde et envoi grace a (persist,flush,move)
    // je crée un nouvel objet, la méthode slugg me permet de changer
    // le nom de mon image et gérer les caractères spéciaux
    //les methodes en parametre on demande l'instanciation a sf
     public function insertArticle(Request $request, EntityManagerInterface $entityManager,
                                      SluggerInterface $slugger)
     {
        // je crée un nouvel objet
        $article = new Article();
        //je crée un formulaire grâce à la function createFrom et je passe en paramétre le chemin vers
        // le fichierArticleType, lie au gabarit de mon formulaire  Entité Article
        $form = $this->createForm(ArticleType::class, $article);
        //avec la classe handle de la methode request je récupère les données en post
        $form->handleRequest($request);

         if($form->isSubmitted() && $form->isValid())
        {
            //je crée une variable illustration que je recupere dans mon formulaire
            $illustration = $form->get('illustration')->getData();

                if($illustration){
                    //je recupere, l'image uploade par le client (origine) le chemin pour la stocker ds dossier temporaire $illustration c'est l'image
                    $originalFilename = pathinfo($illustration->getClientOriginalName(), PATHINFO_FILENAME);
                    // grâce à la classe Slugger, je change le nom de mon image
                    // et je sort tous les caractères spéciaux grace à la methode slug
                    $safeFilename = $slugger->slug($originalFilename);
                    //je stock ce slug dans une variable newimage nouvelle extention unique avec le methode uniqid
                    $newFilename = $safeFilename.'-'.uniqid().'.'.$illustration->guessExtension();
                            //l'image par defaut est pres sauvgarder dans mon fichier
                            // services.yaml que j'appel images_directory je recupere ce fichier que
                            //grace à la methode move
                            $illustration->move(
                                $this->getParameter('images_directory'),
                                $newFilename
                            );
                            //je recupere l'image modifiée contenu dans ma variable article
                    $article->setIllustration($newFilename);
                    }

            // si mon formulaire et envoyer et valide alors je pré-sauvegarde avec la fonction persist
            $entityManager->persist($article);
            // j'envoi en BDD avec la fonction flush
            $entityManager->flush();
                    //la methode addflash me permet d'afficher un message de confirmation de creation d'article
                    // via un fichier twig
                    $this->addFlash(
                        "success",
                        "l'article a été ajouté"
                    );
            return $this->redirectToRoute('Front_articlelist');
        }
        //je crée grâce à la fonction createview une vue qui pourra être lu par twig
         $formView = $form-> createView();
            //la fonction render me permet de renvoyer vers mon fichier a twig mon formulaire
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
     * @return RedirectResponse|Response
     */
    //je crée une methode updateArticle pour modifier le contenu du formulaire je lui passe en parametre id pour pouvoir
    //  modifier un article grace a son id,la propriété repository me permet de modifier les données de la bdd et
    // la propriéte request me permet de récuperer les modifications
    // entitymanager via ces classes de gerer la pre-sauvegarde et l'envoi des  (persist et flush)
    public function updateArticle($id, ArticleRepository $articleRepository,Request $request, EntityManagerInterface $entityManager)
    {
        //je récupére en bdd  l'id qui correspond a celui renseigner dans url
        $article = $articleRepository -> find($id);

            if(is_null($article)){
                //throw $this->createNotFoundException('article non trouvée');
                return $this->redirectToRoute('Front_articlelist');
            }

        //je crée un formulaire grâce à la fonction createFrom et je passe en paramétre le chemin vers
        // le fichierArticleType grace a :: class
        $form = $this->createForm(ArticleType::class, $article);
        //avec la methode handleRequest de la class form je récupère les données en post
        $form->handleRequest($request);
        //je fait une contidion si mon formulaire et envoyer et valide alors je pré-sauvegarde
        //avec la function persist et j'envoi les données avec la function flush
             if($form->isSubmitted() && $form->isValid()){

                        $entityManager->persist($article);
                        $entityManager->flush();

                            //j'ajoute un message de type flash qui s'affichera  la suppression de l'article
                            //et qui renvoi une vue vers mon formulaire fichier twig
                            $this->addFlash(
                                "success",
                                "l'article a été modifié"
                            );
                        return $this->redirectToRoute('Front_articlelist');
                   }
            //je crée grâce à la function createview je crée une vue qui pourra en suite être lu par twig
            $formView = $form-> createView();
            //la fonction render me permet de renvoyer vers mon fichier twig mon formulaire de modification
                return $this->render('Article/Admin/article_update.html.twig',[
                    'formView' => $formView,
                    'article' => $article
                ]);
    }

    /**
     * @route("Admin/Article/delete/{id}",name="admin_article_delete")
     * @param $id
     * @param ArticleRepository $articleRepository
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
     public function deletearticle($id,ArticleRepository $articleRepository,EntityManagerInterface $entityManager)
     {
        //je récupére en bdd l'id passer dans url
        $article = $articleRepository->find($id);
        //je fait une condition qui va permet de verifier si il y a un temoignage et un fois le temoignage
        // et son id supprimé
        if(!is_null($article)){
            //throw est une instruction qui permet via sa methode createnotfoundexception d'afficher une erreur type 404
            //throw $this->createNotFoundException('article non trouvée');
            //entitymanager avec le methode remove efface l'article dont l'id est renseigné dans url
            $entityManager->remove($article);
            //la fonction flush renvoi les nouvelles modifs
            $entityManager->flush();
            //la méthode addflash me permet d'afficher un message qui s'affichera a la suppression du temoignage

        } else {

           return $this->render('Front/delete_confirmation.html.twig');
        }
                 $this->addFlash(
                     "success",
                     "l'article a été supprimé"
                 );
        // la fonction redirectToRoute permet de retrouner un visuel via le name de mon fichier 'articlelist'

         return $this->redirectToRoute('Front_articlelist');
     }



}


