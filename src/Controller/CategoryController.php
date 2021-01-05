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
     //je crée une fonction category qui me permet de recuperer toutes mes categories
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
     // je vrée une function qui me permet de recupere une categorie et son id
     public function categoryShow($id, CategoryRepository $categoryRepository)
    {
            $category = $categoryRepository->find($id);

                return $this->render("Front/category.html.twig", [
                    'category' => $category
          ]);

    }

}