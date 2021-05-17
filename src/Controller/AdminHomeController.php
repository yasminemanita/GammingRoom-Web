<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Repository\MembreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminHomeController extends AbstractController
{
    /**
     * @Route("/admin/", name="admin_home")
     */
    public function index(MembreRepository $membreRepository): Response
    {
        $produits = $this->getDoctrine()->getRepository(Produit::class)->findAll();
        $libelle = [];
        $prix = [];
        foreach ($produits as $produit) {
            $libelle [] = $produit->getLibelle();
            $prix [] = $produit->getPrix();
        }

        return $this->render('admin_home/index.html.twig', [
            'controller_name' => 'AdminHomeController',
            'counts' => $membreRepository->countMember(),
            'libelle' => json_encode($libelle),
            'prix' => json_encode($prix)
        ]);
    }
}
