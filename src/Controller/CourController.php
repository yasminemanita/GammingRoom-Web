<?php

namespace App\Controller;

// Include Dompdf required namespaces
use App\Entity\Membre;
use App\Form\CourTypeCaptcha;
use Dompdf\Dompdf;
use Dompdf\Options;
use App\Entity\Cour;
use App\Entity\Participantcours;
use App\Entity\Reactioncours;
use App\Form\CourType;
use App\Repository\CourRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

use Symfony\Component\Filesystem\Filesystem;
use GuzzleHttp\Psr7\UploadedFile;
use Symfony\Component\HttpFoundation\JsonRespImageonse;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Security;


/**
 * @Route("/cour")
 */
class CourController extends Controller
{
    private $session;
    private $security;

    public function __construct(SessionInterface $session,Security $security)
    {
        $this->session = $session;
        $this->security = $security;
    }
    /**
     * @Route("/", name="cour_index", methods={"GET"})
     */
    public function index(CourRepository $courRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $courRepository = $paginator->paginate($this->getDoctrine()->getRepository(Cour::class)
            ->findAll(),
            $request->query->getInt('page', 1),
            5
        );
        return $this->render('cour/index.html.twig', [
            'cours' => $courRepository,
        ]);

        $writer = $this->get('phpspreadsheet')->createSpreadSheet();
        $writer->setActiveSheetIndex(0);
        $activesheet = $writer->getActiveSheet();
        $drawingobject = $this->get('phpspreadsheet')->createSpreadsheetWorksheetDrawing();
        $drawingobject->setPath('/path/to/image')
            ->setName('Image name')
            ->setDescription('Image description')
            ->setHeight(60)
            ->setOffsetY(20)
            ->setCoordinates('A1')
            ->setWorksheet($activesheet);

    }

    /**
     * @Route("/coach/cours", name="cour_index_admin", methods={"GET"})
     */
    public function adminindex(CourRepository $courRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $courRepository = $paginator->paginate($this->getDoctrine()->getRepository(Cour::class)
            ->findAll(),
            $request->query->getInt('page', 1),
            5
        );
        return $this->render('cour/indexAdmin.html.twig', [
            'cours' => $courRepository,
        ]);
    }


    /**
     * @Route("/coach/new", name="cour_new_admin", methods={"GET","POST"})
     */
    public function newbyadmin(Request $request): Response
    {

        $cour = new Cour();

        $flow = $this->get('app.form.flow.courTypeFlow'); // must match the flow's service id
        $flow->bind($cour);

        // form of the current step
        $form = $flow->createForm();
        if ($flow->isValid($form)) {

            $flow->saveCurrentStepData($form);

            if($flow->getCurrentStep()==1){
                $file = $form->get('imagecours')->getData();
                $fileName = bin2hex(random_bytes(6)) . '.' . $file->guessExtension();
                $file->move($this->getParameter('cours_directory'), $fileName);
                $this->session->set('imageBeforeUploade', $fileName);

            }


            if ($flow->nextStep()) {
                // form for the next step

                if ($flow->getStep(1)) {


                }

                $form = $flow->createForm();
            } else {
                if ($cour->getTags() == null) {
                    $cour->setTags("aucune");
                }
                $cour->setImagecours($this->session->get('imageBeforeUploade'));
                $cour->setMembre( $this->security->getUser());
                $em = $this->getDoctrine()->getManager();
                $em->persist($cour);
                $em->flush();
                $flow->reset(); // remove step data from the session

                return $this->redirectToRoute('cour_index_admin'); //redirection apres l'ajout
            }
        }

        return $this->render('cour/newadmin.html.twig', [ //envoi du form à la page twig
            'cour' => $cour,
            'form' => $form->createView(),
            'flow' => $flow
        ]);
    }
    /**
     * @Route("/coach/new/captcha", name="cour_new_admin_captcha", methods={"GET","POST"})
     */
    public function newbyadminCaptcha(Request $request): Response
    {
        $cour = new Cour();
        $form = $this->createForm(CourTypeCaptcha::class, $cour);//récuperation du formulaire
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('imagecours')->getData();
            $fileName = bin2hex(random_bytes(6)) . '.' . $file->guessExtension();
            $file->move($this->getParameter('cours_directory'), $fileName);
            $cour->setImagecours($fileName);
            if ($cour->getTags() == null) {
                $cour->setTags("aucune");
            }
            //entity managerpermet l’insertion, la mise à jour et la suppression des données de la base de données
            $entityManager = $this->getDoctrine()->getManager();//récupérer l’entity manager
            $entityManager->persist($cour);//pour l‘ajout d’un nouvel objet
            $entityManager->flush();//envoyer la maj à la bd
            $this->addFlash(
                'info', 'Added succesfully'
            );

            return $this->redirectToRoute('cour_index_admin'); //redirection apres l'ajout

        }

        return $this->render('cour/newadminCaptcha.html.twig', [ //envoi du form à la page twig
            'cour' => $cour,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/rechreche",name="rechrecheCour")
     */
    public function rechreche(Request $request, NormalizerInterface $Normalizer)
    {
        $repository = $this->getDoctrine()->getRepository(Cour::class);
        $requestString = $request->get('searchValue');
        $offres = $repository->findOffreByNsc($requestString);
        $jsonContent = $Normalizer->normalize($offres, 'json');

        return new Response(json_encode($jsonContent));
    }


    /**
     * @Route("/mine/{id}", name="cour_show_mine", methods={"GET"})
     */
    public function showmine(CourRepository $courRepository, Request $request, $id): Response
    {
        //$e=$this->getDoctrine()->getRepository(Cour::class)->find($id);
        $id = 8;
        //$m=$this->getDoctrine()->getRepository(Membre::class)->find(8);
        $cour = $this->courRepository->getEventPart($id);


        return $this->render('cour/index.html.twig', [
            'courRepository' => $courRepository,
        ]);
    }

    /**
     * @Route("/{id}", name="cour_show", methods={"GET"})
     */
    public function show(Cour $cour): Response
    {
        return $this->render('cour/show.html.twig', [
            'participants' => $this->getDoctrine()->getRepository(Participantcours::class)->findAll(),
            'reactions' => $this->getDoctrine()->getRepository(Reactioncours::class)->findAll(),
            'cour' => $cour,
        ]);
    }

    /**
     * @Route("/coach/showcoach/{id}", name="cour_show_admin", methods={"GET"})
     */
    public function showforadmin(Cour $cour): Response
    {
        return $this->render('cour/showadmin.html.twig', [
            'cour' => $cour,
        ]);
    }


    /**
     * @Route("/coach/{id}/editcoach", name="cour_edit_admin", methods={"GET","POST"})
     */
    public function editadmin(Request $request, Cour $cour): Response
    {
        /**
         * @var UploadedFile $file // methode nhabet fiha les fichier
         */

        $oldImage = $cour->getImagecours();
        $form = $this->createForm(CourTypeCaptcha::class, $cour);
        $form->handleRequest($request); //envoie le contenu du form

        if ($form->isSubmitted() && $form->isValid()) {

            /**
             * @var UploadedFile $file // methode nhabet fiha les fichier
             */
            $file = $form->get('imagecours')->getData();//recupere l'image
            $fileName = bin2hex(random_bytes(6)) . '.' . $file->guessExtension();
            $file->move($this->getParameter('cours_directory'), $fileName);
            $cour->setImagecours($fileName);

            $this->getDoctrine()->getManager()->flush();
            $this->addFlash(
                'info', 'updated succesfully'
            );


            return $this->redirectToRoute('cour_index_admin');
        }

        return $this->render('cour/editadmin.html.twig', [
            'cour' => $cour,
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/list", name="cour_pdf, methods={"GET"})
     */

    /** public function pdf()
     * {
     * // Configure Dompdf according to your needs
     * $pdfOptions = new Options();
     * $pdfOptions->set('defaultFont', 'Arial');
     *
     * // Instantiate Dompdf with our options
     * $dompdf = new Dompdf($pdfOptions);
     *
     * // Retrieve the HTML generated in our twig file
     * $html = $this->renderView('cour/pdf.html.twig', [
     * 'title' => "Welcome to our PDF Test"
     * ]);
     *
     * // Load HTML to Dompdf
     * $dompdf->loadHtml($html);
     *
     * // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
     * $dompdf->setPaper('A4', 'portrait');
     *
     * // Render the HTML as PDF
     * $dompdf->render();
     *
     * // Store PDF Binary Data
     * $output = $dompdf->output();
     *
     * // In this case, we want to write the file in the public directory
     * $publicDirectory = $this->get('kernel')->getProjectDir() . '/public';
     * // e.g /var/www/project/public/mypdf.pdf
     * $pdfFilepath =  $publicDirectory . '/mypdf.pdf';
     *
     * // Write file to the desired path
     * file_put_contents($pdfFilepath, $output);
     *
     * // Send some text response
     * return new Response("The PDF file has been succesfully generated !");
     * }
     */

    /**
     * @Route("/coach/{id}", name="cour_delete", methods={"POST"})
     */
    public function delete(Request $request, Cour $cour): Response
    {
        $participants = $this->getDoctrine()->getRepository(Participantcours::class)->findBy(["cour" => $cour]);

        foreach ($participants as $participant) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($participant);
            $entityManager->flush();
        }

        $reactions = $this->getDoctrine()->getRepository(Reactioncours::class)->findBy(["cour" => $cour]);

        foreach ($reactions as $reaction) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($reaction);
            $entityManager->flush();
        }
        if ($this->isCsrfTokenValid('delete' . $cour->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($cour);
            $entityManager->flush();
            $this->addFlash(
                'info', 'Deleted succesfully'
            );
        }

        return $this->redirectToRoute('cour_index_admin');
    }

}
