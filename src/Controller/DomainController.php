<?php

namespace App\Controller;

use App\Entity\Domain;
use App\Form\DomainType;
use App\Repository\DomainRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class DomainController extends AbstractController
{
    /**
     * @Route("/admin/dashboard/domain", name="domain_index_back", methods={"GET"})
     */
    public function indexBack(DomainRepository $domainRepository): Response
    {
        return $this->render('BackInterface/domain/index.html.twig', [
            'domains' => $domainRepository->findAll(),
        ]);
    }

    /**
     * @Route("/home/domain", name="domain_index_front", methods={"GET"})
     */
    public function indexFront(DomainRepository $domainRepository): Response
    {
        return $this->render('FrontInterface/domain/index.html.twig', [
            'domains' => $domainRepository->findAll(),
        ]);
    }

    /**
     * @Route("/admin/dashboard/domain/new", name="domain_new_back", methods={"GET","POST"})
     */
    public function newBack(Request $request): Response
    {
        $domain = new Domain();
        $form = $this->createForm(DomainType::class, $domain);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($domain);
            $entityManager->flush();

            return $this->redirectToRoute('domain_index_back');
        }

        return $this->render('BackInterface/domain/new.html.twig', [
            'domain' => $domain,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/home/domain/new", name="domain_new_front", methods={"GET","POST"})
     */
    public function newFront(Request $request): Response
    {
        $domain = new Domain();
        $form = $this->createForm(DomainType::class, $domain);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($domain);
            $entityManager->flush();

            return $this->redirectToRoute('domain_index_front');
        }

        return $this->render('FrontInterface/domain/new.html.twig', [
            'domain' => $domain,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/dashboard/domain/{id}", name="domain_show_back", methods={"GET"})
     */
    public function showBack(Domain $domain): Response
    {
        return $this->render('BackInterface/domain/show.html.twig', [
            'domain' => $domain,
        ]);
    }

    /**
     * @Route("/home/domain/{id}", name="domain_show_front", methods={"GET"})
     */
    public function showFront(Domain $domain): Response
    {
        return $this->render('FrontInterface/domain/show.html.twig', [
            'domain' => $domain,
        ]);
    }

    /**
     * @Route("/admin/dashboard/domain/{id}/edit", name="domain_edit_back", methods={"GET","POST"})
     */
    public function editBack(Request $request, Domain $domain): Response
    {
        $form = $this->createForm(DomainType::class, $domain);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('domain_index_back');
        }

        return $this->render('BackInterface/domain/edit.html.twig', [
            'domain' => $domain,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/home/domain/{id}/edit", name="domain_edit_front", methods={"GET","POST"})
     */
    public function editFront(Request $request, Domain $domain): Response
    {
        $form = $this->createForm(DomainType::class, $domain);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('domain_index_front');
        }

        return $this->render('FrontInterface/domain/edit.html.twig', [
            'domain' => $domain,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/dashboard/domain/{id}", name="domain_delete_back", methods={"DELETE"})
     */
    public function deleteBack(Request $request, Domain $domain): Response
    {
        if ($this->isCsrfTokenValid('delete'.$domain->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($domain);
            $entityManager->flush();
        }

        return $this->redirectToRoute('domain_index_back');
    }

    /**
     * @Route("/home/domain/{id}", name="domain_delete_front", methods={"DELETE"})
     */
    public function deleteFront(Request $request, Domain $domain): Response
    {
        if ($this->isCsrfTokenValid('delete'.$domain->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($domain);
            $entityManager->flush();
        }

        return $this->redirectToRoute('domain_index_front');
    }
}
