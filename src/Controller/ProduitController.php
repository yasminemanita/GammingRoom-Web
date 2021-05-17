<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Entity\Membre;
use App\Entity\RechercheProd;

use App\Form\ProduitType;
use App\Form\RechercheproduitType;
use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use GuzzleHttp\Psr7\UploadedFile;
use Symfony\Component\HttpFoundation\JsonRespImageonse;
use Knp\Component\Pager\PaginatorInterface;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
//use Symfony\Component\Serializer\Normalizer\NormalizableInterface;

class ProduitController extends AbstractController
{
    /**
     * @Route("/admin/produit_index", name="produit_index")
     */
    public function index(Request $request, PaginatorInterface $paginator): Response
    {
        $rechercheprod= new RechercheProd();
        $form = $this->createForm(RechercheproduitType ::class, $rechercheprod);
        $form->handleRequest($request);


        $produits= [];
        if($form->isSubmitted() && $form->isValid()) {
            //on récupère le type de suggestion tapé dans le formulaire
            $desc = $rechercheprod->getDesc();

            if ($desc!="")
                //si on a fourni un type on affiche tous les suggestions ayant ce nom
                $produits= $this->getDoctrine()->getRepository(Produit::class)->findBy(['libelle' => $desc] );
            $produits = $paginator->paginate(
                $produits, // Requête contenant les données à paginer (ici nos articles)
                $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
                3 // Nombre de résultats par page
            );

            return $this->render('produit/index.html.twig', [
                'produits' => $produits,

                'form' => $form->createView(),

            ]);}

        else
            $produits = $this->getDoctrine()
                ->getRepository(Produit::class)
                ->findAll();
        $produits = $paginator->paginate(
            $produits, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            3 // Nombre de résultats par page
        );
            return $this->render('produit/index.html.twig', [
                'produits' => $produits,


                'form' => $form->createView(),

            ]);



    }

    /**
     * @Route("/admin/new", name="produit_new", methods={"GET","POST"})
     */
    public function new(Request $request,MailerInterface $mailer): Response
    {
        $produit = new Produit();
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request); // aabit l formulaire w bech nabaathou

        //recuperer tt les membre inscri
      $em=$this->getDoctrine()->getManager();
       $membre=$em->getRepository(Membre::class)->findAll();

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('image')->getData();
            $fileName = bin2hex(random_bytes(6)).'.'.$file->guessExtension();
            $file->move ($this->getParameter('produit_directory'),$fileName);
            $produit->setImage($fileName);
            $produit->setImage($fileName);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($produit);
            $entityManager->flush();

            // email
        foreach ($membre as $m )
          {
            $email = (new Email())
                ->from('Gamingroom.prodigiesDev@gmail.com')
              ->to($m->getEmail())
                //->to('yasmine.manita@esprit.tn')
                ->priority(Email::PRIORITY_HIGH)
                ->subject('[GamingRoom] Traitement d ajout !')
                //->text('Sending emails is fun again!')
                ->html('<p>Bonjour cher(e) Mr/Mme </p><br>
                   <p>un nouveau produit a été ajouté ' );
            $mailer->send($email);
       }
            return $this->redirectToRoute('produit_index');
        }

        return $this->render('produit/new.html.twig', [
            'produit' => $produit,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/{idprod}/show", name="produit_show", methods={"GET"})
     */
    public function show(Produit $produit): Response
    {
        return $this->render('produit/show.html.twig', [
            'produit' => $produit,
        ]);
    }

    /**
     * @Route("/admin/{idprod}/edit", name="produit_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Produit $produit): Response
    {
        $form = $this->createForm(ProduitType::class, $produit);// creation formulaire
        $form->handleRequest($request); // tabaath chneya fel formulaire

        if ($form->isSubmitted() && $form->isValid()){  // mafama champs feragh
            /**
             * @var UploadedFile $file // methode nhabet fiha les fichier
             */
            $file = $form->get('image')->getData();//recupere l'image
            $fileName = bin2hex(random_bytes(6)).'.'.$file->guessExtension();
            $file->move($this->getParameter('produit_directory'),$fileName);
            $produit->setImage($fileName);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('produit_index');
        }

        return $this->render('produit/edit.html.twig', [
            'produit' => $produit,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/{idprod}/delete", name="produit_delete", methods={"POST"})
     */
    public function delete(Request $request, Produit $produit): Response
    {
        if ($this->isCsrfTokenValid('delete'.$produit->getIdprod(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($produit);
            $entityManager->flush();
        }

        return $this->redirectToRoute('produit_index');
    }




    /**
     * @Route("/admin/listp", name="produit_list", methods={"GET"})
     */
    public  function  listp (ProduitRepository $produitRepository):Response{


    $pdfOptions = new Options();
    $pdfOptions->set('defaultFont', 'Arial');


    $dompdf = new Dompdf($pdfOptions);

    $produits= $produitRepository->findAll();


    $html = $this->renderView('produit/listeP.html.twig',['produits'=>$produits,
    ]);


    $dompdf->loadHtml($html);


    $dompdf->setPaper('A4', 'portrait');


   // Rendre le HTML au format PDF
    $dompdf->render();

    // Sortie du PDF généré dans le navigateur (téléchargement forcé)
    $dompdf->stream("mypdf.pdf", [
        "Attachment" => true
    ]);

}

    /**
     * @Route("/admin/rechprod", name="rechprod")
     */
    public function rechercherProd(Request $request,ProduitRepository $produitRepository): Response

    {   $rechercheprod= new RechercheProd();
        $form = $this->createForm(RechercheproduitType ::class, $rechercheprod);
        $form->handleRequest($request);


        $produits= [];
        if($form->isSubmitted() && $form->isValid()) {
            //on récupère le type de suggestion tapé dans le formulaire
            $desc = $rechercheprod->getDesc();

            if ($desc!="")
                //si on a fourni un type on affiche tous les suggestions ayant ce nom
                $produits= $this->getDoctrine()->getRepository(Produit::class)->findBy(['libelle' => $desc] );


            return $this->render('produit/index.html.twig', [
                'produits' => $produits,

                'form' => $form->createView(),

            ]);}

        else
            return $this->render('produit/index.html.twig', [
                'produits' => $produitRepository->findAll(),


                'form' => $form->createView(),

            ]);
    }

    /**
     * @Route("/admin/StatProduit", name="StatProduit")
     */
    public function StatProduit(): Response
    {

        $produits = $this->getDoctrine()->getRepository(Produit::class)->findAll();
        $libelle = [];
        $prix = [];
        foreach ($produits as $produit) {
            $libelle [] = $produit->getLibelle();
            $prix [] = $produit->getPrix();
        }
        return $this->render('produit/StatProduit.html.twig', [
            'libelle' => json_encode($libelle),
            'prix' => json_encode($prix)
        ]);
    }


    /**
     * @Route("/admin/sort", name="sort")
     */
    public function tri(): Response{
        $prod = $this->getDoctrine()->getRepository(Produit::class)->sortLibelle();
        return $this->render('produit/SortedProduit.html.twig', [
            'controller_name' => 'ProduitController',
            'produitsorted' => $prod,
        ]);
    }



    /**
     * @Route("/admin/sortprix", name="sortprix")
     */
    public function triprix(): Response{
        $prod = $this->getDoctrine()->getRepository(Produit::class)->sortPrix();
        return $this->render('produit/SortedPrix.html.twig', [
            'controller_name' => 'ProduitController',
            'prixsorted' => $prod,
        ]);
    }

    /**
     * @Route("/front", name="front")
     */

    public function affichageFront(): Response{
        $prod = $this->getDoctrine()->getRepository(Produit::class)->findAll();
        return $this->render('produit/front.html.twig', [
            'prod' => $prod,
        ]);
    }
}