<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Security\EmailVerifier;
use App\Security\AppCustomAuthenticator;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;


class RegistrationController extends AbstractController
{
    private $emailVerifier;
    // je cree une function qui verifie l'email de l'utilisateur
    public function __construct(EmailVerifier $emailVerifier)
    {
        $this->emailVerifier = $emailVerifier;
    }

    /**
     * @Route("/register", name="app_register")
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param GuardAuthenticatorHandler $guardHandler
     * @param AppCustomAuthenticator $authenticator
     * @return Response
     */
    // la function register me permet de recuperer le données,de crypté le mot de passe
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder,
                             GuardAuthenticatorHandler $guardHandler, AppCustomAuthenticator
                             $authenticator): Response
    {   //je cree un nouvel objet user
        $user = new User();
        //je cree et renvoi vers le formulaire d'uinscription grace a :: class
        $form = $this->createForm(RegistrationFormType::class, $user);
        //avec la methode handleRequest de la class form je récupère les données en post
        $form->handleRequest($request);
        //je fait une contidion si mon formulaire et envoyer et valide alors je recupere le mot de passe
        // et je le crypte
        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            //

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

                $this->addFlash(
                    "success",
                    "Vous étes inscrit"
                );
            return $this->redirectToRoute('home_page');
        }

         return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);

    }

    /**
     * @Route("/verify/email", name="app_verify_email")
     * @param Request $request
     * @return Response
     */
    public function verifyEmail(Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // validate email confirmation link, sets User::isVerified=true and persists
        try {
            $this->emailVerifier->handleEmailConfirmation($request, $this->getUser());
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $exception->getReason());

        }


        $this->addFlash('success', 'Votre adresse mail a bien été vérifié.');

        return $this->redirectToRoute('home_page');
    }


    /**
     * @route("/email", name="email")
     * @param $name
     * @param Request $request
     * @param \Swift_Mailer $mailer
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param GuardAuthenticatorHandler $guardHandler
     * @return Response
     */
    public function index($name, Request $request,\Swift_Mailer $mailer,UserPasswordEncoderInterface $passwordEncoder,
                          GuardAuthenticatorHandler $guardHandler):Response
    {
        $user = new Users();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $message = (new \Swift_Message('Hello Email'))
            ->setFrom('send@example.com')
            ->setTo('recipient@example.com')
            ->setBody(
                $this->renderView(
                // templates/emails/registration.html.twig
                    'email.html.twig',
                    ['name' => $name]
                ),
                'text/html'
            );

        $mailer->send($message);
       // return $this->redirectToRoute('home_page');
       // return $this->render(...);
            return $guardHandler->authenticateUserAndHandleSuccess(

                $user,
                $request,
                $authenticator,
                'main' // firewall name in security.yaml
            );
    }




    // you can remove the following code if you don't define a text version for your emails
    //->addPart(
    // $this->renderView(
    // templates/emails/registration.txt.twig
    //  'emails/registration.txt.twig',
    // ['name' => $name]
    // ),
    // 'text/plain'
    //)
}
