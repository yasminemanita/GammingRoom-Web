<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Entity\Evenement;
use App\Entity\Membre;
use App\Entity\Participant;
use App\Entity\Reactionev;
use App\Form\EvenementType;
use App\Repository\EvenementRepository;
use App\Repository\ParticipantRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Security\Core\Security;

class ParticipantsController extends AbstractController
{

    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
     * @Route("/calendar", name="particpant_calendar", methods={"GET"})
     */
    public function calendar(): Response
    {

        return $this->render('participants/calendar.html.twig');
    }

    /**
     * @Route("/participants", name="participants")
     */
    public function index(): Response
    {
        return $this->render('participants/index.html.twig', [
            'controller_name' => 'ParticipantsController',
        ]);
    }

    /**
     * @Route("/admin/listerPAdmin", name="listerPAdmin")
     */
    public function listerPAdmin(): Response
    {
        $participant=$this->getDoctrine()->getRepository(Participant::class)->findAll();
        return $this->render('participants/listerPAdmin.html.twig', [
            'participant' => $participant,
        ]);
    }

    /**
     * @Route("/event/rechreche",name="rechrecheEvent")
     */
    public function rechreche(Request $request, NormalizerInterface $Normalizer)
    {
        $repository = $this->getDoctrine()->getRepository(Evenement::class);

        $requestString = $request->get('searchValue');
        $c=$this->getDoctrine()->getRepository(Categorie::class)->findOneBy(array("nomcategorie"=>$requestString));
        $offres = $repository->findOffreByNsc($requestString,$c);
        $jsonContent = $Normalizer->normalize($offres, 'json');

        return new Response(json_encode($jsonContent));
    }

    /**
     * @Route("/listerP", name="listerP")
     */
    public function listerP(): Response
    {
        $participant=$this->getDoctrine()->getRepository(Participant::class)->findAll();
        return $this->render('participants/listerE.html.twig', [
            'participant' => $participant,
        ]);
    }

    /**
     * @Route("/listerE", name="listerE")
     */
    public function listerE(EvenementRepository $evenementRepository,Request $request,PaginatorInterface $paginator): Response
    {
        $evenements = $paginator->paginate(
        // Doctrine Query, not results
            $evenementRepository->findAll(),
            // Define the page parameter
            $request->query->getInt('page', 1),
            // Items per page
            5
        );

        $i=0;
        $nbParticipants=array();
        foreach ($evenements as $e){

            //TODO: get id from current connected member
            if(!$this->security->getUser()){
                $isParticpant[$i]="Inscription";
            }
            else{

                $idM=$this->security->getUser()->getId();
                $m=$this->getDoctrine()->getRepository(Membre::class)->find($idM);
                if((sizeof($this->getDoctrine()->getRepository(Participant::class)->findOneByME($e,$m)))<=0){
                    $isParticpant[$i]="Inscription";
                }
                else{
                    $isParticpant[$i]="Annuler";
                }
    
            }
            //get nbPaticipant
            $nbPart=($this->getDoctrine()->getRepository(Evenement::class)->getNBParticipants($e)[0])[1];
            $nbParticipants[$i]=$nbPart;

            $i++;


        }
        return $this->render('participants/listerE.html.twig', [
            'evenements' => $evenements,
            'isParticpant'=>$isParticpant,
            'size'=>sizeof($isParticpant)-1,
            'nbParticipants'=>$nbParticipants,
            'size'=>sizeof($nbParticipants)-1,
        ]);
    }


    /**
     * @Route("/upComingEvents", name="upComingEvents")
     */
    public function upComingEvents(EvenementRepository $evenementRepository,Request $request,PaginatorInterface $paginator): Response
    {
        $evenements = $paginator->paginate(
        // Doctrine Query, not results
            $evenementRepository->upComingEvents(),
            // Define the page parameter
            $request->query->getInt('page', 1),
            // Items per page
            5
        );
        $i=0;
        $isParticpant=array();
        $nbParticipants=array();
        foreach ($evenements as $e){

            //TODO: get id from current connected member
            if(!$this->security->getUser()){
                $isParticpant[$i]="Inscription";
            }
            else{

                $idM=$this->security->getUser()->getId();
                $m=$this->getDoctrine()->getRepository(Membre::class)->find($idM);
                if((sizeof($this->getDoctrine()->getRepository(Participant::class)->findOneByME($e,$m)))<=0){
                    $isParticpant[$i]="Inscription";
                }
                else{
                    $isParticpant[$i]="Annuler";
                }
            }

            //get nbPaticipant
            $nbPart=($this->getDoctrine()->getRepository(Evenement::class)->getNBParticipants($e)[0])[1];
            $nbParticipants[$i]=$nbPart;

            $i++;


        }
        return $this->render('participants/listerE.html.twig', [
            'evenements' => $evenements,
            'isParticpant'=>$isParticpant,
            'size'=>sizeof($isParticpant)-1,
            'nbParticipants'=>$nbParticipants,
            'size'=>sizeof($nbParticipants)-1,
        ]);
    }

    /**
     * @Route("/eventCat/{cat}", name="eventCat")
     */
    public function eventCat(EvenementRepository $evenementRepository,Request $request,PaginatorInterface $paginator,$cat): Response
    {
        $c=$this->getDoctrine()->getRepository(Categorie::class)->findBy(array("nomcategorie"=>$cat));
        $evenements = $paginator->paginate(
        // Doctrine Query, not results
            $evenementRepository->eventCat($c),
            // Define the page parameter
            $request->query->getInt('page', 1),
            // Items per page
            5
        );
        $i=0;
        $isParticpant=array();
        $nbParticipants=array();
        foreach ($evenements as $e){

            //TODO: get id from current connected member
            if(!$this->security->getUser()){
                $isParticpant[$i]="Inscription";
            }
            else{

                $idM=$this->security->getUser()->getId();
                $m=$this->getDoctrine()->getRepository(Membre::class)->find($idM);
                if((sizeof($this->getDoctrine()->getRepository(Participant::class)->findOneByME($e,$m)))<=0){
                    $isParticpant[$i]="Inscription";
                }
                else{
                    $isParticpant[$i]="Annuler";
                }
            }

            //get nbPaticipant
            $nbPart=($this->getDoctrine()->getRepository(Evenement::class)->getNBParticipants($e)[0])[1];
            $nbParticipants[$i]=$nbPart;

            $i++;


        }
        return $this->render('participants/listerE.html.twig', [
            'evenements' => $evenements,
            'isParticpant'=>$isParticpant,
            'size'=>sizeof($isParticpant)-1,
            'nbParticipants'=>$nbParticipants,
            'size'=>sizeof($nbParticipants)-1,
        ]);
    }

    /**
     * @Route("/new/{id}", name="actionParticipant")
     */
    public function new($id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $e=$this->getDoctrine()->getRepository(Evenement::class)->find($id);
        //TODO: get id from current connected member
        if(!$this->security->getUser()){
            return $this->redirect("/login");
        }
        $idM=$this->security->getUser()->getId();
        $m=$this->getDoctrine()->getRepository(Membre::class)->find($idM);
        if((sizeof($this->getDoctrine()->getRepository(Participant::class)->findOneByME($e,$m)))<=0){
            if((sizeof($this->getDoctrine()->getRepository(Participant::class)->findBy(array('evenement'=>$e))))< $e->getNbremaxParticipant()){
                $participant = new Participant();
                $participant->setEvenement($e);
                $participant->setMember($m);
                $participant->setRound(1);


                $entityManager->persist($participant);
                $entityManager->flush();

                //Metier: repartition des duels aleatoirment
                $listeParticipants=$this->getDoctrine()->getRepository(Participant::class)->eventParts($e);
                $memberListe=array();
                foreach ($listeParticipants as $value){
                    array_push($memberListe,$value->getMember());
                }


                $memberListeSize=sizeof($memberListe);
                $randomValues=array();
                $char ='A';
                $i=1;

                while ($memberListe){
                    $randIndex = array_rand($memberListe);
                    $randomElement=$memberListe[$randIndex];
                    $randomValues[$randomElement->getId()]=$char;
                    unset($memberListe[$randIndex]);
                    if($i %2 ==0){
                        $char++;
                    }
                    $i++;
                }

                foreach( $randomValues as $key => $value ){

                    $m=$this->getDoctrine()->getRepository(Membre::class)->find($key);
                    $this->getDoctrine()->getRepository(Participant::class)->repartitionDual($m,$value,$e);
                }
            }else{
                $this->addFlash('danger', 'saturée');
            }

        }
        else{
            $participant=($this->getDoctrine()->getRepository(Participant::class)->delete($e,$m));
        }


        return $this->redirectToRoute('listerE');



    }


    /**
     * @Route("/admin/updateRound/{id}", name="updateRound")
     */
    public function updateRound($id): Response
    {
        $this->getDoctrine()->getRepository(Participant::class)->updateRound($id);



        // actually executes the queries (i.e. the INSERT query)

       // return $this->redirectToRoute('evenement_index');

        return $this->json(['code'=>200,
            'message'=>'Round mise à jour',
            'rounds'=>$this->getDoctrine()->getRepository(Participant::class)->find($id)->getRound()],
            200);



    }

}
