<?php


namespace App\Controller;


use App\Entity\Article;
use App\Entity\Testimony;
use App\Entity\User;
use App\Form\ArticleType;
use App\Form\EditUserType;
use App\Form\TestimonyType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\String\Slugger\SluggerInterface;

class Utilisateurs extends AbstractController
{
    /**
     * @Route  ("/utilisateurs", name="utilisateurs")
     * @param UserRepository $users
     * @return Response
     */
    public function usersList(UserRepository $users)
    {
        return $this->render('Admin/users.html.twig', [
            'users' => $users->findAll(),
        ]);
    }

    /**
     * @route ("/utilisateurs/modifier/{id}", name="modifier_utilisateur")
     * @param User $user
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function editUser(User $user,Request $request)
    {
        $form = $this->createForm(EditUserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

                $this->addFlash('message', 'Utilisateur modifié avec succès');
                return $this->redirectToRoute('utilisateurs');
            }

        return $this->render('Admin/edituser.html.twig', [
            'userForm' => $form->createView(),
        ]);
    }
    /**
     * @route ("Front/utilisateurs/insert/article",name="Front_utilisateurs_insert_article")
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param SluggerInterface $slugger
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */

    //je crée une methode pour créer un formulaire avec la function insertArticle en paramétre la méthode
    // request pour récuperer les infos post get url
    // je crée un nouvel objet, la méthode slugg me permet de change
    // le nom de mon image et gérer les caractères spéciaux
    public function insertArticle(Request $request, EntityManagerInterface $entityManager,
                                  SluggerInterface $slugger)
    {
        //j'indique a sf que je crée un nouvelle objet
        $article = new Article();
        //je crée un formulaire grâce à la function createFrom et je passe en paramétre le chemin vers
        // le fichierArticleType Je lie mon gabarit de mon formulaire à mon Entité Article
        $form = $this->createForm(ArticleType::class, $article);
        //avec la methode handle de la class form je récupère les données en post
        $form->handleRequest($request);

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
                //je déplace grace a la méthode move l'image dans un dossier temporaire dans le fichier
                // services.yaml
                // ou je précise en parametre son nom
                $illustration->move(
                    $this->getParameter('images_directory'),
                    $newFilename
                );

                $article->setIllustration($newFilename);
            }

            // je récupère le fichier uploadé dans le formulaire
            //je récupere le contenu du champ imageFileName
            //je fait une contidion si mon formulaire et envoyer et valide alors je pré-sauvegarde
            //avec la fonction persist
            $entityManager->persist($article);
            // j'envoi en BDD avec la fonction flush
            $entityManager->flush();
            //la methode addflash me permet d'afficher un message via un fichier twig
            $this->addFlash(
                "success",
                "l'article a été ajouté"
            );
            return $this->redirectToRoute('admin_articleList');
        }
        //je crée grâce à la fonction createview une vue qui pourra être lu par twig
        $formView = $form-> createView();
        //la fonction render me permet de renvoyer vers mon fichier a twig mon formulaire
        return $this->render('Front/utilisateurs_insert_article.html.twig',[
            'formView' => $formView
        ]);


    }
    /**
     * @route("Front/utilisateurs/insert/testimony",name="Front_utilisateurs_insert_testimony")
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return RedirectResponse|Response
     */
    //je crée une methode pour créer un formulaire avec la methode inserttestimony, en parametre je lui passe la
    // methode request pour recuperer les infos post, get dans l'url
    // entitymanager qui gere grace a ces classes persit flush et remove la gestion des infos
    public function insertTestimony(Request $request , EntityManagerInterface $entityManager,SluggerInterface $slugger)
    {   // je crée un nouvelle objet
        $testimony = new Testimony();
        //autowire fait le liens entre les fichiers, dépendances
        //je crée un formulaire grâce à la fonction createFrom et je passe en paramétre grace a :: class
        // le chemin vers le fichier testimonyType
        $form = $this->createForm(TestimonyType::class, $testimony);
        //avec la methode handle de la class form je récupère les données en post
        $form->handleRequest($request);
        //je fait une contidion si mon formulaire et envoyer et valide alors je pré-sauvegarde
        //avec la fonction persist plus bas
        if($form->isSubmitted() && $form->isValid()) {
            // je récupère le fichier uploadé dans le formulaire
            //je recupere le contenu du champ imageFileName
            $picture=$form->get('picture')->getData();

            if($picture) {
                //je recupere le nom d'origine de l'image
                $originalFilename = pathinfo($picture->getClientOriginalName(), PATHINFO_FILENAME);
                // grâce à la classe Slugger, je change le nom de mon image
                // et je sort tous les caractères spéciaux
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$picture->guessExtension();
                //je déplace l'image dans un fichier temporaire dans services.yaml ou je precise en parametre son nom
                //la methode remove me permet de déplacer le fichier
                $picture->move(
                    $this->getParameter('picture_directory'), $newFilename
                //je recupere, envoyer les données vers les parametres de mon fichier services
                );
                $testimony->setPicture($newFilename);
            }
            $entityManager->persist($testimony);
            $entityManager->flush();
            //la methode addflash me permet d'afficher un message de confirmation
            $this->addFlash(
                "success",
                "le témoignage a été ajouté"
            );
                 return $this->redirectToRoute('admin_testimony_list');
        }
        //je crée grâce à la fonction createview une vue qui sera lu par twig
        $formView = $form-> createView();
        //la fonction render me permet de renvoyer vers mon fichier twig via sa route
                 return $this->render('Front/utilisateurs_insert_testimony.html.twig',[
                     'formView' => $formView
                ]);
    }
}