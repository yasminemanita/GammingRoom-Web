<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Entity\CategoriMembre;
use App\Entity\Membre;
use App\Form\CategoriMembreType;
use App\Repository\CategoriMembreRepository;
use App\Repository\MembreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/categori/membre")
 */
class CategoriMembreController extends AbstractController
{
    /**
     * @Route("/", name="categori_membre_index", methods={"GET"})
     */
    public function index(CategoriMembreRepository $categoriMembreRepository): Response
    {
        return $this->render('categori_membre/index.html.twig', [
            'categori_membres' => $categoriMembreRepository->findAll(),
        ]);
    }

    /**
     * @Route ("/test/{idCategorie}", name="add_category", methods={"GET", "POST"})
 *
     */
    public function addOne(Request $request,  $idCategorie, MembreRepository $membreRepository,  CategoriMembreRepository $categoriMembreRepository):Response{
        $categoryMember = new CategoriMembre();
        $category = $this->getDoctrine()->getRepository(Categorie::class)->find($idCategorie); //bsh nhotha static juste bsh naamel test
   //
        $idUser = $membreRepository->selectLastRow();
        $membre = $this->getDoctrine()->getRepository(Membre::class)->find($idUser);
        $categoryMember->setMembre($membre);
        $categoryMember->setCategorie($category);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($categoryMember);
        $entityManager->flush();

        return $this->render('home/index.html.twig', [
            'categori_membres' => $categoriMembreRepository->findAll(),
        ]);

    }

    /**
     * @Route("/new", name="categori_membre_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $categoriMembre = new CategoriMembre();
        $form = $this->createForm(CategoriMembreType::class, $categoriMembre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($categoriMembre);
            $entityManager->flush();

            return $this->redirectToRoute('categori_membre_index');
        }

        return $this->render('categori_membre/new.html.twig', [
            'categori_membre' => $categoriMembre,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="categori_membre_show", methods={"GET"})
     */
    public function show(CategoriMembre $categoriMembre): Response
    {
        return $this->render('categori_membre/show.html.twig', [
            'categori_membre' => $categoriMembre,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="categori_membre_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, CategoriMembre $categoriMembre): Response
    {
        $form = $this->createForm(CategoriMembreType::class, $categoriMembre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('categori_membre_index');
        }

        return $this->render('categori_membre/edit.html.twig', [
            'categori_membre' => $categoriMembre,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="categori_membre_delete", methods={"POST"})
     */
    public function delete(Request $request, CategoriMembre $categoriMembre): Response
    {
        if ($this->isCsrfTokenValid('delete'.$categoriMembre->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($categoriMembre);
            $entityManager->flush();
        }

        return $this->redirectToRoute('categori_membre_index');
    }
}
