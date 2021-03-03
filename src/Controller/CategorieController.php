<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Repository\CategorieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\CategorieType;


class CategorieController extends AbstractController
{
    /**
     * @Route("/admin/dashboard/categorie/", name="categorie")
     */
    public function index(CategorieRepository $ca): Response
    {
        $categories = $ca->findAll();
        return $this->render('categorie/index.html.twig', [
            'categorie' => $categories,
        ]);
    }

 /**
     * @Route("/admin/dashboard/categorie/new", name="new_categorie")
     */
    function new(Request $request, EntityManagerInterface $em):Response{
        $categorie = new Categorie();
        $form = $this->createForm(QuestionType::class, $categorie);
       
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $categorie = new Categorie();
            $categorie = $form->getData();
            $em->persist($categorie);
            $em->flush();
            $this->addFlash('success', 'Categorie ajouté avec succés!');
            return $this->redirectToRoute('admin_dashboard');
        }
        return $this->render('question/new.html.twig', [
            "form"=> $form->createView(),
        ]);
    }
 /**
     * @Route("/admin/dashboard/categorie/{id}", name="edit_categorie")
     */
    public function edit(Request $request, CategorieRepository $ca,  EntityManagerInterface $em,int  $id):Response{
        $categorie = $ca->findOneQuestionById($id);
        $form = $this->createForm(QuestionType::class, $categorie);
        $form-> handleRequest($request);
        if($form->isSubmitted()){
            $categorie = $form->getData();
            $em->flush();
            $this->addFlash('success', 'Categorie modifié avec succés!');
            return $this->redirectToRoute('admin_dashboard');
      
        }
        return $this->render('categorie/edit.html.twig', [
            "form"=> $form->createView(),
        ]);
    }
    /**
     * @Route("/categorie/{id}", name="categorie", method="delete")
     */
   /* public function delete(int $id, CategorieRepository $ca, EntityManagerInterface $em){
        $cat = $ca->findOneCategorieById($id);
        $em->remove($cat);
        $em->flush();
        $this->addFlash('success', 'Categorie supprimé avec succés!');
    }
    protected function forward(string $controller, array $path = [], array $query = []): Response
    {
    }*/
}
