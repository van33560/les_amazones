<?php


namespace App\Controller;


use App\Repository\TestimonyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestimonyController  extends AbstractController
{
    /**
     * @Route ("Front/testimony/testimonys", name="Front_testimony_list")
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
      return $this->render("front/testimonys.html.twig",[
          'testimonys'=>$testimonys
      ]);
    }
    /**
     * @route ("Front/testimony/show/{id}",name="Front_testimonyShow")
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


}