<?php

namespace App\Controller;

use App\Entity\Cle;
use App\Form\CleType;
use App\Repository\CleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * @Route("/admin/cle")
 */
class CleController extends AbstractController
{
    /**
     * @Route("/", name="cle_index", methods={"GET"})
     */
    public function index(CleRepository $cleRepository): Response
    {
        return $this->render('cle/index.html.twig', [
            'cles' => $cleRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="cle_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $cle = new Cle();
        $form = $this->createForm(CleType::class, $cle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $length=5;
            $entityManager = $this->getDoctrine()->getManager();
            $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $charactersLength = strlen($characters);
            $nb=intVal($form->get('number')->getData());
            if($nb==0)
                $nb=1;

            $s=1;
            for($n=0;$n<$nb;$n++){
                $randomString = '';
                $s+=1;
                for($j=0;$j<5;$j++){
                    for ($i = 0; $i < $length; $i++) {
                        $randomString .= $characters[rand(0, $charactersLength - 1)];
                    }
                    if($j!=4)
                        $randomString.='-';
                }
                $cle2=new Cle();
                $cle2->setProduit($cle->getProduit());
                $cle2->setCode( $randomString);
                $entityManager->persist($cle2);
                $entityManager->flush();
            }

            return $this->redirectToRoute('cle_index');
        }

        return $this->render('cle/new.html.twig', [
            'cle' => $cle,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{idcle}", name="cle_show", methods={"GET"})
     */
    public function show(Cle $cle): Response
    {
        return $this->render('cle/show.html.twig', [
            'cle' => $cle,
        ]);
    }

    /**
     * @Route("/{idcle}/edit", name="cle_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Cle $cle): Response
    {
        $form = $this->createForm(CleType::class, $cle);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('cle_index');
        }

        return $this->render('cle/edit.html.twig', [
            'cle' => $cle,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{idcle}", name="cle_delete", methods={"POST"})
     */
    public function delete(Request $request, Cle $cle): Response
    {
        if ($this->isCsrfTokenValid('delete'.$cle->getIdcle(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($cle);
            $entityManager->flush();
        }

        return $this->redirectToRoute('cle_index');
    }

    /**
     * @Route("/rechreche",name="rechrecheID")
     */
    public function rechreche(Request $request, NormalizerInterface $Normalizer)
    {
        $repository = $this->getDoctrine()->getRepository(Cle::class);
        $requestString = $request->get('searchValue');
        $cle = $repository->findByID($requestString);
        $jsonContent = $Normalizer->normalize($cle, 'json');

        return new Response(json_encode($jsonContent));
    }




}