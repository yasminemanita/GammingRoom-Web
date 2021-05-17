<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Produit;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;

use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;
use App\Repository\ProduitRepository;



class ProduitMobileController extends AbstractController
{
    /**
     * @Route("/produit/mobile", name="produit_mobile")
     */
    public function index(): Response
    {
        return $this->render('produit_mobile/index.html.twig', [
            'controller_name' => 'ProduitMobileController',
        ]);
    }

    /**
     *  @Route("/liste2", name="liste2")

     */
    public function getproduits(Request $request,ProduitRepository  $produitRepository,NormalizerInterface $normalizer):Response{
        $repo = $produitRepository->findAll();

        $json = $normalizer->normalize($repo,'json',['groups'=>'produit:read']);

        return new Response (json_encode($json));

    }

    /**
     * @Route("/deleteProduits", name="deleteProduits", methods={"GET","POST"})
     *
     */
    public function deleteProduits(Request $request){
        $idprod=$request->get('idprod');
        $entityManager = $this->getDoctrine()->getManager();
        $produits=$entityManager->getRepository(Produit::class)->find($idprod);
        if($produits!=null){
            $entityManager->remove($produits);
            $entityManager->flush();
            $serialize = new Serializer([new ObjectNormalizer()]);
            $formatted = $serialize->normalize("produit deleted");
            return new JsonResponse($formatted);
        }

    }

    /**
     * @Route("/updateProduits", name="updateProduits", methods={"GET","POST"})
     *
     */
    public function updateProduit(Request $request){
        $em=$this->getDoctrine()->getManager();
        $produits=$this->getDoctrine()->getManager()->getRepository(Produit::class)->find($request->get("idprod"));

        $produits->setImage($request->get("image"));
        $produits->setLibelle($request->get("libelle"));
        $produits->setPrix($request->get("prix"));
        $produits->setDescription($request->get("description"));
        $produits->setIdCat($request->get("idCat"));

        $em->persist($produits);
        $em->flush();
        $serialize = new Serializer([new ObjectNormalizer()]);
        $formatted = $serialize->normalize("produit updated");
        return new JsonResponse($formatted);
    }



    /**
     * @Route("/Oneres/{idprod}", name="Oneres")
     *
     */



    public function Oneres(Request $request,ProduitRepository $produitRepository,SerializerInterface $serializerinterface,$idprod)
    {
        $repo = $produitRepository->find($idprod);
        $json = $serializerinterface->serialize($repo,'json',['groups'=>'produit']);

        return new Response (json_encode($json));

    }

    /**
     * @Route("/addprod/{image}/{libelle}/{prix}/{description}/{idCat}", name="addprod")
     */
    public function addoffres(Request $request, SerializerInterface $serializer,EntityManagerInterface $entityManager,$image,$libelle,$prix,$description,$idCat){
        $produits= new Produit();

        $produits->setImage($image);
        $produits->setLibelle($libelle);
        $produits->setPrix($prix);

        $produits->setDescription($description);
        $produits->setIdCat($idCat);

        $em=$this->getDoctrine()->getManager();
        $em->persist($produits);
        $em->flush();
        return new Response ('produit ajoute avec succees');

    }




}
