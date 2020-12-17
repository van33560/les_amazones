<?php


namespace App\Controller;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
class CategoryController extends AbstractController
{
    /**
     * @Route("/categorys", name="Front_categorylist")
     * @param CategoryRepository $categoryRepository
     * @return Response
     */
//ma methode acticle repository me permet de recuperer via la bdd les données et de les afficher avec return render
public function CategoryList(CategoryRepository $categoryRepository)
    {
        $categorys = $categoryRepository->findAll();

        return $this->render("Front/categorys.html.twig", [
            'categorys' => $categorys
        ]);

    }


// chemin de ma route qui renvoi au contenu d'une de mes categories via son id
    /**
     * @route("/category/show/{id}",name="Front_categoryShow")
     * @param $id
     * @param CategoryRepository $categoryRepository
     * @return Response
     */
// ma methode articlerepository me permet de recuperer les données de ma bdd et de retourner un resultat via la propriete render
public function categoryShow($id, CategoryRepository $categoryRepository)
    {
        $category = $categoryRepository->find($id);

        return $this->render("Front/category.html.twig", [
            'category' => $category
      ]);

    }

}