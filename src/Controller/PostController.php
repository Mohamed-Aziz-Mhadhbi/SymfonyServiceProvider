<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Forum;
use App\Entity\Post;
use App\Entity\User;
use App\Form\CommentType;
use App\Form\PostType;
use App\Repository\CommentRepository;
use App\Repository\ForumRepository;
use App\Repository\PostRepository;
use App\Repository\TagRepository;
use MercurySeries\FlashyBundle\FlashyNotifier;
use phpDocumentor\Reflection\DocBlock\Tags\Uses;
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
     * @Route("/admin/dashboard/post}", name="post_index_back", methods={"GET"})
     */
    public function indexBack(PostRepository $postRepository): Response
    {

        return $this->render('BackInterface/post/index.html.twig', [
            'posts'=> $postRepository->findAll(),
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
     * @Route("/admin/dashboard/post/{id}", name="post_show_back", methods={"GET"})
     */
    public function showBack(Post $post): Response
    {
        return $this->render('BackInterface/post/show.html.twig', [
            'post' => $post,
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
    public function newFront(Request $request ,User $usr,FlashyNotifier $flashy): Response
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
            $flashy->success('Post Add!');
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
        $entityManager = $this->getDoctrine()->getManager();
        $arrayUsers = $entityManager->getRepository(User::class)->findAll();
        foreach ($arrayUsers as $item) {
            $arrayTags[] = $item->getUsername();
        }
        $str = "";
        foreach ($arrayTags as $tag){
            $str .= $tag . '/';

        }
        $post->setViews($post->getViews() + 1);
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
            $comment->setPst($post);
            $comment->setLikes(0);
            $comment->setUsr($this->getUser());
            $comment->setStatusLike(0);
            $post->setNoc($post->getNoc()+1);

            $this->addFlash(
                'success',
                "Your comment has been added !"
            );
            $entityManager->flush();

            return $this->redirectToRoute('post_show_front',array('id'=>$post->getId()));
        }

        $this->getDoctrine()->getManager()->flush();



        return $this->render('FrontInterface/post/Posts.html.twig', [
            'post' => $post,
            'tags' => $tagRepository->findAll(),
            'comments' => $commentRepository->findAll(),
            'user' => $user,
            'form' => $form->createView(),
            'strTags' => $str,
        ]);
    }


    /**
     * @Route("/home/post/{id}/edit", name="post_edit_front", methods={"GET","POST"})
     */
    public function editFront(Request $request, Post $post, TagRepository $tagRepository,Forum $forum,ForumRepository $forumRepository,PostRepository $postRepository): Response
    {
        $user = $this->security->getUser();
        $forum = $post->getFrm();
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('forum_show_front',array('id'=>$forum->getId()));
        }

        return $this->render('FrontInterface/forum/edit.html.twig', [
            'post' => $post,
            'form' => $form->createView(),
            'forum' => $forum,
            'forums' => $forumRepository->findAll(),
            'tags' => $tagRepository->findAll(),
            'posts' => $postRepository->findAll(),
            'user' => $user,
            'form' => $form->createView(),
            'post' => $post,
        ]);
    }


    /**
     * @Route("/home/post/{id}/like", name="clickLikes", methods={"GET","POST"})
     */
    public function clickLikes(Post $post) :Response
    {
        if ( $post->getReqUser() == NULL )
        {
            $post->setLikes($post->getLikes() + 1);
            $post->setStatusLike(true);
            $post->setReqUser($this->getUser());

            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('post_show_front',array('id'=>$post->getId()));
        }
        else if($post->getReqUser() == $this->getUser())
        {
            return $this->redirectToRoute('post_show_front',array('id'=>$post->getId()));
        }
        else
        {
            return $this->redirectToRoute('post_show_front',array('id'=>$post->getId()));
        }
    }

    /**
     * @Route("/home/post/{id}/like/comment", name="clickLikescomment", methods={"GET","POST"})
     */
    public function clickLikescomment(Comment $comment,Post $post)
    {
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
    }

    /**
     * @Route("/home/post/{id}", name="post_delete_font", methods={"DELETE"})
     */
    public function deleteFront(Request $request, Post $post,FlashyNotifier $flashy): Response
    {
        $Forum =$post->getFrm();
        if ($this->isCsrfTokenValid('delete'.$post->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($post);
            $entityManager->flush();
            $flashy->error('Post Deleted');
        }

        return $this->redirectToRoute('forum_show_front',array('id'=>$Forum->getId()));
    }
    /**
     * @Route("/home/comment/{id}", name="comment_delete_front", methods={"DELETE"})
     */
    public function deleteFrontComment(Request $request, Comment $comment): Response
    {
        $post = $comment->getPst();
        if ($this->isCsrfTokenValid('delete'.$comment->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($comment);
            $post->setNoc($post->getNOC()-1);
            $entityManager->flush();
        }
        return $this->redirectToRoute('post_show_front',array('id'=>$post->getId()));
    }

    /**
     * @Route("/home/comment/{id}/edit", name="comment_edit_front", methods={"GET","POST"})
     */
    public function editFrontComment(Request $request, Comment $comment ,TagRepository $tagRepository,CommentRepository $commentRepository): Response
    {
        $post = $comment->getPst();
        $user = $this->security->getUser();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            echo'ffffffffffffffffffffffffffffffffffsdfsdfqds';
            return $this->redirectToRoute('post_show_front',array('id'=>$post->getId()));
        }

        return $this->render('FrontInterface/comment/edit.html.twig', [
            'comment' => $comment,
            'form' => $form->createView(),
            'user' => $user,
            'post' => $post,
            'tags' => $tagRepository->findAll(),
            'comments' => $commentRepository->findAll(),
        ]);
    }
}
