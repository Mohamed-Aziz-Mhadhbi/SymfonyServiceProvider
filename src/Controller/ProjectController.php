<?php

namespace App\Controller;

use App\Entity\Project;
use App\Form\ProjectType;
use App\Repository\ProjectRepository;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\PieChart;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;


class ProjectController extends AbstractController
{
    /**
     * @var Security
     */
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }


    /**
     * @Route("/admin/dashboard/project", name="project_index_back", methods={"GET"})
     */
    public function indexBack(ProjectRepository $projectRepository): Response
    {
        $list1=$projectRepository->calcul(1);
        $total1=0;
        foreach ($list1 as $row){
            $total1++;
        }
        $list2=$projectRepository->calcul(0);
        $total2=0;
        foreach ($list2 as $row){
            $total2++;
        }


        $pieChart = new PieChart();
        $pieChart->getData()->setArrayToDataTable(
            [['Task', 'Hours per Day'],
                ['Validated',     $total1],
                ['Not validated',    $total2]
            ]
        );
        $pieChart->getOptions()->setTitle('Projects activities');
        $pieChart->getOptions()->setHeight(500);
        $pieChart->getOptions()->setWidth(900);
        $pieChart->getOptions()->getTitleTextStyle()->setBold(true);
        $pieChart->getOptions()->getTitleTextStyle()->setColor('#009900');
        $pieChart->getOptions()->getTitleTextStyle()->setItalic(true);
        $pieChart->getOptions()->getTitleTextStyle()->setFontName('Arial');
        $pieChart->getOptions()->getTitleTextStyle()->setFontSize(20);


        return $this->render('BackInterface/project/index.html.twig', [
            'projects' => $projectRepository->findAll(),
            'piechart' => $pieChart,
        ]);
    }

    /**
     * @Route("/home/project", name="project_index_front", methods={"GET"})
     */
    public function indexFront(ProjectRepository $projectRepository): Response
    {
        $user = $this->security->getUser();
        return $this->render('FrontInterface/project/index.html.twig', [
            'projects' => $projectRepository->findAll(),
            'user' => $user,
        ]);
    }
    /**
     * @Route("/home/project/accept", name="project_index_front_accept", methods={"GET"})
     */
    public function accept(ProjectRepository $projectRepository ): Response
    {
        return $this->render('FrontInterface/project/accepted.html.twig');
    }
    /**
     * @Route("/home/project/rech", name="rech", methods={"GET"})
     */
    public function rech(ProjectRepository $projectRepository , Request $request): Response
    {   $project = new Project();

        $title ="";
        return $this->render('BackInterface/project/index.html.twig', [
            'projects' => $projectRepository->findbytitle($title),
        ]);
    }


    /**
     * @Route("/admin/dashboard/project/new", name="project_new_back", methods={"GET","POST"})
     */
    public function newBack(Request $request , ProjectRepository $projectRepository): Response
    {

        $project = new Project();
        $form = $this->createForm(ProjectType::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($project);
            $entityManager->flush();


            return $this->redirectToRoute('project_index_back');
        }

        return $this->render('BackInterface/project/index.html.twig', [
            'project' => $project,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/home/project/new", name="project_new_front", methods={"GET","POST"})
     */
    public function newFront(Request $request , \Swift_Mailer $mailer , ProjectRepository $projectRepository): Response
    {
        $user = $this->security->getUser();


        $project = new Project();
        $form = $this->createForm(ProjectType::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $project->setStatus(0);
            $project->setProjectUser($user);
            $entityManager->persist($project);
            $entityManager->flush();
            $mail = (new \Swift_Message('Thanks for proposing this project and choosing our services'))
                // On attribue l'expéditeur
                ->setFrom('service.provider.time@gmail.com')
                // On attribue le destinataire
                ->setTo($user->getEmail())
                // On crée le texte avec la vue
                ->setBody(
                    $this->renderView(
                        'emails/thanks.html.twig'
                    ),
                    'text/html'
                )
            ;
            $mailer->send($mail);

            $mail1 = (new \Swift_Message('A freelancer just contacted you'))
                // On attribue l'expéditeur
                ->setFrom('service.provider.time@gmail.com')
                // On attribue le destinataire
                ->setTo($user->getEmail())
                // On crée le texte avec la vue
                ->setBody(
                    $this->renderView(
                        'emails/accept.html.twig'
                    ),
                    'text/html'
                )
            ;
            $mailer->send($mail);
            $mailer->send($mail1);
            //$projectRepository->sms();
            /*$this->mailer->sendEmail($user->getEmail(), $user->getToken());*/
            $this->addFlash("success", "Inscription réussie !");

            return $this->redirectToRoute('project_index_front');
        }

        return $this->render('FrontInterface/project/new.html.twig', [
            'project' => $project,
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }

    /**
     * @Route("/admin/dashboard/project/{id}", name="project_show_back", methods={"GET"})
     */
    public function showBack(Project $project): Response
    {
        return $this->render('BackInterface/project/show.html.twig', [
            'project' => $project,
        ]);
    }

    /**
     * @Route("/home/project/{id}", name="project_show_front", methods={"GET"})
     */
    public function showFront(Project $project): Response
    {
        $user = $this->security->getUser();
        return $this->render('FrontInterface/project/show.html.twig', [
            'project' => $project,
            'user' => $user,
        ]);
    }

    /**
     * @Route("/admin/dashboard/project/{id}/edit", name="project_edit_back", methods={"GET","POST"})
     */
    public function editBack(Request $request, Project $project): Response
    {
        $form = $this->createForm(ProjectType::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('project_index_back');
        }

        return $this->render('BackInterface/project/edit.html.twig', [
            'project' => $project,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/home/project/{id}/edit", name="project_edit_front", methods={"GET","POST"})
     */
    public function editFront(Request $request, Project $project , \Swift_Mailer $mailer): Response
    {
        $user = $this->security->getUser();


        $project->setStatus(1);
            $this->getDoctrine()->getManager()->flush();

        $mail = (new \Swift_Message('Thank you for working on our platform'))
            // On attribue l'expéditeur
            ->setFrom('service.provider.time@gmail.com')
            // On attribue le destinataire
            ->setTo($user->getEmail())
            // On crée le texte avec la vue
            ->setBody(
                $this->renderView(
                    'FrontInterface/project/ty1.html.twig'
                ),
                'text/html'
            )
        ;
        $mailer->send($mail);

        return $this->render('FrontInterface/project/ty.html.twig', [
            'project' => $project,
        ]);
    }

    /**
     * @Route("/admin/dashboard/project/{id}", name="project_delete_back", methods={"DELETE"})
     */
    public function deleteBack(Request $request, Project $project): Response
    {
        if ($this->isCsrfTokenValid('delete'.$project->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($project);
            $entityManager->flush();
        }

        return $this->redirectToRoute('project_index_back');
    }

    /**
     * @Route("/home/project/{id}", name="project_delete_front", methods={"DELETE"})
     */
    public function deleteFront(Request $request, Project $project): Response
    {
        if ($this->isCsrfTokenValid('delete'.$project->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($project);
            $entityManager->flush();
        }

        return $this->redirectToRoute('project_index_front');
    }

    /*public function indexAction(ProjectRepository $projectRepository)
    {
        $pieChart = new PieChart();
        $pieChart->getData()->setArrayToDataTable(
            [['Task', 'Hours per Day'],
                ['Validated',     $projectRepository->calcul(1)],
                ['Not validated',    $projectRepository->calcul(1)]
            ]
        );
        $pieChart->getOptions()->setTitle('My Daily Activities');
        $pieChart->getOptions()->setHeight(500);
        $pieChart->getOptions()->setWidth(900);
        $pieChart->getOptions()->getTitleTextStyle()->setBold(true);
        $pieChart->getOptions()->getTitleTextStyle()->setColor('#009900');
        $pieChart->getOptions()->getTitleTextStyle()->setItalic(true);
        $pieChart->getOptions()->getTitleTextStyle()->setFontName('Arial');
        $pieChart->getOptions()->getTitleTextStyle()->setFontSize(20);

        return $this->render('AppBundle::index.html.twig', array('piechart' => $pieChart));
    }*/
}
