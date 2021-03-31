<?php


namespace App\Controller;





use App\Form\ContactType;
use Swift_Mailer;

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
     * @param Swift_Mailer $mailer
     * @return RedirectResponse|Response
     */

    //je crée une methode pour créer un formulaire avec la function contact en paramétre
    //  la méthode request pour récuperer les infos passées en post, get dans l'url
    // avec entitymanager via ses classes je pre-sauvegarde et envoi grace a (persist,flush,move)
    public function InsertContact(Request $request, \Swift_Mailer $mailer)
    {

            $form = $this->createForm(ContactType::class);
            $request->query->get('form');
            //$form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                    $contact = $form->getData();
                    // On crée le message
                    $message = (new \Swift_Message('Nouveau contact'))
                        // On attribue l'expéditeur
                        ->setFrom($contact['email'])
                        // On attribue le destinataire
                        ->setTo('vanlab33@hotmail.fr')
                        // On crée le texte avec la vue
                        ->setBody(
                         $this->renderView(
                                    'contact/contact.html.twig',
                                    compact('contact')),
                                   'text/html'
                        );
                    $mailer->send($message);
            }

        $formView = $form-> createView();
        //la fonction render me permet de renvoyer vers mon fichier a twig mon formulaire
        return $this->render('contact/contact.html.twig',[
            'formView' => $formView,

            ]);

        return $this->redirectToRoute('home_page');
    }



}