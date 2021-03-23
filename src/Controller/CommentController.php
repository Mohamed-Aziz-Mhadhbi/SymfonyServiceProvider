<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Post;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;


class CommentController extends AbstractController
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
     * @Route("/admin/dashboard/comment", name="comment_index_back", methods={"GET"})
     */
    public function indexBack(CommentRepository $commentRepository): Response
    {
        return $this->render('BackInterface/comment/index.html.twig', [
            'comments' => $commentRepository->findAll(),
        ]);
    }

    /**
     * @Route("/home/comment/", name="comment_index_front", methods={"GET"})
     */
    public function indexFront(CommentRepository $commentRepository ,PostRepository $postRepository): Response
    {
        $user = $this->security->getUser();
        return $this->render('FrontInterface/comment/index.html.twig', [
            'comments' => $commentRepository->findAll(),
            'user' => $user,
            'posts' => $postRepository->findAll(),
        ]);
    }

    /**
     * @Route("/admin/dashboard/comment/new", name="comment_new_back", methods={"GET","POST"})
     */
    public function newBack(Request $request): Response
    {
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($comment);
            $entityManager->flush();

            return $this->redirectToRoute('comment_index_back');
        }

        return $this->render('BackInterface/comment/new.html.twig', [
            'comment' => $comment,
            'form' => $form->createView(),
        ]);
    }


   /* /**
     * @Route("/home/comment/new", name="comment_new_front", methods={"GET","POST"})
     */
    public function newFront(Request $request): Response
    {
        $user = $this->security->getUser();
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($comment);
            $comment->setLikes(0);
            $comment->setCreatAt(new \DateTime('now'));
            $post = $entityManager->getRepository(Post::class)->find($request->get('id'));
            $comment->setPost($post);

            $comment->setStatusLike(true);


            $comment->setPst();
            $comment->setLikes(0);
            $comment->setUsr($this->getUser());



            $entityManager->flush();

        }
        return $this->render('FrontInterface/comment/new.html.twig', [
            'comment' => $comment,
            'form' => $form->createView(),
            'user' => $user,
        ]);

    }


    /**
     * @Route("/admin/dashboard/comment/{id}", name="comment_show_back", methods={"GET"})
     */
    public function showBack(Comment $comment): Response
    {
        return $this->render('BackInterface/comment/show.html.twig', [
            'comment' => $comment,
        ]);
    }

    /**
     * @Route("/home/comment/{id}", name="comment_show_front", methods={"GET"})
     */
    public function showFront(Comment $comment): Response
    {
        $user = $this->security->getUser();
        return $this->render('FrontInterface/comment/show.html.twig', [
            'comment' => $comment,
            'user' => $user,
        ]);
    }

    /**
     * @Route("/admin/dashboard/comment/{id}/edit", name="comment_edit_back", methods={"GET","POST"})
     */
    public function editBack(Request $request, Comment $comment): Response
    {
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('comment_index_back');
        }

        return $this->render('BackInterface/comment/edit.html.twig', [
            'comment' => $comment,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/home/comment/{id}/edit", name="comment_edit_front", methods={"GET","POST"})
     */
    public function editFront(Request $request, Comment $comment): Response
    {
        $user = $this->security->getUser();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('comment_index_front');
        }

        return $this->render('FrontInterface/comment/edit.html.twig', [
            'comment' => $comment,
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }


    /**
     * @Route("/admin/dashboard/comment/{id}", name="comment_delete_back", methods={"DELETE"})
     */
    public function deleteBack(Request $request, Comment $comment): Response
    {
        if ($this->isCsrfTokenValid('delete'.$comment->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($comment);
            $entityManager->flush();
        }

        return $this->redirectToRoute('comment_index_back');
    }

    /**
     * @Route("/home/comment/{id}", name="comment_delete_front", methods={"DELETE"})
     */
    public function deleteFront(Request $request, Comment $comment): Response
    {
        if ($this->isCsrfTokenValid('delete'.$comment->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($comment);
            $entityManager->flush();
        }

        return $this->redirectToRoute('comment_index_front');
    }

}


