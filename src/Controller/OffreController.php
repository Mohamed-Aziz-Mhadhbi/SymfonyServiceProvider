<?php

namespace App\Controller;

use App\Entity\Offre;
use App\Form\OffreType;
use App\Repository\OffreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class OffreController extends AbstractController
{
    /**
     * @Route("/admin/dashboard/offre", name="offre_index_back", methods={"GET"})
     */
    public function indexBack(OffreRepository $offreRepository): Response
    {
        return $this->render('BackInterface/offre/index.html.twig', [
            'offres' => $offreRepository->findAll(),
        ]);
    }

    /**
     * @Route("/home/offre", name="offre_index_front", methods={"GET"})
     */
    public function indexFront(OffreRepository $offreRepository): Response
    {
        return $this->render('FrontInterface/offre/index.html.twig', [
            'offres' => $offreRepository->findAll(),
        ]);
    }

    /**
     * @Route("/home/offre/new", name="offre_new_front", methods={"GET","POST"})
     */
    public function newFront(Request $request): Response
    {
        $offre = new Offre();
        $form = $this->createForm(OffreType::class, $offre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($offre);
            $entityManager->flush();

            return $this->redirectToRoute('offre_index_front');
        }

        return $this->render('FrontInterface/offre/new.html.twig', [
            'offre' => $offre,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/admin/dashboard/offre/{id}", name="offre_show_back", methods={"GET"})
     */
    public function showBack(Offre $offre): Response
    {
        return $this->render('BackInterface/offre/show.html.twig', [
            'offre' => $offre,
        ]);
    }

    /**
     * @Route("/home/offre/{id}", name="offre_show_front", methods={"GET"})
     */
    public function showFront(Offre $offre): Response
    {
        return $this->render('FrontInterface/offre/show.html.twig', [
            'offre' => $offre,
        ]);
    }

    /**
     * @Route("/home/offre/{id}/edit", name="offre_edit_front", methods={"GET","POST"})
     */
    public function editFront(Request $request, Offre $offre): Response
    {
        $form = $this->createForm(OffreType::class, $offre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('offre_index_front');
        }

        return $this->render('FrontInterface/offre/edit.html.twig', [
            'offre' => $offre,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/dashboard/offre/{id}", name="offre_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Offre $offre): Response
    {
        if ($this->isCsrfTokenValid('delete'.$offre->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($offre);
            $entityManager->flush();
        }

        return $this->redirectToRoute('offre_index_back');
    }

    /**
     * @Route("/home/offre/{id}", name="offre_delete_front", methods={"DELETE"})
     */
    public function deleteFront(Request $request, Offre $offre): Response
    {
        if ($this->isCsrfTokenValid('delete'.$offre->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($offre);
            $entityManager->flush();
        }

        return $this->redirectToRoute('offre_index_front');
    }

}
