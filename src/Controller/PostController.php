<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Post;
use App\Entity\User;
use App\Form\CommentType;
use App\Form\PostType;
use App\Repository\CommentRepository;
use App\Repository\PostRepository;
use App\Repository\TagRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class PostController extends AbstractController
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
     * @Route("/admin/dashboard/post", name="post_index_back", methods={"GET"})
     */
    public function indexBack(PostRepository $postRepository): Response
    {
        return $this->render('BackInterface/post/index.html.twig', [
            'posts' => $postRepository->findAll(),
            
        ]);
    }

    /**
     * @Route("/home/post", name="post_index_front", methods={"GET"})
     */
    public function indexFont(PostRepository $postRepository,TagRepository $tagRepository,CommentRepository $commentRepository ): Response
    {
        $user = $this->security->getUser();
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        return $this->render('FrontInterface/post/index.html.twig', [
            'posts' => $postRepository->findAll(),
            'user'=> $user,
            'tags' => $tagRepository->findAll(),
            'comments' => $commentRepository->findAll(),
            'form' => $form->createView(),
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
            $post->setCreatAt(new \DateTime('now'));
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

    /**
     * @Route("/home/post/new", name="post_new_front", methods={"GET","POST"})
     */
    public function newFront(Request $request ,User $usr): Response
    {
        $user = $this->security->getUser();
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($post);
            $post->setLikes(0);
            $post->setNoc(0);
            $post->setViews(0);
            $post->setCreatAt(new \DateTime('now'));
            $entityManager->flush();

            return $this->redirectToRoute('post_index_front');
        }

        return $this->render('FrontInterface/post/new.html.twig', [
            'post' => $post,
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }

    /**
     * @Route("/home/post/{id}", name="post_show_front", methods={"GET","POST"})
     */
    public function showFront(Request $request , Post $post,TagRepository $tagRepository,CommentRepository $commentRepository ): Response
    {
        $user = $this->security->getUser();
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);
        $post->setViews($post->getViews()+1);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($comment);

            $comment->setLikes(0);
            $comment->setCreatAt(new \DateTime('now'));
            $post = $entityManager->getRepository(Post::class)->find($request->get('id'));
            $comment->setPst($post);
            $comment->setLikes(0);
            $comment->setUsr($this->getUser());
            $comment->setStatusLike(0);
            $post->setNoc($post->getNoc()+1);

            $entityManager->flush();
            return $this->redirectToRoute('post_show_front',array('id'=>$post->getId()));
        }

            
        return $this->render('FrontInterface/post/Posts.html.twig', [
            'post' => $post,
            'tags' => $tagRepository->findAll(),
            'comments' => $commentRepository->findAll(),
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/home/post/{id}/like", name="clickLikes", methods={"GET","POST"})
     */
    public function clickLikes( Post $post,TagRepository $tagRepository,CommentRepository $commentRepository)
    {
        $user = $this->security->getUser();
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);

        if ($post->getReqUser() != $this->getUser() )
        {
            $post->setLikes($post->getLikes() + 1);
            $post->setStatusLike(true);
            $post->setReqUser($this->getUser());
            

            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('post_show_front',array('id'=>$post->getId()));
        }
        else {
            return $this->redirectToRoute('post_show_front',array('id'=>$post->getId()));
        }

        return $this->render('FrontInterface/post/Posts.html.twig', [
            'post' => $post,
            'tags' => $tagRepository->findAll(),
            'comments' => $commentRepository->findAll(),
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/home/post/{id}/like/comment", name="clickLikescomment", methods={"GET","POST"})
     */
    public function clickLikescomment(Post $post,TagRepository $tagRepository,CommentRepository $commentRepository)
    {
        $user = $this->security->getUser();
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        if ($comment->getStatusLike() == true)
        {
            $comment->setLikes($comment->getLikes()-1);
            $comment->setStatusLike(false);
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('post_show_front',array('id'=>$post->getId()));
        }else
        {
            return $this->redirectToRoute('post_show_front',array('id'=>$post->getId()));
        }


        return $this->render('FrontInterface/post/Posts.html.twig', [
            'post' => $post,
            'tags' => $tagRepository->findAll(),
            'comments' => $commentRepository->findAll(),
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/home/post/{id}", name="post_delete_font", methods={"DELETE"})
     */
    public function deleteFront(Request $request, Post $post): Response
    {
        $user = $this->security->getUser();
        if ($this->isCsrfTokenValid('delete'.$post->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($post);
            $entityManager->flush();
        }
        return $this->redirectToRoute('post_show_front',array('id'=>$post->getId()));
    }
}
