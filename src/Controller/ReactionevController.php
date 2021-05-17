<?php

namespace App\Controller;

use App\Entity\Reactionev;
use App\Form\ReactionevType;
use App\Repository\ReactionevRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/reactionev")
 */
class ReactionevController extends AbstractController
{
    /**
     * @Route("/", name="reactionev_index", methods={"GET"})
     */
    public function index(ReactionevRepository $reactionevRepository): Response
    {
        return $this->render('reactionev/index.html.twig', [
            'reactionevs' => $reactionevRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="reactionev_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $reactionev = new Reactionev();
        $form = $this->createForm(ReactionevType::class, $reactionev);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($reactionev);
            $entityManager->flush();

            return $this->redirectToRoute('reactionev_index');
        }

        return $this->render('reactionev/new.html.twig', [
            'reactionev' => $reactionev,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="reactionev_show", methods={"GET"})
     */
    public function show(Reactionev $reactionev): Response
    {
        return $this->render('reactionev/show.html.twig', [
            'reactionev' => $reactionev,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="reactionev_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Reactionev $reactionev): Response
    {
        $form = $this->createForm(ReactionevType::class, $reactionev);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('reactionev_index');
        }

        return $this->render('reactionev/edit.html.twig', [
            'reactionev' => $reactionev,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="reactionev_delete", methods={"POST"})
     */
    public function delete(Request $request, Reactionev $reactionev): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reactionev->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($reactionev);
            $entityManager->flush();
        }

        return $this->redirectToRoute('reactionev_index');
    }
}
