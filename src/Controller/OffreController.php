<?php

namespace App\Controller;

use App\Entity\Offre;
use App\Form\OffreType;
use App\Repository\OffreRepository;
use App\Repository\PostLikeRepository;
use App\Repository\PostulationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Ob\HighchartsBundle\Highcharts\Highchart;
use App\Entity\PostLike;


class OffreController extends AbstractController
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
     * @Route("/admin/dashboard/offre", name="offre_index_back", methods={"GET"})
     */
    public function indexBack(OffreRepository $offreRepository): Response
    {
        return $this->render('BackInterface/offre/index.html.twig', [
            'offres' => $offreRepository->findAll(),
        ]);
    }

    /**
     * @Route("/home/offre", name="offre_index_front", methods={"GET"})
     */
    public function indexFront(OffreRepository $offreRepository): Response
    {
        $user = $this->security->getUser();
        return $this->render('FrontInterface/offre/index.html.twig', [
            'offres' => $offreRepository->findAll(),
            'user' => $user
        ]);
    }

    /**
     * @Route("/home/offre/new", name="offre_new_front", methods={"GET","POST"})
     */
    public function newFront(Request $request): Response
    {

        $user = $this->security->getUser();
        $offre = new Offre();
        $form = $this->createForm(OffreType::class, $offre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $offre->setUser($user);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($offre);
            $entityManager->flush();

            return $this->redirectToRoute('offre_index_front');
        }

        return $this->render('FrontInterface/offre/new.html.twig', [
            'offre' => $offre,
            'form' => $form->createView(),
            'user' => $user
        ]);
    }


    /**
     * @Route("/admin/dashboard/offre/{id}", name="offre_show_back", methods={"GET"})
     */
    public function showBack(Offre $offre): Response
    {
        return $this->render('BackInterface/offre/show.html.twig', [
            'offre' => $offre,
        ]);
    }

    /**
     * @Route("/home/offre/{id}", name="offre_show_front", methods={"GET"})
     */
    public function showFront(Offre $offre,PostulationRepository $postulationRepository): Response
    {
        $postulations = $postulationRepository->findBy(array('offre' => $offre->getId()));
        $user = $this->security->getUser();
        return $this->render('FrontInterface/offre/show.html.twig', [
            'offre' => $offre,
            'postulations' =>$postulations,
            'user' => $user
        ]);
    }

    /**
     * @Route("/home/offre/{id}/edit", name="offre_edit_front", methods={"GET","POST"})
     */
    public function editFront(Request $request, Offre $offre): Response
    {
        $user = $this->security->getUser();
        $form = $this->createForm(OffreType::class, $offre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('offre_index_front');
        }

        return $this->render('FrontInterface/offre/edit.html.twig', [
            'offre' => $offre,
            'form' => $form->createView(),
            'user' => $user
        ]);
    }

    /**
     * @Route("/admin/dashboard/offre/{id}", name="offre_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Offre $offre): Response
    {
        if ($this->isCsrfTokenValid('delete'.$offre->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($offre);
            $entityManager->flush();
        }

        return $this->redirectToRoute('offre_index_back');
    }

    /**
     * @Route("/home/offre/{id}", name="offre_delete_front", methods={"DELETE"})
     */
    public function deleteFront(Request $request, Offre $offre): Response
    {
        if ($this->isCsrfTokenValid('delete'.$offre->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($offre);
            $entityManager->flush();
        }

        return $this->redirectToRoute('offre_index_front');
    }

    /**
     * @param OffreRepository $repository
     * @param Request $request
     * @return Response
     * @Route("/offre/recherche",name="recherche")
     */
    public function Recherche(OffreRepository $repository,Request $request)
    {
        $user = $this->security->getUser();
        $data=$request->get('search');
        $em=$repository->search($data);

        return $this->render('FrontInterface/offre/index.html.twig',[
            'offres'=>$em,
            'user' => $user
        ]);
    }

    /**
     * @return Response
     * @Route ("/BackInterface/offre/statistique",name="statistique")
     */
    public function statistique(OffreRepository $repo): Response
    {
        $ob = new Highchart();
        $ob->chart->renderTo('linechart');
        $ob->title->text('Browser market shares at a specific website in 2010');
        $ob->plotOptions->pie(array(
            'allowPointSelect'  => true,
            'cursor'    => 'pointer',
            'dataLabels'    => array('enabled' => false),
            'showInLegend'  => true
        ));
        $offre=$repo->stat1();
        $data =array();
        foreach ($offre as $values)
        {
            $a =array($values['domain_offer_id'],intval($values['nbdom']));
            array_push($data,$a);
        }

        $ob->series(array(array('type' => 'pie','name' => 'Browser share', 'data' => $data)));
        return $this->render('BackInterface/offre/statitstique.html.twig', array(
            'chart' => $ob
        ));


    }
    /**
     * @Route ("/post/{id}/like",name="post_like")
     * @param Offre $offre
     * @param PostLikeRepository $likerepo
     *
     * @return Response
     */
    public function like (Offre $offre,PostLikeRepository $likerepo,EntityManagerInterface $entityManager):Response
    { $user=$this->getUser();
        if(!$user) return $this->json([
            'code'=> 403,
            'message'=> "unsuthorised"
        ],403);

        if($offre->islikedByUser($user)){
            $like=$likerepo->findOneBy([
                'post'=>$offre,
                'user'=>$user
            ]);
            $entityManager->remove($like);
            $entityManager->flush();
            return $this->json(
                [
                    'code'=> 200,
                    'message'=>'like bien supprime',
                    'likes'=>$likerepo->count(['post'=>$offre])
                ],200

            );
        }
        $like =new Postlike();
        $like->setPost($offre)
            ->setUser($user);
        $entityManager->persist($like);
        $entityManager->flush();




        return $this->json(['code'=> 200, 'message'=>'clike bien ajoute','likes'=>$likerepo->count(['post'=>$offre])],200);

    }

}
