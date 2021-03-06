<?php

namespace App\Controller;

use App\Entity\Postulation;
use App\Form\PostulationType;
use App\Repository\PostulationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/postulation")
 */
class PostulationController extends AbstractController
{
    /**
     * @Route("/", name="postulation_index", methods={"GET"})
     */
    public function index(PostulationRepository $postulationRepository): Response
    {
        return $this->render('postulation/index.html.twig', [
            'postulations' => $postulationRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="postulation_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $postulation = new Postulation();
        $form = $this->createForm(PostulationType::class, $postulation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($postulation);
            $entityManager->flush();

            return $this->redirectToRoute('postulation_index');
        }

        return $this->render('postulation/new.html.twig', [
            'postulation' => $postulation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="postulation_show", methods={"GET"})
     */
    public function show(Postulation $postulation): Response
    {
        return $this->render('postulation/show.html.twig', [
            'postulation' => $postulation,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="postulation_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Postulation $postulation): Response
    {
        $form = $this->createForm(PostulationType::class, $postulation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('postulation_index');
        }

        return $this->render('postulation/edit.html.twig', [
            'postulation' => $postulation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="postulation_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Postulation $postulation): Response
    {
        if ($this->isCsrfTokenValid('delete'.$postulation->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($postulation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('postulation_index');
    }
}
