<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

use Symfony\Component\Security\Core\Security;
use App\Entity\Membre;
use App\Entity\Avis;
use App\Form\AvisType;
class HomeController extends AbstractController
{
    private $client;
    private $security;

    public function __construct(Security $security,HttpClientInterface $client)
    {
        $this->security = $security;
        $this->client = $client;
    }
    /**
     * @Route("/", name="home")
     */
    public function index(Request $request,SessionInterface $session): Response
    {
        $avis= new Avis();
        $listAvis=$this->getDoctrine()->getRepository(Avis::class)->findAll();
        $form = $this->createForm(AvisType::class, $avis);
        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user=$this->security->getUser();
            
            if(!$user){
                return $this->redirect('/login');
            }
            $avis->setMembre($user);
            $response = $this->client->request(
                'GET',
                "https://api.meaningcloud.com/sentiment-2.1?verbose=y&key=".$_ENV['keyMeaningcloudApi']."&lang=en&txt=".$avis->getAvis()."&model=general"
            );
    
            $statusCode = $response->getStatusCode();
            
            if($statusCode==200){
                // $statusCode = 200
                $content = $response->toArray();
                $avis->setSentiment($content['score_tag']);
            }
            
            $em=$this->getDoctrine()->getManager();
            $em->persist($avis);
            $em->flush();
            //TODO notif add succ
            $avis= new Avis();
            $form = $this->createForm(AvisType::class, $avis);
        }

        $panier = $session->get('panier',[]);
        $total= 0;

        foreach($panier as $item){
            $totalitem = $item ['produit'] -> getPrix() * $item['quantity'];
            $total += $totalitem;
        }
        


        return $this->render('home/index.html.twig', [
            'form' => $form->createView(),
            'listAvis' => $listAvis,
            'items'=> $panier,
            'total'=> $total
        ]);
    }
}
