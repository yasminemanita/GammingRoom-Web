<?php

namespace App\Controller;

use App\Entity\Evenement;
use App\Entity\Membre;
use App\Entity\Participant;
use App\Entity\Reactionev;
use App\Form\EvenementType;
use App\Form\ReactionevType;
use App\Repository\EvenementRepository;
use App\Repository\ReactionevRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Security\Core\Security;

class EvenementController extends AbstractController
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }
    /**
     * @Route("/admin/evenement", name="evenement_index", methods={"GET"})
     */
    public function index(EvenementRepository $evenementRepository,Request $request,PaginatorInterface $paginator): Response
    {

        $evenement = $paginator->paginate(
        // Doctrine Query, not results
            $evenementRepository->findAll(),
            // Define the page parameter
            $request->query->getInt('page', 1),
            // Items per page
            5
        );
        return $this->render('evenement/index.html.twig', [
            'evenements' => $evenement,
        ]);
    }


    /**
     * @Route("/evenement/showEventFront/{id}", name="show_EventFront")
     */
    public function showEventFront($id,Request $request): Response
    {

        $evenement=$this->getDoctrine()->getRepository(Evenement::class)->find($id);
        //dd($this->getDoctrine()->getRepository(Reactionev::class)->findBy(array("evenement"=>$evenement)));
        $nbParticipants=$this->getDoctrine()->getRepository(Evenement::class)->getNBParticipants($evenement);
        $NBLikes=$this->getDoctrine()->getRepository(Reactionev::class)->getNBLikes($evenement);
        $NBDislikes=$this->getDoctrine()->getRepository(Reactionev::class)->getNBDislikes($evenement);
        $Commentaires=$this->getDoctrine()->getRepository(Reactionev::class)->getCommentaires($evenement);
        //TODO get This user $m
        $m= $this->security->getUser();
        $isLikedByUser=(($this->getDoctrine()->getRepository(Reactionev::class)->isLikedByUser($m,$evenement))[0])[1]>0;
        $isDislikedByUser=(($this->getDoctrine()->getRepository(Reactionev::class)->isDislikedByUser($m,$evenement))[0])[1]>0;

        /***commentaire*/
        $reactionev = new Reactionev();
        $form = $this->createForm(ReactionevType::class, $reactionev);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            //TODO:$m
            if(!$m){
                return $this->redirect('/login');
            }
            $reactionev->setMembre($m);
            $reactionev->setEvenement($evenement);
            $reactionev->setInteraction(0);
            $entityManager->persist($reactionev);
            $entityManager->flush();

            return $this->redirectToRoute('show_EventFront',['id'=>$id]);
        }


        /***maches*/
        $maches=$this->getDoctrine()->getRepository(Participant::class)->eventParts($id);
        $distMachesResultset=$this->getDoctrine()->getRepository(Participant::class)->distEventParts($id);
        $i=0;
        $distMaches=array();
        while ($i<sizeof($distMachesResultset)/2){
            $distMaches[$i]=$distMachesResultset[$i];
            $i++;
        }
        /*****/
        
        $urlId=substr($evenement->getLienyoutube(),strrpos($evenement->getLienyoutube(),'/')+1);
        return $this->render('evenement/showEventFront.html.twig', [
            'evenement' => $evenement,
            'urlId'=>$urlId,
            'nbP'=>($nbParticipants[0])[1],
            'NBLikes'=>($NBLikes[0])[1],
            'NBDislikes'=>($NBDislikes[0])[1],
            'Commentaires'=>$Commentaires,
            'isLikedByUser'=>$isLikedByUser,
            'isDislikedByUser'=>$isDislikedByUser,
            'reactionev' => $reactionev,
            'form' => $form->createView(),
            'maches'=>$maches,
            'distMaches'=>$distMaches

        ]);
    }


    /**
     * @Route("/admin/evenement/new", name="evenement_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $evenement = new Evenement();
        $form = $this->createForm(EvenementType::class, $evenement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

                $file = $form->get('image')->getData();
                $fileName = bin2hex(random_bytes(6)).'.'.$file->guessExtension();
                $file->move ($this->getParameter('images_directory'),$fileName);
                $evenement->setImage($fileName);

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($evenement);
                $entityManager->flush();

                return $this->redirectToRoute('evenement_index');
        }

        return $this->render('evenement/new.html.twig', [
            'evenement' => $evenement,
            'form' => $form->createView(),
        ]);
        }


    /*public function new(Request $request, SluggerInterface $slugger): Response
    {
        $evenement = new Evenement();
        $form = $this->createForm(EvenementType::class, $evenement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $brochureFile = $form->get('brochure')->getData();

            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($brochureFile) {
                $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $brochureFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $brochureFile->move(
                        $this->getParameter('brochures_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $evenement->setImage($newFilename);

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($evenement);
                $entityManager->flush();

                return $this->redirectToRoute('evenement_index');
            }

            return $this->render('evenement/new.html.twig', [
                'evenement' => $evenement,
                'form' => $form->createView(),
            ]);
        }
    }
    */

    /**
     * @Route("/evenement/showEventFront/5/{id}/like", name="evenement_like")
     */
    public function evenement_like($id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        //TODO: $m
        $m= $this->security->getUser();
        if(!$m){
            return $this->json(['code'=>403, 'message'=>'Unauthorized'],403);
        }
        //$this->getDoctrine()->getRepository(Reactionev::class)->unlikeEvent($m,$e);
        //TODO: $m
        $e=$this->getDoctrine()->getRepository(Evenement::class)->find($id);


        if((($this->getDoctrine()->getRepository(Reactionev::class)->isLikedByUser($m,$e))[0])[1]>0){
            $event=$this->getDoctrine()->getRepository(Reactionev::class)->findOneBy(array("membre"=>$m,"evenement"=>$e,"interaction"=>1));

            $entityManager->remove($event);
            $entityManager->flush();
            return $this->json(['code'=>200,
                'message'=>'like bien supprimé',
                'likes'=>$this->getDoctrine()->getRepository(Reactionev::class)->count(array("evenement"=>$e,"interaction"=>1)),
                'dislikes'=>$this->getDoctrine()->getRepository(Reactionev::class)->count(array("evenement"=>$e,"interaction"=>-1))],
                200);

        }

        if((($this->getDoctrine()->getRepository(Reactionev::class)->isDislikedByUser($m,$e))[0])[1]>0){
            $event=$this->getDoctrine()->getRepository(Reactionev::class)->dislikeEvent($m,$e);

            return $this->json(['code'=>200,
                'message'=>'dislike bien modifié en like',
                'likes'=>$this->getDoctrine()->getRepository(Reactionev::class)->count(array("evenement"=>$e,"interaction"=>1)),
                'dislikes'=>$this->getDoctrine()->getRepository(Reactionev::class)->count(array("evenement"=>$e,"interaction"=>-1))],
                200);

        }

        $like=new Reactionev();
        $like->setEvenement($e);
        $like->setMembre($m);
        $like->setInteraction(1);

        $entityManager->persist($like);
        $entityManager->flush();

        return $this->json(['code'=>200,
            'message'=>'Like bien ajouté',
            'likes'=>$this->getDoctrine()->getRepository(Reactionev::class)->count(array("evenement"=>$e,"interaction"=>1)),
            'dislikes'=>$this->getDoctrine()->getRepository(Reactionev::class)->count(array("evenement"=>$e,"interaction"=>-1))],
            200);
    }

    /**
     * @Route("/evenement/showEventFront/5/{id}/disLike", name="evenement_disLike")
     */
    public function evenement_disLike($id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        //TODO: $m
        $m=$this->security->getUser();
        if(!$m){
            return $this->json(['code'=>403, 'message'=>'Unauthorized'],403);
        }
        //$this->getDoctrine()->getRepository(Reactionev::class)->unlikeEvent($m,$e);
        //TODO: $m
        $e=$this->getDoctrine()->getRepository(Evenement::class)->find($id);
        if((($this->getDoctrine()->getRepository(Reactionev::class)->isDislikedByUser($m,$e))[0])[1]>0){
            $event=$this->getDoctrine()->getRepository(Reactionev::class)->findOneBy(array("membre"=>$m,"evenement"=>$e,"interaction"=>-1));

            $entityManager->remove($event);
            $entityManager->flush();
            return $this->json(['code'=>200,
                'message'=>'disLike bien supprimé',
                'likes'=>$this->getDoctrine()->getRepository(Reactionev::class)->count(array("evenement"=>$e,"interaction"=>1)),
                'dislikes'=>$this->getDoctrine()->getRepository(Reactionev::class)->count(array("evenement"=>$e,"interaction"=>-1))],
                200);

        }

        if((($this->getDoctrine()->getRepository(Reactionev::class)->isLikedByUser($m,$e))[0])[1]>0){
            $event=$this->getDoctrine()->getRepository(Reactionev::class)->likeEvent($m,$e);

            return $this->json(['code'=>200,
                'message'=>'like bien modifié en dislike',
                'likes'=>$this->getDoctrine()->getRepository(Reactionev::class)->count(array("evenement"=>$e,"interaction"=>1)),
                'dislikes'=>$this->getDoctrine()->getRepository(Reactionev::class)->count(array("evenement"=>$e,"interaction"=>-1))],
                200);

        }

        $dislike=new Reactionev();
        $dislike->setEvenement($e);
        $dislike->setMembre($m);
        $dislike->setInteraction(-1);

        $entityManager->persist($dislike);
        $entityManager->flush();

        return $this->json(['code'=>200,
            'message'=>'disLike bien ajouté',
            'likes'=>$this->getDoctrine()->getRepository(Reactionev::class)->count(array("evenement"=>$e,"interaction"=>1)),
            'dislikes'=>$this->getDoctrine()->getRepository(Reactionev::class)->count(array("evenement"=>$e,"interaction"=>-1))],
            200);
    }






    /**
     * @Route("/admin/evenement/{idevent}", name="evenement_show", methods={"GET"})
     */
    public function show(Evenement $evenement): Response
    {
        return $this->render('evenement/show.html.twig', [
            'evenement' => $evenement,
        ]);
    }

    /**
     * @Route("/admin/evenement/{idevent}/edit", name="evenement_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Evenement $evenement): Response
    {
        $form = $this->createForm(EvenementType::class, $evenement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $file = $form->get('image')->getData();
            $fileName = bin2hex(random_bytes(6)).'.'.$file->guessExtension();
            $file->move ($this->getParameter('images_directory'),$fileName);
            $evenement->setImage($fileName);


            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('evenement_index');
        }

        return $this->render('evenement/edit.html.twig', [
            'evenement' => $evenement,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/evenement/{idevent}", name="evenement_delete", methods={"POST"})
     */
    public function delete(Request $request, Evenement $evenement): Response
    {
        if ($this->isCsrfTokenValid('delete'.$evenement->getIdevent(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($evenement);
            $entityManager->flush();
        }

        return $this->redirectToRoute('evenement_index');
    }
}
