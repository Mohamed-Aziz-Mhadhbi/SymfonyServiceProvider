<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ClientType;
use App\Form\CompanyType;
use App\Form\FreelancerFrontType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class InscriptionController extends AbstractController
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * @var UserRepository
     */
    private $userRepository;


    public function __construct(UserPasswordEncoderInterface $passwordEncoder, UserRepository $userRepository)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->userRepository = $userRepository;
    }

    /**
     * @Route("/inscription/client", name="register")
     * @param Request $request
     * @return Response
     */
    public function register(Request $request,\Swift_Mailer $mailer): Response
    {
        $user = new User();
        $form = $this->createForm(ClientType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $this->passwordEncoder->encodePassword($user, $form->get("password")->getData())
            );
            $user->setToken($this->generateToken());
            $user->setRole('client');
            $user->setRoles(['ROLE_USER']);
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $mail = (new \Swift_Message('Thanks for signing up!'))
                // On attribue l'expéditeur
                ->setFrom('service.provider.time@gmail.com')
                // On attribue le destinataire
                ->setTo($user->getEmail())
                // On crée le texte avec la vue
                ->setBody(
                    $this->renderView(
                        'emails/registration.html.twig', ['token' => $user->getToken()]
                    ),
                    'text/html'
                )
            ;
            $mailer->send($mail);

            /*$this->mailer->sendEmail($user->getEmail(), $user->getToken());*/
            $this->addFlash("success", "Inscription réussie !");
        }
        return $this->render('inscription/inscription.html.twig', [
            'form' => $form->createView()
        ]);
    }
    /**
     * @Route("/inscription/freelancer", name="registerFreelancer")
     * @param Request $request
     * @return Response
     */
    public function registerFreelancer(Request $request,\Swift_Mailer $mailer): Response
    {
        $user = new User();
        $form = $this->createForm(FreelancerFrontType::class, $user);
        $form->handleRequest($request);



        if($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $this->passwordEncoder->encodePassword($user, $form->get("password")->getData())
            );

            $file = $user->getPhoto();
            $Uploads_directory = $this->getParameter('Uploads_directory');
            $filename = md5(uniqid()) . '.' . $file->guessExtension();

            $file->move(
              $Uploads_directory,
              $filename
            );
            $user->setToken($this->generateToken());
            $user->setRole('prestataire');
            $user->setRoles(['ROLE_USER']);
            $user->setPhoto($filename);

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $mail = (new \Swift_Message('Thanks for signing up!'))
                // On attribue l'expéditeur
                ->setFrom('service.provider.time@gmail.com')
                // On attribue le destinataire
                ->setTo($user->getEmail())
                // On crée le texte avec la vue
                ->setBody(
                    $this->renderView(
                        'emails/registration.html.twig', ['token' => $user->getToken()]
                    ),
                    'text/html'
                )
            ;
            $mailer->send($mail);

            /*$this->mailer->sendEmail($user->getEmail(), $user->getToken());*/
            $this->addFlash("success", "Inscription réussie !");
        }
        return $this->render('inscription/inscriptionFreelancer.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/inscription/entreprise", name="registerEntreprise")
     * @param Request $request
     * @return Response
     */
    public function registerEntreprise(Request $request,\Swift_Mailer $mailer): Response
    {
        $user = new User();
        $form = $this->createForm(CompanyType::class, $user);
        $form->handleRequest($request);



        if($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $this->passwordEncoder->encodePassword($user, $form->get("password")->getData())
            );


            $user->setToken($this->generateToken());
            $user->setRole('entreprise');
            $user->setRoles(['ROLE_USER']);

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $mail = (new \Swift_Message('Thanks for signing up!'))
                // On attribue l'expéditeur
                ->setFrom('service.provider.time@gmail.com')
                // On attribue le destinataire
                ->setTo($user->getEmail())
                // On crée le texte avec la vue
                ->setBody(
                    $this->renderView(
                        'emails/registration.html.twig', ['token' => $user->getToken()]
                    ),
                    'text/html'
                )
            ;
            $mailer->send($mail);


            $this->addFlash("success", "Inscription réussie !");
        }
        return $this->render('inscription/inscriptionEntrepise.html.twig', [
            'form' => $form->createView()
        ]);
    }


    /**
     * @Route("/confirmer-mon-compte/{token}", name="confirm_account")
     * @param string $token
     */
    public function confirmAccount(string $token)
    {
        $user = $this->userRepository->findOneBy(["token" => $token]);
        if($user) {
            $user->setToken(null);
            $user->setEnabled(true);
            $user->setIsVerified(true);
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            $this->addFlash("success", "Compte actif !");
            return $this->redirectToRoute("home");
        } else {
            $this->addFlash("error", "Ce compte n'exsite pas !");
            return $this->redirectToRoute('home');
        }
    }

    /**
     * @return string
     * @throws \Exception
     */
    private function generateToken()
    {
        return rtrim(strtr(base64_encode(random_bytes(32)), '+/', '-_'), '=');
    }
}
