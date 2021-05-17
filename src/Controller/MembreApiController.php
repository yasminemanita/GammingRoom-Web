<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\Membre;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Routing\Annotation\Route;

class MembreApiController extends AbstractController
{
    /**
     * @Route("/membre/api/login", name="membre_login")
     */
    public function signIn(Request $request): Response
    {
        $email = $request->query->get('email');
        $password = $request->query->get('password');

        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(Membre::class)->findOneBy(['email'=>$email]);//nlawej ala user b email
        //kan lkitah fl base
        if($user && $user->getRole() != 'Admin'){
            if($user->getRole() == 'Coach' && $user->getActive()==0 && $user->getBanDuration()==0 && $user->getLastTimeban()==null)
            {
                return new Response("votre compte est enregistré veuillez attendre l'activation de l'admin");
            }
            elseif ($user->getActive()==0 && $user->getBanDuration()>0 &&  $user->getLastTimeban()!=null)
            {
                return new Response("votre compte est désactivé ");
            }
            elseif(password_verify($password,$user->getPassword())) {
                $serializer = new Serializer([new ObjectNormalizer()]);
                $formatted = $serializer->normalize($user);
                return new JsonResponse($formatted);
            }
            else{
                return new Response("password not found");
            }
        }
        else{
            return new Response("email not found");
        }




    }

}
