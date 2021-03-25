<?php

namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\CategorieType;
use App\Entity\Categorie;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;


class CategorieController extends AbstractController
{
    /**
     * @Route("/admin/dashboard/categorie/", name="categories")
     */
    public function index(CategorieRepository $cr): Response
    {
        $categories = $cr->findAll();

        return $this->render('categorie/index.html.twig', [
            'categories' => $categories,

        ]);
    }

    /**
     * @Route("/admin/dashboard/categorie/new", name="new_categorie")
     */
    function new(Request $request, EntityManagerInterface $em, ValidatorInterface $validator):Response{
        $categorie = new Categorie();
        $form = $this->createForm(CategorieType::class, $categorie);

        $form->handleRequest($request);
        if($form->isSubmitted()){

            $categorie = new Categorie();
            $categorie=$form->getData();
            $errors = $validator->validate($categorie);
            if (count($errors) > 0) {

                $errorsString = (string) $errors;

                return $this->render('categorie/new.html.twig', [
                    "form"=> $form->createView(),
                    "errors"=> $errors
                ]);
            }
            $em->persist($categorie);
            $em->flush();
            $this->addFlash('success', 'Categorie ajouté avec succés!');
                 $this->redirectToRoute('categories');
        }
        return $this->render('categorie/new.html.twig', [
            "form"=> $form->createView(),
        ]);

    }
    /**
     * @Route("/admin/dashboard/categorie/edit/{id}", name="edit_categorie")
     * @Method={"GET","POST"}
     */
    public function edit(Request $request, CategorieRepository $repository,  EntityManagerInterface $em,int  $id):Response{
        $categorie = $repository->findOneCategorieById($id);
        $form = $this->createForm(CategorieType::class, $categorie);
        $form-> handleRequest($request);
        if($form->isSubmitted()){
            $categorie = $form->getData();
            $em->flush();
            $this->addFlash('success', 'Categorie modifié avec succés!');
            return $this->redirectToRoute('categories');
        }
        return $this->render('categorie/edit.html.twig', [
            "form"=> $form->createView(),
        ]);
    }
    /**
     * @Route("/admin/dashboard/categorie/delete/{id}", name="delete_categorie")
     * @Method({"DELETE"})
     */
    public function delete(Request $req, int $id, CategorieRepository $cr, EntityManagerInterface $em){
        $categorie = $cr->findOneCategorieById($id);
        $em->remove($categorie);
        $em->flush();
        $this->addFlash('success', 'Categorie supprimé avec succés!');
        return $this->redirectToRoute('categories');
    }
    protected function forward(string $controller, array $path = [], array $query = []): Response
    {
    }
}
