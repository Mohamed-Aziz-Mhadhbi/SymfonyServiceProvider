<?php

namespace App\Controller;

use Carbon\Carbon;
use App\Entity\Forum;
use App\Entity\Post;
use App\Form\PostType;
use App\Form\ForumType;
use App\Repository\CommentRepository;
use App\Repository\ForumRepository;
use App\Repository\PostRepository;
use App\Repository\TagRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class ForumController extends AbstractController
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
     * @Route("/admin/dashboard/forum/page/{page<\d+>?1}", name="forum_index_back", methods={"GET"})
     */
    public function indexBack(ForumRepository $forumRepository,$page): Response
    {
        $limit = 5;
        $start = $page * $limit - $limit;
        $total = count($forumRepository->findAll());
        $pages = ceil($total / $limit);

        return $this->render('BackInterface/forum/index.html.twig', [
            'forums' => $forumRepository->findBy([],[],$limit,$start),
            'pages' => $pages,
            'page' => $page,
        ]);
    }

    /**
     * @Route("/home/forum", name="forum_index_front", methods={"GET"})
     */
    public function forumFront(ForumRepository $forumRepository,TagRepository $tagRepository,PostRepository $postRepository): Response
    {
        return $this->render('FrontInterface/forum/forum.html.twig', [
            'forums' => $forumRepository->findAll(),
            'tags' => $tagRepository->findAll(),
            'posts' => $postRepository->findAll(),

        ]);
    }

    /**
     * @Route("/home/forums", name="forums_index_front", methods={"GET","POST"})
     */
    public function forumsFront(ForumRepository $forumRepository,Request $request): Response
    {
        $user = $this->security->getUser();
        $forum = new Forum();
        $form = $this->createForm(ForumType::class, $forum);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($forum);

            $forum->setCreatAt(new \DateTime('now'));
            $forum->setUs($this->getUser());

            $entityManager->flush();

            return $this->redirectToRoute('forums_index_front');
        }

        return $this->render('FrontInterface/forum/forums.html.twig', [
            'forum' => $forum,
            'form' => $form->createView(),
            'user' => $user,
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

            $forum->setCreatAt(new \DateTime('now'));
            $forum->setUs($this->getUser());

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
     * @Route("/home/forum/{id}", name="forum_show_front", methods={"GET","POST"})
     */
    public function showFront(Request $request,Forum $forum,ForumRepository $forumRepository,PostRepository $postRepository): Response
    {
        $user = $this->security->getUser();
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($post);

            $post->setUsr($this->getUser());
            $forum = $entityManager->getRepository(Forum::class)->find($request->get('id'));
            $post->setFrm($forum);
            $post->setLikes(0);
            $post->setNoc(0);
            $post->setViews(0);
            $post->setStatusLike(0);
            $post->setCreatAt(new \DateTime('now'));
          
            $entityManager->flush();
            return $this->redirectToRoute('forum_show_front',array('id'=>$forum->getId()));
        }
       
        return $this->render('FrontInterface/forum/forum.html.twig', [
            'forum' => $forum,
            'forums' => $forumRepository->findAll(),
            'posts' => $postRepository->findAll(),
            'user' => $user,
            'form' => $form->createView(),
            'post' => $post,
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
     * @Route("/home/post/{id}/edit", name="post_edit_front", methods={"GET","POST"})
     */
    public function editFrontPost(Request $request,Post $post,Forum $forum,ForumRepository $forumRepository,TagRepository $tagRepository,PostRepository $postRepository): Response
    {
        $Forum = $post->getFrm();
        $user = $this->security->getUser();
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('forum_show_front',array('id'=>$Forum->getId()));
        }

        return $this->render('FrontInterface/forum/forum.html.twig', [
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
     * @Route("/home/forum/{id}/edit", name="forum_edit_front", methods={"GET","POST"})
     */
    public function editFront(Request $request, Forum $forum ,ForumRepository $forumRepository): Response
    {
        $user = $this->security->getUser();
        $form = $this->createForm(ForumType::class, $forum);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('forums_index_front');
        }

        return $this->render('FrontInterface/forum/edit.html.twig', [
            'forum' => $forum,
            'form' => $form->createView(),
            'user' => $user,
            'forums' => $forumRepository->findAll(),
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

    /**
     * @Route("/home/forum/{id}", name="forum_delete_front", methods={"DELETE"})
     */
    public function deleteFront(Request $request, Forum $forum): Response
    {
        if ($this->isCsrfTokenValid('delete'.$forum->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($forum);
            $entityManager->flush();
        }

        return $this->redirectToRoute('forums_index_front');
    }

}
