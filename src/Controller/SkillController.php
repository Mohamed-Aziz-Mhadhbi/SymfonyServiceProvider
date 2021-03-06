<?php

namespace App\Controller;

use App\Entity\Skill;
use App\Form\SkillType;
use App\Repository\SkillRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class SkillController extends AbstractController
{
    /**
     * @Route("/admin/dashboard/skill", name="skill_index_back", methods={"GET"})
     */
    public function indexBack(SkillRepository $skillRepository): Response
    {
        return $this->render('BackInterface/skill/index.html.twig', [
            'skills' => $skillRepository->findAll(),
        ]);
    }

    /**
     * @Route("/home/skill", name="skill_index_front", methods={"GET"})
     */
    public function indexFront(SkillRepository $skillRepository): Response
    {
        return $this->render('FrontInterface/skill/index.html.twig', [
            'skills' => $skillRepository->findAll(),
        ]);
    }

    /**
     * @Route("/admin/dashboard/skill/new", name="skill_new_back", methods={"GET","POST"})
     */
    public function newBack(Request $request): Response
    {
        $skill = new Skill();
        $form = $this->createForm(SkillType::class, $skill);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($skill);
            $entityManager->flush();

            return $this->redirectToRoute('skill_index_back');
        }

        return $this->render('BackInterface/skill/new.html.twig', [
            'skill' => $skill,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/home/skill/new", name="skill_new_front", methods={"GET","POST"})
     */
    public function newFront(Request $request): Response
    {
        $skill = new Skill();
        $form = $this->createForm(SkillType::class, $skill);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($skill);
            $entityManager->flush();

            return $this->redirectToRoute('skill_index_front');
        }

        return $this->render('FrontInterface/skill/new.html.twig', [
            'skill' => $skill,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/dashboard/skill/{id}", name="skill_show_back", methods={"GET"})
     */
    public function showBack(Skill $skill): Response
    {
        return $this->render('BackInterface/skill/show.html.twig', [
            'skill' => $skill,
        ]);
    }

    /**
     * @Route("/home/skill/{id}", name="skill_show_front", methods={"GET"})
     */
    public function showFront(Skill $skill): Response
    {
        return $this->render('FrontInterface/skill/show.html.twig', [
            'skill' => $skill,
        ]);
    }

    /**
     * @Route("/admin/dashboard/skill/{id}/edit", name="skill_edit_back", methods={"GET","POST"})
     */
    public function editBack(Request $request, Skill $skill): Response
    {
        $form = $this->createForm(SkillType::class, $skill);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('skill_index_back');
        }

        return $this->render('BackInterface/skill/edit.html.twig', [
            'skill' => $skill,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/home/skill/{id}/edit", name="skill_edit_front", methods={"GET","POST"})
     */
    public function editFront(Request $request, Skill $skill): Response
    {
        $form = $this->createForm(SkillType::class, $skill);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('FrontInterface/skill_index_front');
        }

        return $this->render('FrontInterface/skill/edit.html.twig', [
            'skill' => $skill,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/dashboard/skill/{id}", name="skill_delete_back", methods={"DELETE"})
     */
    public function deleteBack(Request $request, Skill $skill): Response
    {
        if ($this->isCsrfTokenValid('delete'.$skill->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($skill);
            $entityManager->flush();
        }

        return $this->redirectToRoute('skill_index_back');
    }

    /**
     * @Route("/home/skill/{id}", name="skill_delete_front", methods={"DELETE"})
     */
    public function deleteFront(Request $request, Skill $skill): Response
    {
        if ($this->isCsrfTokenValid('delete'.$skill->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($skill);
            $entityManager->flush();
        }

        return $this->redirectToRoute('skill_index_front');
    }
}
