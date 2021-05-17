<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Form\CategorieType;
use App\Repository\CategorieRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class CategorieController extends AbstractController
{
    /**
     * @Route("/admin/categorie/NameOrdredcat", name="name_orderedcat", methods={"GET"})
     */
    public function orderedName(CategorieRepository $categorieRepository,Request $request,PaginatorInterface $paginator):Response{
        $categorie = $paginator->paginate(
            $categorieRepository->findBy(
                array(),
                array('nomcategorie' => 'ASC')
            ),
            $request->query->getInt('page', 1),
            // Items per page
            8
        );


        return $this->render('categorie/index.html.twig', [
            'categories' => $categorie,
        ]);
    }

    /**
     * @Route("/admin/categorie", name="categorie_index", methods={"GET"})
     */
    public function index(CategorieRepository $categorieRepository,Request $request,PaginatorInterface $paginator): Response
    {
        $categorie = $paginator->paginate(
        // Doctrine Query, not results
            $categorieRepository->findAll(),
            // Define the page parameter
            $request->query->getInt('page', 1),
            // Items per page
            8
        );
        return $this->render('categorie/index.html.twig', [
            'categories' => $categorie,
        ]);
    }

    /**
     * @Route("/admin/categorie/new", name="categorie_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $categorie = new Categorie();
        $form = $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($categorie);
            $entityManager->flush();

            return $this->redirectToRoute('categorie_index');
        }

        return $this->render('categorie/new.html.twig', [
            'categorie' => $categorie,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/categorie/{idcat}", name="categorie_show", methods={"GET"})
     */
    public function show(Categorie $categorie): Response
    {
        return $this->render('categorie/show.html.twig', [
            'categorie' => $categorie,
        ]);
    }

    /**
     * @Route("/admin/categorie/{idcat}/edit", name="categorie_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Categorie $categorie): Response
    {
        $form = $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('categorie_index');
        }

        return $this->render('categorie/edit.html.twig', [
            'categorie' => $categorie,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/categorie/{idcat}", name="categorie_delete", methods={"POST"})
     */
    public function delete(Request $request, Categorie $categorie): Response
    {
        if ($this->isCsrfTokenValid('delete'.$categorie->getIdcat(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($categorie);
            $entityManager->flush();
        }

        return $this->redirectToRoute('categorie_index');
    }


}
