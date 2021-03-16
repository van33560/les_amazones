<?php


namespace App\Controller;




use App\Form\ArticleType;
use App\Form\ContactType;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;



class ContactController  extends AbstractController
{
    /**
     * @Route("/contact",name="contact")
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return RedirectResponse|Response
     */

    //je crée une methode pour créer un formulaire avec la function contact en paramétre
    //  la méthode request pour récuperer les infos passées en post, get dans l'url
    // avec entitymanager via ses classes je pre-sauvegarde et envoi grace a (persist,flush,move)
    public function InsertContact(Request $request, EntityManagerInterface $entityManager)
    {
            $form = $this->createForm(ContactType::class);
            $formView = $form-> createView();
            //la fonction render me permet de renvoyer vers mon fichier a twig mon formulaire
            return $this->render('contact/contact.html.twig',[
                'formView'=> $formView]);

        return $this->redirectToRoute('home_page');
    }



}