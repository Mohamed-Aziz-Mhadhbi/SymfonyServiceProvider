<?php

namespace App\Controller;

use App\Entity\Order;
use App\Form\OrderType;
use App\Repository\OrderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class OrderController extends AbstractController
{
    /**
     * @Route("/admin/dashboard/order", name="order_index_back", methods={"GET"})
     */
    public function indexBack(OrderRepository $orderRepository): Response
    {
        return $this->render('BackInterface/order/index.html.twig', [
            'orders' => $orderRepository->findAll(),
        ]);
    }

    /**
     * @Route("/home/order", name="order_index", methods={"GET"})
     */
    public function indexFront(OrderRepository $orderRepository): Response
    {
        return $this->render('FrontInterface/order/index.html.twig', [
            'orders' => $orderRepository->findAll(),
        ]);
    }

    /**
     * @Route("/home/order/new", name="order_new_front", methods={"GET","POST"})
     */
    public function newFront(Request $request): Response
    {
        $order = new Order();
        $form = $this->createForm(OrderType::class, $order);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($order);
            $entityManager->flush();

            return $this->redirectToRoute('order_index_front');
        }

        return $this->render('FrontInterface/order/new.html.twig', [
            'order' => $order,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/admin/dashboard/order/{id}", name="order_show_back", methods={"GET"})
     */
    public function showBack(Order $order): Response
    {
        return $this->render('BackInterface/order/show.html.twig', [
            'order' => $order,
        ]);
    }

    /**
     * @Route("/home/order/{id}", name="order_show_front", methods={"GET"})
     */
    public function showFront(Order $order): Response
    {
        return $this->render('FrontInterface/order/show.html.twig', [
            'order' => $order,
        ]);
    }

    /**
     * @Route("/home/order/{id}/edit", name="order_edit_front", methods={"GET","POST"})
     */
    public function editFront(Request $request, Order $order): Response
    {
        $form = $this->createForm(OrderType::class, $order);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('order_index_front');
        }

        return $this->render('FrontInterface/order/edit.html.twig', [
            'order' => $order,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/dashboard/order/{id}", name="order_delete_back", methods={"DELETE"})
     */
    public function deleteBack(Request $request, Order $order): Response
    {
        if ($this->isCsrfTokenValid('delete'.$order->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($order);
            $entityManager->flush();
        }

        return $this->redirectToRoute('order_index_back');
    }
    /**
     * @Route("/home/order/{id}", name="order_delete_front", methods={"DELETE"})
     */
    public function deleteFront(Request $request, Order $order): Response
    {
        if ($this->isCsrfTokenValid('delete'.$order->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($order);
            $entityManager->flush();
        }

        return $this->redirectToRoute('order_index_front');
    }
}
