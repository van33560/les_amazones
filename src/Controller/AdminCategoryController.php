<?php


namespace App\Controller;


use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
class AdminCategoryController extends AbstractController
{    // chemin de ma route et son name
    /**
     * @Route("admin/category/categorys", name="admin_categorylist")
     * @param CategoryRepository $categoryRepository
     * @return \Symfony\Component\HttpFoundation\Response
     */
    //ma methode category repository me permet de recuperer via la bdd les données et de renvoyer vers twig
    // pour les afficher un resultat vie la propriete  render
    public function CategoryList(CategoryRepository $categoryRepository)
    {    //find all est une methode qui permet de recuperer tous les temoignages des champs de ma bdd
        //doctrine effectue la requete pour moi ici select*from category
        $categorys = $categoryRepository->findAll();
        //la methode render me permet d'envoyer a twig les infos qui seront affichés
        return $this->render("category/admin/categorys.html.twig",[
            'categorys' => $categorys
        ]);

    }

    // chemin de ma route avec id
    /**
     * @route("/category/show/{id}",name="categoryShow")
     * @param $id
     * @param CategoryRepository $categoryRepository
     * @return \Symfony\Component\HttpFoundation\Response
     */
    // ma methode categoryrepository me permet de recuperer une category et son id dans la  bdd et de retourner
    // un resultat via la propriete render

    public function categoryShow($id, CategoryRepository $categoryRepository)
    {   //find($id) est une methode qui permet de recuperer une category via son id
        //doctrine effectue la requete pour moi ici select name.category form category where id=
        $category = $categoryRepository->find($id);
        //la methode render me permet d'envoyer a twig les infos qui seront affichés
        return $this->render("category/admin/category.html.twig", [
            'category' => $category
        ]);

    }
}