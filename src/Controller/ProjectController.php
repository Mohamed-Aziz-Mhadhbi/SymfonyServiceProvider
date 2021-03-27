<?php

namespace App\Controller;

use App\Entity\Project;
use App\Form\ProjectType;
use App\Repository\ProjectRepository;
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
        return $this->render('BackInterface/project/index.html.twig', [
            'projects' => $projectRepository->findAll(),
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
     * @Route("/admin/dashboard/project/new", name="project_new_back", methods={"GET","POST"})
     */
    public function newBack(Request $request): Response
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
    public function newFront(Request $request): Response
    {
        $user = $this->security->getUser();
        $project = new Project();
        $form = $this->createForm(ProjectType::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($project);
            $entityManager->flush();

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
    public function editFront(Request $request, Project $project): Response
    {
        $form = $this->createForm(ProjectType::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('project_index_front');
        }

        return $this->render('FrontInterface/project/edit.html.twig', [
            'project' => $project,
            'form' => $form->createView(),
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

}
