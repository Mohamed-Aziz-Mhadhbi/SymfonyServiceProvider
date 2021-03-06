<?php

namespace App\Controller;

use App\Entity\Service;
use App\Form\ServiceType;
use App\Repository\ServiceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class ServiceController extends AbstractController
{
    /**
     * @Route("/admin/dashboard/service", name="service_index_back", methods={"GET"})
     */
    public function indexBack(ServiceRepository $serviceRepository): Response
    {
        return $this->render('BackInterface/service/index.html.twig', [
            'services' => $serviceRepository->findAll(),
        ]);
    }
    /**
     * @Route("/home/service", name="service_index_front", methods={"GET"})
     */
    public function indexFront(ServiceRepository $serviceRepository): Response
    {
        return $this->render('FrontInterface/service/index.html.twig', [
            'services' => $serviceRepository->findAll(),
        ]);
    }

    /**
     * @Route("/home/service/new", name="service_new_front", methods={"GET","POST"})
     */
    public function newFront(Request $request): Response
    {
        $service = new Service();
        $form = $this->createForm(ServiceType::class, $service);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($service);
            $entityManager->flush();

            return $this->redirectToRoute('service_index_front');
        }

        return $this->render('FrontInterface/service/new.html.twig', [
            'service' => $service,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/dashboard/service/{id}", name="service_show_Back", methods={"GET"})
     */
    public function showBack(Service $service): Response
    {
        return $this->render('BackInterface/service/show.html.twig', [
            'service' => $service,
        ]);
    }

    /**
     * @Route("/home/service/{id}", name="service_show_front", methods={"GET"})
     */
    public function showFront(Service $service): Response
    {
        return $this->render('FrontInterface/service/show.html.twig', [
            'service' => $service,
        ]);
    }

    /**
     * @Route("/home/service/{id}/edit", name="service_edit_front", methods={"GET","POST"})
     */
    public function editFront(Request $request, Service $service): Response
    {
        $form = $this->createForm(ServiceType::class, $service);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('service_index_front');
        }

        return $this->render('FrontInterface/service/edit.html.twig', [
            'service' => $service,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/dashboard/service/{id}", name="service_delete_Back", methods={"DELETE"})
     */
    public function deleteBack(Request $request, Service $service): Response
    {
        if ($this->isCsrfTokenValid('delete'.$service->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($service);
            $entityManager->flush();
        }

        return $this->redirectToRoute('service_index_back');
    }

    /**
     * @Route("/home/service/{id}", name="service_delete_front", methods={"DELETE"})
     */
    public function deleteFront(Request $request, Service $service): Response
    {
        if ($this->isCsrfTokenValid('delete'.$service->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($service);
            $entityManager->flush();
        }

        return $this->redirectToRoute('service_index_front');
    }

}
