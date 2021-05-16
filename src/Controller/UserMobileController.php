<?php

namespace App\Controller;

use App\Entity\User;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Validator\Constraints\Json;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;


class UserMobileController extends AbstractController
{

    /******************Ajouter Reclamation*****************************************/
    /**
     * @Route("/addUser", name="add_user")
     */
    public function addUserAction(Request $request,NormalizerInterface $Normalizer)
    {
        $user = new User();
        $em = $this->getDoctrine()->getManager();

        $nom = $request->query->get("nom");
        $prenom = $request->query->get("prenom");
        $username = $request->query->get("username");
        $email = $request->query->get("email");
        $password = $request->query->get("password");
        $phone =$request->query->get("phone");
        $specialisation =$request->query->get("specialisation");

        $date = new \DateTime('now');

        $user->setUsername($username);
        $user->setNom($nom);
        $user->setPrenom($prenom);
        $user->setEmail($email);
        $user->setPassword($password);
        $user->setPhone($phone);
        $user->setSpecialisation($specialisation);
        $user->setCreatedAt($date);
        $user->setRole("prestataire");

        $em->persist($user);
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($user);
        return new JsonResponse($formatted);

    }

    /******************Supprimer Reclamation*****************************************/

    /**
     * @Route("/jsonUserDelete/{id}" , name="jsondelete")
     */
    public function deleteReclamationAction(Request $request, NormalizerInterface $Normalizer, $id) {

        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(User::class)->find($id);
        if($user!=null ) {
            $em->remove($user);
            $em->flush();

            $serialize = new Serializer([new ObjectNormalizer()]);
            $formatted = $serialize->normalize("Reclamation a ete supprimee avec success.");
            return new JsonResponse($formatted);

        }
        return new JsonResponse("id reclamation invalide.");


    }

    /******************Modifier Reclamation*****************************************/
    /**
     * @Route("/jsonUserUpdate/{id}", name="jsonupdate" )
     */
    public function modifierUserAction(Request $request, NormalizerInterface $Normalizer, $id) {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getDoctrine()->getManager()
            ->getRepository(User::class)
            ->find($id);

        $nom = $request->query->get("nom");
        $prenom = $request->query->get("prenom");
        $username = $request->query->get("username");
        $email = $request->query->get("email");
        $password = $request->query->get("password");
        $phone =$request->query->get("phone");
        $specialisation =$request->query->get("specialisation");

        $user->setUsername($username);
        $user->setNom($nom);
        $user->setPrenom($prenom);
        $user->setEmail($email);
        $user->setPassword($password);
        $user->setPhone($phone);
        $user->setSpecialisation($specialisation);


        $em->persist($user);
        $em->flush();
        $jsonContent = $Normalizer->normalize($user, 'json', ['groups' => 'post:read']);
        return new Response(json_encode($jsonContent));

    }



    /******************affichage Reclamation*****************************************/

    /**
     * @Route("/displayUsers", name="display_users")
     */
    public function allUsers(NormalizerInterface $Normalizer):Response
    {

        $users = $this->getDoctrine()->getManager()->getRepository(User::class)->findByRoleField("prestataire");
        //$serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $Normalizer->normalize($users, 'json', ['groups' => 'post:read']);

        return new Response(json_encode($formatted));

    }


    /******************Detail Reclamation*****************************************/

    /**
     * @Route("/jsonUserShow/{id}", name="jsonid" )
     */

    //Detail Reclamation
    public function detailUserAction(Request $request, $id, NormalizerInterface $Normalizer)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(User::class)->find($id);
        $jsonContent = $Normalizer->normalize($user, 'json', ['groups' => 'post:read']);

        return new Response(json_encode($jsonContent));
    }
}
