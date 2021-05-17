<?php

namespace App\Controller;

use App\Entity\Cour;
use App\Entity\Membre;
use App\Entity\Reactioncours;
use App\Form\ReactioncoursType;
use App\Repository\ReactioncoursRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

/**
 * @Route("/Reactioncours")
 */
class ReactioncoursController extends AbstractController
{

    
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;

    }
    /**
     * @Route("/", name="reactioncours_index", methods={"GET"})
     */
    public function index(ReactioncoursRepository $reactioncoursRepository): Response
    {
        return $this->render('reactioncours/index.html.twig', [
            'reactioncours' => $reactioncoursRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="reactioncours_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $reactioncour = new Reactioncours();
        $form = $this->createForm(ReactioncoursType::class, $reactioncour);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($reactioncour);
            $entityManager->flush();

            return $this->redirectToRoute('reactioncours_index');
        }

        return $this->render('reactioncours/new.html.twig', [
            'reactioncour' => $reactioncour,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="reactioncours_show", methods={"GET"})
     */
    public function show(Reactioncours $reactioncour): Response
    {
        return $this->render('reactioncours/show.html.twig', [
            'reactioncour' => $reactioncour,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="reactioncours_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Reactioncours $reactioncour): Response
    {
        $form = $this->createForm(ReactioncoursType::class, $reactioncour);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('reactioncours_index');
        }

        return $this->render('reactioncours/edit.html.twig', [
            'reactioncour' => $reactioncour,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="reactioncours_delete", methods={"POST"})
     */
    public function delete(Request $request, Reactioncours $reactioncour): Response
    {
        if ($this->isCsrfTokenValid('delete' . $reactioncour->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($reactioncour);
            $entityManager->flush();
        }

        return $this->redirectToRoute('reactioncours_index');
    }


    /**
     * @Route("/reaction/ajout", name="reaction")
     */
    public function like(Request $request)
    {
        $user = $this->security->getUser();
        if(!$user){
            $jsonContent['notFound'] = 404;
            return new Response(json_encode($jsonContent));
        }
        $idMembre = $user;

        $likeType = (int)$request->get('typeReactioncours');
        $idCour = $request->get('idCour');

        $haveReactioncours = $this->getDoctrine()->getRepository(Reactioncours::class)->haveLikeDislike(
            $this->getDoctrine()->getRepository(Cour::class)->find($idCour),
            $this->getDoctrine()->getRepository(Membre::class)->find($idMembre)
        );


        $this->addReactioncours($haveReactioncours, $likeType, "NULL", $idMembre, $idCour);


        $nombreObjets = $this->getDoctrine()->getRepository(Reactioncours::class)->nombreObjets($idCour);
        $nombreReactioncours = $this->getDoctrine()->getRepository(Reactioncours::class)->nombreLikes($idCour);
        if ($nombreReactioncours != 0) {
            $pourcentage = ($nombreReactioncours / $nombreObjets) * 100;
        } else {
            $pourcentage = 0;
        }

        $cour = $this->getDoctrine()->getManager()->getRepository(Cour::class)->find($idCour);
        $cour->setPourcentageLike($pourcentage);

        $repository = $this->getDoctrine()->getManager();
        $repository->persist($cour);
        $repository->flush();

        $jsonContent['nbLike'] = $nombreReactioncours;
        $jsonContent['nbDislike'] = ($nombreObjets - $nombreReactioncours);
        $jsonContent['pourcentage'] = $pourcentage;
        return new Response(json_encode($jsonContent));
    }

    /**
     * @Route("/commentaire/ajout", name="commentaire")
     */
    public function commentaire(Request $request)
    {
        $user = $this->security->getUser();
        if(!$user){
            $jsonContent['notFound'] = 404;
            return new Response(json_encode($jsonContent));
        }
        $idMembre = $user;


        $likeType = (int)$request->get('typeReactioncours');
        $idCour = $request->get('idCour');
        $contenuCommentaire = $request->get('contenuCommentaire');

        $this->addCommentaire($likeType, $contenuCommentaire, $idMembre, $idCour);

        $cour = $this->getDoctrine()->getManager()->getRepository(Cour::class)->find($idCour);

        $repository = $this->getDoctrine()->getManager();
        $repository->persist($cour);
        $repository->flush();

        $jsonContent['contenuCommentaire'] = $contenuCommentaire;
        $jsonContent['nomPrenom'] = $idMembre->getNom() . " " . $idMembre->getPrenom();
        return new Response(json_encode($jsonContent));
    }

    function addCommentaire($typeReactioncours, $contenuCommentaire, $membre, $courId)
    {
        $reactioncours = new Reactioncours();
        $reactioncours->setInteraction($typeReactioncours);
        $cour = $this->getDoctrine()->getManager()->getRepository(Cour::class)->find($courId);

        $reactioncours->setCommentaire($contenuCommentaire);
        $reactioncours->setCour($cour);
        $reactioncours->setMembre($membre);

        $date = new DateTime('now', new \DateTimeZone('Africa/Tunis'));
        $reactioncours->setDateCreation($date);


        $repository = $this->getDoctrine()->getManager();
        $repository->persist($reactioncours);
        $repository->flush();

    }

    function addReactioncours($haveReactioncours, $typeReactioncours, $contenuCommentaire, $membre, $courId)
    {
        if ($haveReactioncours == null) {
            $reactioncours = new Reactioncours();
            $reactioncours->setInteraction($typeReactioncours);
            $cour = $this->getDoctrine()->getManager()->getRepository(Cour::class)->find($courId);

            $reactioncours->setCommentaire($contenuCommentaire);
            $reactioncours->setCour($cour);
            $reactioncours->setMembre($membre);

            $date = new DateTime('now', new \DateTimeZone('Africa/Tunis'));
            $reactioncours->setDateCreation($date);


            $repository = $this->getDoctrine()->getManager();
            $repository->persist($reactioncours);
            $repository->flush();

        } else {
            foreach ($haveReactioncours as $haveReactioncour) {
                $missionManager = $this->getDoctrine()->getManager();
                $missionManager->remove($haveReactioncour);
                $missionManager->flush();
            }
        }
    }


}
