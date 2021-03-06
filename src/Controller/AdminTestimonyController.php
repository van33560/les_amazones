<?php


namespace App\Controller;



use App\Entity\Testimony;

use App\Form\TestimonyType;
use Symfony\Component\HttpFoundation\File\File;
use App\Repository\TestimonyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class AdminTestimonyController extends AbstractController
{
    /**
    * @Route("Admin/Admin_testimony", name="home_testimony")
    * @param TestimonyRepository $testimonyRepository
    * @return Response
    */
    public function indexTestimony(TestimonyRepository $testimonyRepository)
    {
        $testimonys = $testimonyRepository->findAll();
            return $this->render('Admin/Admin_testimony.html.twig', [
                'testimonys' => $testimonys
            ]);

    }

    /**
    * @Route("admin/testimony/testimonys", name="admin_testimony_list")
    * @param TestimonyRepository $testimonyRepository
    * @return Response
    */
    //ma methode testimonyrepository me permet de récuperer via la bdd les données et de les afficher
    // via mon fichier twig et la propriete  render qui renvoi vers la route de mon fichier twig
    public function TestimonyList(TestimonyRepository $testimonyRepository)
    {    //find all est une methode qui permet de récuperer tous les temoignages
        //doctrine effectue la requete pour moi ici select*from testimony
        $testimonys = $testimonyRepository->findAll();
        //la methode render me permet d'envoyer vers mon fichier twig
            return $this->render("Front/testimonys.html.twig",[
                'testimonys' => $testimonys
            ]);
    }

    /**
    * @route("admin/testimony/insert",name="admin_testimony_insert")
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
                         return $this->redirectToRoute('Front_testimony_list');
             }
             //je crée grâce à la fonction createview une vue qui sera lu par twig
             $formView = $form-> createView();
             //la fonction render me permet de renvoyer vers mon fichier twig via sa route
                 return $this->render('Front/testimonys.html.twig',[
                     'formView' => $formView
                ]);
    }

    /**
    * @route("admin/testimony/update/{id}",name="admin_testimony_update")
    * @param $id
    * @param TestimonyRepository $testimonyRepository
    * @param Request $request
    * @param EntityManagerInterface $entityManager
    * @return RedirectResponse|Response
    */
    // je crée une methode updatetestimony pour modifier le contenu du formulaire je lui passe en parametre
    // id pour pouvoir modifier le temoignage grace a son id
    // la propriété repository me permettra de modifier les données de la bdd et
    // la propriéte request me permettra de recuperer les modifications
    public function updatetesimony($id, TestimonyRepository $testimonyRepository, Request $request, EntityManagerInterface $entityManager)
    {
        //je récupére en bdd le temoignage et son id
        $testimony = $testimonyRepository -> find($id);

        if(is_null($testimony)){
            return $this->redirectToRoute('admin_testimony_list');
        }

        //je crée un formulaire grâce à la fonction createFrom et je passe en paramétre le chemin grace :: class
        //qui renvois vers le fichier testimonyType
        $form = $this -> createForm(TestimonyType::class,$testimony);
        //avec la methode handle de la class form je récupère les données en post
        $form->handleRequest($request);
        //je fait une contidion si mon formulaire et envoyer et valide alors je pré-sauvegarde
        //avec la methode persist et j'insere renvoi avec la methode flush
        if($form->isSubmitted() && $form->isValid()){
            $entityManager->persist($testimony);
            $entityManager->flush();

            //je crée un message grace a la methode addflash qui s'affichera à la modification du témoignage
                $this->addFlash(
                    "success",
                    "le temoignage a été modifié"
                );

            return $this->redirectToRoute('Front_testimony_list');

        }
        //je crée grâce à la fonction createview une vue qui sera lu par twig
        $formView = $form-> createView();
        //la methode render me permet de renvoyer vers mon fichier twig via sa route
            return $this->render('Testimony/Admin/update_testimony.html.twig',[
                'formView' => $formView,
                'testimony' => $testimony
            ]);
    }
    /**
    * @route("admin/testimony/delete/{id}",name="admin_testimony_delete")
    * @param $id
    * @param TestimonyRepository $testimonyRepository
    * @param EntityManagerInterface $entityManager
    * @return RedirectResponse
    */
    //je crée une methode deletetestimony qui aura pour paramétres $id(me permettra de recuperer le temoignage),
    //testimonyrepository(qui me permettra de récuperer les données des champs de la base de données)
    //, sf effectuera la requete delete et entityManagerinterface,me permettra de remove(deplacer)
    // flush(pre-sauvegarder) persist (envoyer)
    public function deleteTestimony($id,TestimonyRepository $testimonyRepository,
                                EntityManagerInterface $entityManager)
    {
        //je récupére en bdd  l'id du temoignage passer dans url
        $testimony = $testimonyRepository->find($id);
        //je fait une condition qui va permettra de verifier si il y a un temoignage et un fois le temoignage
        // et son id supprimé la methode add flash affichera un message de confirmation
        if(!is_null($testimony)){
            //entitymanager avec le methode remove effacera l'article dont l'id est renseigné dans url
            $entityManager->remove($testimony);
            //la fonction flush insere renvoi les nouvelles modifs
            $entityManager->flush();
            //je crée un message grace a la methode addflash qui s'affichera a la suppression du temoignage
                $this->addFlash(
                    "success",
                    "le temoignage a été supprimé"
                );

        }
             // la fonction redirectToRoute me permet de retrouner vers un visuel
             // via le name de mon fichier 'les temoignages'
            return $this->redirectToRoute('Front_testimony_list');

    }



    }