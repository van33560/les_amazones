<?php


namespace App\Controller;



use App\Entity\Testimony;

use App\Form\TestimonyType;
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
 * @Route("admin/testimony/testimonys", name="admin_testimony_list")
 * @param TestimonyRepository $testimonyRepository
 * @return Response
 */
 //ma methode testimonyrepository me permet de recuperer via la bdd les données et de les afficher
// via mon fichier twig et la propriete  render
public function TestimonyList(TestimonyRepository $testimonyRepository)
    {    //find all est une methode qui permet de recuperer tous les temoignages
        //doctrine effectue la requete pour moi ici select*from testimony
        $testimonys = $testimonyRepository->findAll();
        //la methode render me permet d'envoyer a twig les infos qui seront affichés
        return $this->render("Testimony/admin/testimonys.html.twig",[
            'testimonys' => $testimonys
        ]);
    }
/**
 * @route("admin/testimony/show/{id}",name="testimonyShow")
 * @param $id
 * @param TestimonyRepository $testimonyRepository
 * @return Response
 */
// ma methode testimonyrepository me permet de recuperer les données de ma bdd et de retourner un resultat via la propriete render
public function testimonyShow($id, TestimonyRepository $testimonyRepository)
    {
        $testimony = $testimonyRepository->find($id);

        return $this->render("Front/testimony.html.twig", [
            'testimony' => $testimony
        ]);

    }
/**
 * @route("admin/testimony/insert",name="admin_testimony_insert")
 * @param Request $request
 * @param EntityManagerInterface $entityManager
 * @return RedirectResponse|Response
 */

//je crée une methode pour créer un formulaire avec la methode inserttestimony en parametre
// methode request pour recuperer les infos post, get dans l'url
// entitymanager gerer les entités les champs de ma bdd
public function insertTestimony(Request $request , EntityManagerInterface $entityManager,SluggerInterface $slugger)
    {   //j' indique a sf que je crée un nouvelle objet
        $testimony = new Testimony();
        //autowire fait le liens entre les fichiers dependance
        //je crée un formulaire grâce à la fonction createFrom et je passe en paramétre le chemin vers le fichierArticleType
        $form = $this->createForm(TestimonyType::class, $testimony);
        //avec la methode handle de la class form je récupère les données en post
        $form->handleRequest($request); //autowire fait le liens entre les fichiers dependance
            //je fait une contidion si mon formulaire et envoyer et valide alors je pré-sauvegarde
            //avec la fonction persist

        if($form->isSubmitted() && $form->isValid()){
            // je récupère le fichier uploadé dans le formulaire
            //je recupere le contenu du champ imageFileName
            //je fait une contidion si mon formulaire et envoyer et valide alors je pré-sauvegarde
            //avec la fonction persist
            $picture=$form->get('picture')->getData();

            if($picture){
                //je recupere le nom d'origine de l'image
                $originalFilename = pathinfo($picture->getClientOriginalName(), PATHINFO_FILENAME);
                // grâce à la classe Slugger, je change le nom de mon image
                // et pour sortir tous les caractères spéciaux
                $safeFilename = $slugger->slug($originalFilename);

                $newFilename = $safeFilename.'-'.uniqid().'.'.$picture->guessExtension();
                //je déplace l'image dans un dossier temporaire services.yaml ou je precise  en parametre son nom
                $picture->move(
                    $this->getParameter('picture_directory'),//recuperer envoyer les données vers parametres
                    $newFilename
                );
                $testimony->setPicture($newFilename);
            }
            $entityManager->persist($testimony);
            // j'insere avec la fonction flush
            $entityManager->flush();
            $this->addFlash(
                "sucess",
                "le témoignage a été ajouté"
            );
             return $this->redirectToRoute('admin_testimony_list');

            }
        //je crée grâce à la fonction createview une vue qui pourra  en suite être lu par twig
        $formView = $form-> createView();
        //la fonction render me permet d'envoyer a twig les infos qui seront affichés
            return $this->render('Testimony/Admin/insert_testimony.html.twig',[
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
//je crée une methode updatetestimony pour modifier le contenu du formulaire je lui passe en parametre id pour pouvoir
//  modifier le temoignage grace a son id,la prropriété repository me permettra de modifier les données de la bdd et
// la propriéte request me permettra de recuperer les modification
public function updatetesimony($id, TestimonyRepository $testimonyRepository,Request $request, EntityManagerInterface $entityManager)
    {

        //je récupére en bdd  l'id wild card qui correspond a celui renseigner dans url
        $testimony = $testimonyRepository -> find($id);

        if(is_null($testimony)){
            return $this->redirectToRoute('admin_testimony_list');
        }
        //je crée un formulaire grâce à la fonction createFrom et je passe en paramétre le chemin vers le fichiertestimonyType
        $form = $this -> createForm(TestimonyType::class,$testimony);

        //avec la methode handle de la class form je récupère les données en post
        $form->handleRequest($request);
        //je fait une contidion si mon formulaire et envoyer et valide alors je pré-sauvegarde
        //avec la fonction persist et j'insere avec la fonction flush
        if($form->isSubmitted() && $form->isValid()){
            $entityManager->persist($testimony);
            $entityManager->flush();
            //j'ajoute un message de type flash qui s'affichera  la modification du temoignage
            $this->addFlash(
                "sucess",
                "le temoignage a été modifié"
            );

            return $this->redirectToRoute('admin_testimony_list');

        }
        //je crée grâce à la fonction createview une vue qui pourra  en suite être lu par twig
        $formView = $form-> createView();
        //la methode render me permet d'envoyer a twig les infos qui seront affichés
        return $this->render('Testimony/Admin/update_testimony.html.twig',[
            'formView' => $formView
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
//, sf effectuera la requete delete et entityManagerinterface,(me permettra de faire des réquetes ici delete)
// ces classes seront intanciés grace a entitymanager qui le gerera à ma place
public function deleteTestimony($id,TestimonyRepository $testimonyRepository,EntityManagerInterface $entityManager)
    {
        //je récupére en bdd  l'id wild card qui correspond a celui renseigner dans url
        $testimony = $testimonyRepository->find($id);
        //je fait une condition qui va permettra de verifier si il y a un temoignage et un fois le temoignage
        // et son id supprimé la methode add flash affichera un message d'erreur
        if(!is_null($testimony)){
            //entitymanager avec le methode remove effacera l'article dont l'id est renseigné dans url
            $entityManager->remove($testimony);
            //la fonction flush insere les nouvelles modifs
            $entityManager->flush();
            //j'ajoute un message de type flash qui s'affichera a la suppression du temoignage
            $this->addFlash(
                "success",
                "le temoignage a été supprimé"
            );

        }
             // la fonction redirecttoroute permet de retrouner un visuel via le name de mon fichier 'les temoignages'
            return $this->redirectToRoute('admin_testimony_list');

    }



}