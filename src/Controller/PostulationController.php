<?php

namespace App\Controller;

use App\Entity\Postulation;
use App\Form\PostulationType;
use App\Repository\PostulationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class PostulationController extends AbstractController
{
    /**
     * @Route("/admin/dashboard/postulation", name="postulation_index_back", methods={"GET"})
     */
    public function indexBack(PostulationRepository $postulationRepository): Response
    {
        return $this->render('BackInterface/postulation/index.html.twig', [
            'postulations' => $postulationRepository->findAll(),
        ]);
    }

    /**
     * @Route("/home/postulation", name="postulation_index_front", methods={"GET"})
     */
    public function indexFront(PostulationRepository $postulationRepository): Response
    {
        return $this->render('FrontInterface/postulation/index.html.twig', [
            'postulations' => $postulationRepository->findAll(),
        ]);
    }

    /**
     * @Route("/admin/dashboard/postulation/new", name="postulation_new_back", methods={"GET","POST"})
     */
    public function newBack(Request $request): Response
    {
        $postulation = new Postulation();
        $form = $this->createForm(PostulationType::class, $postulation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($postulation);
            $entityManager->flush();

            return $this->redirectToRoute('postulation_index_back');
        }

        return $this->render('BackInterface/postulation/new.html.twig', [
            'postulation' => $postulation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/home/postulation/new", name="postulation_new_front", methods={"GET","POST"})
     */
    public function newFront(Request $request): Response
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

        return $this->render('FrontInterface/postulation/new.html.twig', [
            'postulation' => $postulation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/dashboard/postulation/{id}", name="postulation_show_back", methods={"GET"})
     */
    public function showBack(Postulation $postulation): Response
    {
        return $this->render('BackInterface/postulation/show.html.twig', [
            'postulation' => $postulation,
        ]);
    }

    /**
     * @Route("/home/postulation/{id}", name="postulation_show_front", methods={"GET"})
     */
    public function showFront(Postulation $postulation): Response
    {
        return $this->render('FrontInterface/postulation/show.html.twig', [
            'postulation' => $postulation,
        ]);
    }

    /**
     * @Route("/admin/dashboard/postulation/{id}/edit", name="postulation_edit_back", methods={"GET","POST"})
     */
    public function editBack(Request $request, Postulation $postulation): Response
    {
        $form = $this->createForm(PostulationType::class, $postulation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('postulation_index_back');
        }

        return $this->render('BackInterface/postulation/edit.html.twig', [
            'postulation' => $postulation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/home/postulation/{id}/edit", name="postulation_edit_front", methods={"GET","POST"})
     */
    public function editFront(Request $request, Postulation $postulation): Response
    {
        $form = $this->createForm(PostulationType::class, $postulation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('postulation_index');
        }

        return $this->render('FrontInterface/postulation/edit.html.twig', [
            'postulation' => $postulation,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/admin/dashboard/postulation/{id}", name="postulation_delete_back", methods={"DELETE"})
     */
    public function deleteBack(Request $request, Postulation $postulation): Response
    {
        if ($this->isCsrfTokenValid('delete'.$postulation->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($postulation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('postulation_index_back');
    }

    /**
     * @Route("/home/postulation/{id}", name="postulation_delete_front", methods={"DELETE"})
     */
    public function deleteFront(Request $request, Postulation $postulation): Response
    {
        if ($this->isCsrfTokenValid('delete'.$postulation->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($postulation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('postulation_index_front');
    }

}
