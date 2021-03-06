<?php

namespace App\Controller;

use App\Entity\Forum;
use App\Form\ForumType;
use App\Repository\CommentRepository;
use App\Repository\ForumRepository;
use App\Repository\PostRepository;
use App\Repository\TagRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ForumController extends AbstractController
{
    /**
     * @Route("/admin/dashboard/forum", name="forum_index_back", methods={"GET"})
     */
    public function indexBack(ForumRepository $forumRepository): Response
    {
        return $this->render('BackInterface/forum/index.html.twig', [
            'forums' => $forumRepository->findAll(),
        ]);
    }

    /**
     * @Route("/admin/dashboard/forum/new", name="forum_new_back", methods={"GET","POST"})
     */
    public function newBack(Request $request): Response
    {
        $forum = new Forum();
        $form = $this->createForm(ForumType::class, $forum);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($forum);
            $entityManager->flush();

            return $this->redirectToRoute('forum_index_back');
        }

        return $this->render('BackInterface/forum/new.html.twig', [
            'forum' => $forum,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/dashboard/forum/{id}", name="forum_show_back", methods={"GET"})
     */
    public function showBack(Forum $forum): Response
    {
        return $this->render('BackInterface/forum/show.html.twig', [
            'forum' => $forum,
        ]);
    }

    /**
     * @Route("/admin/dashboard/forum/{id}/edit", name="forum_edit_Back", methods={"GET","POST"})
     */
    public function editBack(Request $request, Forum $forum): Response
    {
        $form = $this->createForm(ForumType::class, $forum);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('forum_index_back');
        }

        return $this->render('BackInterface/forum/edit.html.twig', [
            'forum' => $forum,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/dashboard/forum/{id}", name="forum_delete_back", methods={"DELETE"})
     */
    public function deleteBack(Request $request, Forum $forum): Response
    {
        if ($this->isCsrfTokenValid('delete'.$forum->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($forum);
            $entityManager->flush();
        }

        return $this->redirectToRoute('forum_index_back');
    }
}
