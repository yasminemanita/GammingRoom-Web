<?php

namespace App\Controller;

use App\Entity\Membre;
use App\Entity\Reclamation;
use App\Form\ReclamationType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;

class ReclamtionController extends AbstractController
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }
    /**
     * @Route("admin/reclamtion", name="adminReclamtion")
     */
    public function index(): Response
    {  
        $recRepository = $this->getDoctrine()->getRepository(Reclamation::class);
        $rec=$recRepository->findAll();
        return $this->render('reclamtion/index.html.twig', [
            'rec' => $rec,
        ]);
    }

    
   /**
     * @Route("/admin/reclamtion/supprimer/{id}", name="adminSupprimerReclamtion")
     */
    public function supprimer($id): Response
    {
        $rec=$this->getDoctrine()->getRepository(Reclamation::class)->find($id);
        $em=$this->getDoctrine()->getManager();
        $em->remove($rec);        
        $em->flush();
        $this->addFlash('success','Reclamtion supprimés avec succès');
        return $this->redirectToRoute("adminReclamtion");
    }

      /**
     * @Route("/reclamtion", name="adminaddReclamtion")
     */
    public function ajouter(Request $request): Response
    {  
        $rec= new Reclamation();
        $form = $this->createForm(ReclamationType::class, $rec);
        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user=$this->security->getUser();
            
            if(!$user){
                return $this->redirect('/login');
            }
            
            $rec->setMembre($user);
             
               
            
            $em=$this->getDoctrine()->getManager();
            $em->persist($rec);
            $em->flush();
            //TODO notif add succ
            $rec= new Reclamation();
            $form = $this->createForm(ReclamationType::class, $rec);
        }

        return $this->render('reclamtion/ajouter.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
