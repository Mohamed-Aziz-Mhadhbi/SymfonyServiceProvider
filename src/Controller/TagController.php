<?php

namespace App\Controller;

use App\Entity\Tag;
use App\Form\TagType;
use App\Repository\TagRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class TagController extends AbstractController
{
    /**
     * @Route("/admin/dashboard/tag", name="tag_index_back", methods={"GET"})
     */
    public function indexBack(TagRepository $tagRepository): Response
    {
        return $this->render('BackInterface/tag/index.html.twig', [
            'tags' => $tagRepository->findAll(),
        ]);
    }

    /**
     * @Route("/admin/dashboard/tag/new", name="tag_new_back", methods={"GET","POST"})
     */
    public function newBack(Request $request): Response
    {
        $tag = new Tag();
        $form = $this->createForm(TagType::class, $tag);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($tag);
            $entityManager->flush();

            return $this->redirectToRoute('tag_index_back');
        }

        return $this->render('BackInterface/tag/new.html.twig', [
            'tag' => $tag,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/dashboard/tag/{id}", name="tag_show_back", methods={"GET"})
     */
    public function showBack(Tag $tag): Response
    {
        return $this->render('BackInterface/tag/show.html.twig', [
            'tag' => $tag,
        ]);
    }

    /**
     * @Route("/admin/dashboard/tag/{id}/edit", name="tag_edit_back", methods={"GET","POST"})
     */
    public function editBack(Request $request, Tag $tag): Response
    {
        $form = $this->createForm(TagType::class, $tag);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('tag_index_back');
        }

        return $this->render('BackInterface/tag/edit.html.twig', [
            'tag' => $tag,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/dashboard/tag/{id}", name="tag_delete_back", methods={"DELETE"})
     */
    public function deleteBack(Request $request, Tag $tag): Response
    {
        if ($this->isCsrfTokenValid('delete'.$tag->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($tag);
            $entityManager->flush();
        }

        return $this->redirectToRoute('tag_index_back');
    }
}
