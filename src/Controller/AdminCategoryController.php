<?php


namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Type;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class AdminCategoryController extends AbstractController
{

    /**
     * @Route("Admin/Admin_category", name="home_category")
     * @param CategoryRepository $categoryRepository
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexCategory(CategoryRepository $categoryRepository)
    {
        $categorys = $categoryRepository->findAll();
            return $this->render('Admin/Admin_category.html.twig', [
                'categorys' => $categorys
            ]);

    }

/**
 * @Route("category/admin/categorys", name="admin_categorylist")
 * @param CategoryRepository $categoryRepository
 * @return Response;
 */
    //je crée une function category list qui me permet de recupérer mes catégories
    public function CategoryList(CategoryRepository $categoryRepository)
    {   //find all est une methode qui permet de recuperer tous les temoignages des champs de ma bdd
        //doctrine effectue la requete pour moi ici select*from category
        $categorys = $categoryRepository->findAll();
        //la methode render me permet de renvoyer vers mon fichier a twig et d'afficher son contenu
            return $this->render("Category/Admin/categorys.html.twig",[
                'categorys' => $categorys
            ]);

    }

    /**
     * @route("category/admin/category/insert",name="admin_category_insert")
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param SluggerInterface $slugger
     * @return RedirectResponse|Response
     */

    //je crée une methode pour créer un formulaire avec la methode insertArticle
    //je lui passe en paremtre la méthode request pour récuperer les informations passés en post
    //entityManager grace a ces class me permet de gerer la recuperation de info
    //la methode slugger me permet de modifier le nom de mon iamage et de retirer les caracteres speciaux
    public function insertCategory(Request $request, EntityManagerInterface $entityManager,SluggerInterface $slugger)
    {   //je crée un nouvelle objet
        $category = new Category();
        //je crée un formulaire grâce à la fonction createFrom et je passe en paramétre le chemin grace a ::class
        // vers le fichierArticleType
        $form = $this->createForm(CategoryType::class, $category);
        //avec la methode handle de la class form je récupère les données en post
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $photo=$form->get('photo')->getData();

              if($photo){
                //je récupere le nom d'origine de l'image
                $originalFilename = pathinfo($photo->getClientOriginalName(), PATHINFO_FILENAME);
                // grâce à la class Slugger, je change le nom de mon image
                // et je sort les caractères spéciaux
                $safeFilename = $slugger->slug($originalFilename);

                $newFilename = $safeFilename.'-'.uniqid().'.'.$photo->guessExtension();
                //je déplace dans un fichier temporaire (services.yaml) l'image ou je precise en parametre son nom
                //la méthode move me permet de déplacer l'image
                $photo->move(
                    $this->getParameter('photo_directory'),
                    $newFilename
                );
                $category->setPhoto($newFilename);

              }
                // je récupère le fichier uploadé dans le formulaire
                //je récupere le contenu du champ imageFileName
                //je fait une condidion si mon formulaire et envoyer et valide alors je pré-sauvegarde
                //avec la fonction persist
                $entityManager->persist($category);
                // je renvoi les données avec la function flush
                $entityManager->flush();
                //la methode addflash me permet d'afficher un message apres ajout via un fichier twig
                        $this->addFlash(
                        "success",
                        "la catégorie a été ajoutée"
                    );
            return $this->redirectToRoute('admin_categorylist');
        }
        //je crée grâce à la fonction createview une vue qui pourra en suite être lu par twig
        $formView = $form-> createView();
        //la fonction render me permet renvoyer vers mon twig les infos qui seront affichés
            return $this->render('Category/Admin/category_insert.html.twig',[
                'formView' => $formView
            ]);

    }

    /**
     * @route("category/admin/category/update/{id}",name="admin_category_update")
     * @param $id
     * @param CategoryRepository $categoryRepository
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return RedirectResponse|Response
     */
    // je crée une methode updateArticle pour modifier le contenu du formulaire je lui passe en parametre id pour pouvoir
    // modifier un article grace à son id,la prropriété repository me permet d'avoir accés aux données de la bdd et
    // la méthode request me permet de recuperer les données passés dans url en post
    // entitymanager via ces classes de gerer la pre-sauvegarde et renvoi les données grace a ces classes(persist et flush)
    public function updateCategory($id, CategoryRepository $categoryRepository,Request $request, EntityManagerInterface $entityManager)
    {
        //je récupére en bdd  l'id passer dans url
        $category = $categoryRepository -> find($id);

        if(is_null($category)){
            return $this->redirectToRoute('admin_categorylist');
        }
        //je crée un formulaire grâce à la fonction createFrom et je passe en paramétre le chemin grace a :: class
        // vers  le fichierArticleType
        $form = $this -> createForm(CategoryType::class,$category);

        //avec la methode handleRequest de la class form je récupère les données en post
        $form->handleRequest($request);
        //je fait une contidion si mon formulaire et renseigner et valide alors je pré-sauvegarde
        //avec la fonction persist et je renvoi les données grace la fonction flush
        if($form->isSubmitted() && $form->isValid()){
            $entityManager->persist($category);
            $entityManager->flush();

            //je crée un message grace a la methode addflash qui s'affichera à la modification de l'article
                $this->addFlash(
                    "success",
                    "la categorie a été modifiée"
                );
            return $this->redirectToRoute('admin_categorylist');
        }
        //je crée grâce à la fonction createview une vue qui pourra être lu par twig
        $formView = $form-> createView();
        //la fonction render me permet renvoyer vers mon fichier twig formulaire
            return $this->render('Category/Admin/category_update.html.twig',[
                'formView' => $formView
            ]);
    }
    /**
     * @route("category/admin/category/delete/{id}",name="admin_category_delete")
     * @param $id
     * @param CategoryRepository $categoryRepository
     * @param EntityManagerInterface $entityManager
     * @return RedirectResponse
     */
    //je crée une methode deletearticle qui aura pour paramétres $id(me permettra de recuperer l'article),
    //articlerepository(qui me permettra de récuperer les données de la base de données, sf effectuera la requete delete)
    // et entityManagerinterface,(qui me permettra grace a ces classes de pre-sauvegarde et d'envoyer les données en bdd
    public function deleteCategory($id,CategoryRepository $categoryRepository,EntityManagerInterface $entityManager)
    {
        //je récupére en bdd  l'id une category via son id passée dans url
        $category = $categoryRepository->find($id);
        //je fait une condition qui va permettra de verifier si il y a un article et un fois l'article
        // et l'id supprimés j'affiche un message de suppression
        if(!is_null($category)){
            //la fonction remove efface l'article dont l'id est renseigné dans url
            $entityManager->remove($category);
            //la fonction flush renvoi les données modifs
            $entityManager->flush();
            //je cree un message addflash qui s'affiche à la suppression de la category
                $this->addFlash(
                    "success",
                    "la categorie a été supprimée"
                );

        }
        // la fonction redirecttoroute permet de retrouner un visuel via la route de mon fichier category'
        return $this->redirectToRoute('admin_categorylist');

     }


 }