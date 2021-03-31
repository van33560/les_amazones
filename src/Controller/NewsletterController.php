<?php


namespace App\Controller;



use App\Entity\Newsletter;
use App\Form\NewsletterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class NewsletterController extends AbstractController{

    /**
     * @Route("/newsletter", name="newsletter")
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     *
     */

    public function newsletter(Request $request, EntityManagerInterface $entityManager)
    {
        $form = $this->createForm(NewsletterType::class);
        // je crée un nouvel objet
        $newsletter = new Newsletter();
        //je crée un formulaire grâce à la function createFrom et je passe en paramétre le chemin vers
        // le fichierArticleType, lie au gabarit de mon formulaire  Entité Article

        if($form->isSubmitted() && $form->isValid())
        {
            //je crée une variable illustration que je recupere dans mon formulaire
            // si mon formulaire et envoyer et valide alors je pré-sauvegarde avec la fonction persist
            $entityManager->persist($newsletter);
            // j'envoi en BDD avec la fonction flush
            $entityManager->flush();
                        //la methode addflash me permet d'afficher un message de confirmation de creation d'article
                    // via un fichier twig
                    $this->addFlash(
                        "success",
                        "Un mail de confirmation vous sera envoyé"
                    );

            return $this->redirectToRoute('home_page');
        }

      return new Response('Merci pour votre inscription votre email a été enregistré 
      vous receverez prochainement un message de confirmation!!');
    }




}


