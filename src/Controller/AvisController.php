<?php

namespace App\Controller;
use App\Entity\Avis;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AvisController extends AbstractController
{
    /**
     * @Route("/admin/avis", name="adminAvis")
     */
    public function index(): Response
    { 
        $avisRepository = $this->getDoctrine()->getRepository(Avis::class);
        $avis=$avisRepository->findAll();
        return $this->render('avis/index.html.twig', [
            'avis' => $avis
        ]);
    }
    
    /**
      * @Route("/admin/avis/supprimer/{id}", name="adminSupprimerAvis")
      */
     public function supprimer($id): Response
     {
         $avi=$this->getDoctrine()->getRepository(Avis::class)->find($id);
         $em=$this->getDoctrine()->getManager();
         $em->remove($avi);        
         $em->flush();
         $this->addFlash('success','Avis supprimés avec succès');
         return $this->redirectToRoute("adminAvis");
     }
}
