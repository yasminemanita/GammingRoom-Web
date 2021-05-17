<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use App\Entity\Membre;
use App\Entity\Score;
use App\Entity\Jeux;

class ScoreController extends AbstractController
{
    private $encoders ;
    private $normalizers;
    private $serializer; 

    public function __construct()
    {
        $this->encoders= [ new JsonEncoder()];
        $this->normalizers= [new ObjectNormalizer()];
        $this->serializer= new Serializer($this->normalizers, $this->encoders);
    }

    /**
     * @Route("/api/score/{jeuxId}", name="score")
     */
    public function index($jeuxId): Response
    { 
        $score=$this->getDoctrine()->getRepository(Score::class)->findByJeuxOrderd($jeuxId);
       
        return new Response($this->serializer->serialize($score,'json'));
    }

    /**
     * @Route("/api/score/update/{jeuxId}/{mbmId}/{newScore}", name="updateScore")
     */
    public function updateScore($jeuxId,$mbmId,$newScore): Response
    { 
        $jeux=$this->getDoctrine()->getRepository(Jeux::class)->findById($jeuxId);
        $mbm=$this->getDoctrine()->getRepository(Membre::class)->findById($mbmId);
        if(sizeof($jeux)>0 && sizeof($mbm)>0){
            $score=$this->getDoctrine()->getRepository(Score::class)->findByJeuxAndMembre($jeuxId,$mbmId);
            if(sizeof($score)>0){
                $score=$score[0];
                if($score->getScore()<$newScore){
                    $score->setScore($newScore);
                }
            }
            else{
                $score=new Score();
                $score->setJeux($jeux[0]);
                $score->setMembre($mbm[0]);
                $score->setScore($newScore);
            }
            $em=$this->getDoctrine()->getManager();
            $em->persist($score);
            $em->flush();
        }
       
        
        return new Response($this->serializer->serialize("",'json'));
    }
    
}
