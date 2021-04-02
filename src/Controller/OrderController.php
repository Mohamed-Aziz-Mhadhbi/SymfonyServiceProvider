<?php

namespace App\Controller;

use App\Entity\Order;
use App\Form\OrderType;
use App\Repository\OrderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use App\Repository\ServiceRepository;


class OrderController extends AbstractController
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
        $user = $this->security->getUser();
        return $this->render('FrontInterface/order/index.html.twig', [
            'orders' => $orderRepository->findAll(),
            'user' => $user,
        ]);
    }

    /**
     * @Route("/home/order/new", name="order_new_front", methods={"GET","POST"})
     */
    public function newFront(Request $request, \Swift_Mailer $mailer, ServiceRepository $serviceRepository): Response
    {
        $service = $serviceRepository->find($_GET["id"]);
        $user = $this->security->getUser();
        $order = new Order();
        $form = $this->createForm(OrderType::class, $order);
        $form->handleRequest($request);
        // $em=$this->getDoctrine()->getManager();
        //$s = $ee->Selectmail();
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();


            $message = (new \Swift_Message('Hello '))
                ->setFrom('service.provider.time@gmail.com')
                //->setTo($s)
                ->setTo('nourallah.souidene@esprit.tn')
                ->setBody(
                    $this->renderView(

                        'FrontInterface/order/mail.html.twig',

                    ),
                    'text/html'
                );
            $mailer->send($message);
            $entityManager->persist($order);
            $entityManager->flush();

            return $this->redirectToRoute('service_show_front',array($service->getId()));
        }

        return $this->render('FrontInterface/order/new.html.twig', [
            'order' => $order,
            'form' => $form->createView(),
            'user' => $user,
            'service' => $service,
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
