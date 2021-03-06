<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use App\Repository\PostRepository;
use App\Repository\TagRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{
    /**
     * @Route("/admin/dashboard/post", name="post_index_back", methods={"GET"})
     */
    public function indexBack(PostRepository $postRepository): Response
    {
        return $this->render('BackInterface/post/index.html.twig', [
            'posts' => $postRepository->findAll(),
        ]);
    }


    /**
     * @Route("/admin/dashboard/post/new", name="post_new_back", methods={"GET","POST"})
     */
    public function newBack(Request $request): Response
    {
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($post);
            $entityManager->flush();

            return $this->redirectToRoute('post_index_back');
        }

        return $this->render('BackInterface/post/new.html.twig', [
            'post' => $post,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/dashboard/post/{id}", name="post_show_back", methods={"GET"})
     */
    public function showBack(Post $post): Response
    {
        return $this->render('BackInterface/post/show.html.twig', [
            'post' => $post,
        ]);
    }


    /**
     * @Route("/admin/dashboard/post/{id}/edit", name="post_edit_back", methods={"GET","POST"})
     */
    public function editBack(Request $request, Post $post): Response
    {
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('post_index_back');
        }

        return $this->render('BackInterface/post/edit.html.twig', [
            'post' => $post,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/dashboard/post/{id}", name="post_delete_back", methods={"DELETE"})
     */
    public function deleteBack(Request $request, Post $post): Response
    {
        if ($this->isCsrfTokenValid('delete'.$post->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($post);
            $entityManager->flush();
        }

        return $this->redirectToRoute('post_index_back');
    }
}
