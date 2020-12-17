<?php


namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
class AdminCategoryController extends AbstractController
{    // chemin de ma route et son name
/**
 * @Route("category/admin/categorys", name="admin_categorylist")
 * @param CategoryRepository $categoryRepository
 * @return Response;
 */
//ma methode category repository me permet de recuperer via la bdd les données et de renvoyer vers twig
// pour les afficher un resultat vie la propriete  render
    public function CategoryList(CategoryRepository $categoryRepository)
    {    //find all est une methode qui permet de recuperer tous les temoignages des champs de ma bdd
        //doctrine effectue la requete pour moi ici select*from category
        $categorys = $categoryRepository->findAll();
        //la methode render me permet d'envoyer a twig les infos qui seront affichés
        return $this->render("Category/Admin/categorys.html.twig",[
            'categorys' => $categorys
        ]);

    }

    /**
     * @route("category/admin/category/insert",name="admin_category_insert")
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return RedirectResponse|Response
     */

    //je crée une methode pour créer un formulaire avec la methode insertArticle
public function insertCategory(Request $request, EntityManagerInterface $entityManager)
    {   //j' indique a sf que je crée un nouvelle objet
        $category = new Category();

        //je crée un formulaire grâce à la fonction createFrom et je passe en paramétre le chemin vers le fichierArticleType
        $form = $this->createForm(CategoryType::class, $category);
        //avec la methode handle de la class form je récupère les données en post
        $form->handleRequest($request);


        //je fait une contidion si mon formulaire et envoyer et valide alors je pré-sauvegarde
        //avec la fonction persist et j'insere avec la fonction flush
        if($form->isSubmitted() && $form->isValid()){

            $entityManager->persist($category);
            $entityManager->flush();

            $this->addFlash(
                "sucess",
                "la category a ete ajouté"
            );
            return $this->redirectToRoute('admin_categorylist');
        }
        //je crée grâce à la fonction createview une vue qui pourra  en suite être lu par twig
        $formView = $form-> createView();
        //la fonction render me permet d'envoyer a twig les infos qui seront affichés
            return $this->render('Category/Admin/category_insert.html.twig',[
            'formView' => $formView
        ]);

    }
    /**
     * @route("admin/category/update/{id}",name="admin_category_update")
     * @param $id
     * @param CategoryRepository $categoryRepository
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return RedirectResponse|Response
     */
    //je crée une methode updateArticle pour modifier le contenu du formulaire je lui passe en parametre id pour pouvoir
    //  modifier un article grace a son id,la prropriété repository me permettra de modifier les données de la bdd et
    // la propriéte request me permettra de recuperer les modification
    public function updateCategory($id, CategoryRepository $categoryRepository,Request $request, EntityManagerInterface $entityManager)
    {
        //je récupére en bdd  l'id wild card qui correspond a celui renseigner dans url
        $category = $categoryRepository -> find($id);

        if(is_null($category)){
            return $this->redirectToRoute('admin_categorylist');
        }
        //je crée un formulaire grâce à la fonction createFrom et je passe en paramétre le chemin vers le fichierArticleType
        $form = $this -> createForm(CategoryType::class,$category);

        //avec la methode handle de la class form je récupère les données en post
        $form->handleRequest($request);
        //je fait une contidion si mon formulaire et envoyer et valide alors je pré-sauvegarde
        //avec la fonction persist et j'insere avec la fonction flush
        if($form->isSubmitted() && $form->isValid()){
            $entityManager->persist($category);
            $entityManager->flush();

            //j'ajoute un message de type flash qui s'affichera  la suppression de l'article
            $this->addFlash(
                "sucess",
                "la category a été modifié"
            );

        }
        //je crée grâce à la fonction createview une vue qui pourra  en suite être lu par twig
        $formView = $form-> createView();
        //la fonction render me permet d'envoyer a twig les infos qui seront affichés
        return $this->render('Category/Admin/category_update.html.twig',[
            'formView' => $formView
        ]);
    }
    /**
     * @route("admin/category/delete/{id}",name="admin_category_delete")
     * @param $id
     * @param CategoryRepository $categoryRepository
     * @param EntityManagerInterface $entityManager
     * @return RedirectResponse
     */
    //je crée une methode deletearticle qui aura pour paramétres $id(me permettra de recuperer l'article),articlerepository(qui me permettra de récuperer
    //les données de la base de données, sf effectuera la requete delete) et entityManagerinterface,
    // (qui me permettra de faire des réquetes ici delete)
    // ces classes seront intanciés grace a entity manager à ma place
    public function deleteCategory($id,CategoryRepository $categoryRepository,EntityManagerInterface $entityManager)
    {
        //je récupére en bdd  l'id wild card qui correspond a celui renseigner dans url
        $category = $categoryRepository->find($id);
        //je fait une condition qui va permettra de verifier si il y a un article et un fois l'article et l'id supprimés de ne pas afficher de message d'erreur
        if(!is_null($category)){
            //entitymanager avec le fonction remove effacera l'article dont l'id est renseigné dans url
            $entityManager->remove($category);
            //la fonction flush insere les nouvelles modifs
            $entityManager->flush();
            //j'ajoute un message de type flash qui s'affichera a la suppression de l'article
            $this->addFlash(
                "success",
                "la category a été supprimé"
            );

        }
        // la fonction redirecttoroute permet de retrouner un visuel via le name de mon fichier 'articlelist'
        return $this->redirectToRoute('admin_categorylist');

    }

}