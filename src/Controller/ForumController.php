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
     * @Route("/admin/dashboard/forum", name="forum_index", methods={"GET"})
     */
    public function index(ForumRepository $forumRepository): Response
    {
        return $this->render('forum/index.html.twig', [
            'forums' => $forumRepository->findAll(),
        ]);
    }
    /**
     * @Route("/forum", name="forum", methods={"GET"})
     */
    public function forumActive(ForumRepository $forumRepository,TagRepository $tagRepository,PostRepository $postRepository): Response
    {
        return $this->render('forum/forum.html.twig', [
            'forums' => $forumRepository->findAll(),
            'tags' => $tagRepository->findAll(),
            'posts' => $postRepository->findAll(),

        ]);
    }
    /**
     * @Route("/forums", name="forums", methods={"GET"})
     */
    public function forumsActive(ForumRepository $forumRepository): Response
    {
        return $this->render('forum/forums.html.twig', [
            'forums' => $forumRepository->findAll(),
        ]);
    }

    /**
     * @Route("/admin/dashboard/forum/new", name="forum_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $forum = new Forum();
        $form = $this->createForm(ForumType::class, $forum);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($forum);
            $entityManager->flush();

            return $this->redirectToRoute('forum_index');
        }

        return $this->render('forum/new.html.twig', [
            'forum' => $forum,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/dashboard/forum/{id}", name="forum_show", methods={"GET"})
     */
    public function show(Forum $forum): Response
    {
        return $this->render('forum/show.html.twig', [
            'forum' => $forum,
        ]);
    }

    /**
     * @Route("/admin/dashboard/forum/{id}/edit", name="forum_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Forum $forum): Response
    {
        $form = $this->createForm(ForumType::class, $forum);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('forum_index');
        }

        return $this->render('forum/edit.html.twig', [
            'forum' => $forum,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/dashboard/forum/{id}", name="forum_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Forum $forum): Response
    {
        if ($this->isCsrfTokenValid('delete'.$forum->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($forum);
            $entityManager->flush();
        }

        return $this->redirectToRoute('forum_index');
    }
}
