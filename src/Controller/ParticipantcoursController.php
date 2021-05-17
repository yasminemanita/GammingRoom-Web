<?php

namespace App\Controller;

use App\Entity\Membre;
use App\Entity\Cour;
use App\Entity\Participantcours;
use App\Form\ParticipantcoursType;
use App\Repository\CourRepository;
use App\Repository\MembreRepository;
use App\Repository\ParticipantcoursRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

/**
 * @Route("/Participantcours")
 */
class ParticipantcoursController extends AbstractController
{

    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;

    }

    /**
     * @Route("/", name="participantcours_index", methods={"GET"})
     */
    public function index(ParticipantcoursRepository $participantcoursRepository): Response
    {
        return $this->render('participantcours/index.html.twig', [
            'participantcours' => $participantcoursRepository->findAll(),
        ]);
    }




    /**
     * @Route("/new/{id}", name="participantcours_new")
     */
    public function new($id): Response
    {
        $user = $this->security->getUser();
        if(!$user){
            return $this->redirect("/login");
        }
        $entityManager = $this->getDoctrine()->getManager();
        $e=$this->getDoctrine()->getRepository(Cour::class)->find($id);
        $m= $user;

        if((sizeof($this->getDoctrine()->getRepository(Participantcours::class)->findOneByME($e,$m)))<=0){
            $participant = new Participantcours();
            $participant->setCour($e);
            $participant->setMembre($m);


            // tell Doctrine you want to (eventually) save the Product (no queries yet)
            $entityManager->persist($participant);
            $entityManager->flush();
            $this->addFlash(
                'info', 'successful registration'
            );

        }

        return $this->redirectToRoute('cour_show', array('id'=>$id));

        // actually executes the queries (i.e. the INSERT query)


    }




    /*zeyda*/
    /**
     * @Route("/{id}", name="participantcours_delete", methods={"POST"})
     */
    public function delete(Request $request, Participantcours $participantcour,$id): Response
    {
        $e=$this->getDoctrine()->getRepository(Cour::class)->find($id);
        $m=$this->getDoctrine()->getRepository(Membre::class)->find(8);


        if ((sizeof($this->getDoctrine()->getRepository(Participantcours::class)->delete($e,$m)))<=0){
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($participantcour);
            $entityManager->flush();
            $this->addFlash(
                'info', 'Registration cancelled successfully'
            );

        }

        return $this->redirectToRoute('cour_show', array('id'=>$id));
    }

    /**
     * @Route("/{id}", name="participantcours_show", methods={"GET"})
     */
    public function show(Participantcours $participantcour): Response
    {
        return $this->render('participantcours/show.html.twig', [
            'participantcour' => $participantcour,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="participantcours_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Participantcours $participantcour): Response
    {
        $form = $this->createForm(ParticipantcoursType::class, $participantcour);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('participantcours_index');
        }

        return $this->render('participantcours/edit.html.twig', [
            'participantcour' => $participantcour,
            'form' => $form->createView(),
        ]);
    }




}
