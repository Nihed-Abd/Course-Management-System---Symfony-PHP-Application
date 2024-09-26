<?php

namespace App\Controller;

use App\Entity\Formation;
use App\Entity\Administrateur;
use App\Entity\Etudiant;

use App\Form\FormationType;
use App\Repository\FormationRepository;
use App\Repository\EtudiantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Service\StripeService;
use Stripe\Charge;
use Stripe\Stripe;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Knp\Component\Pager\PaginatorInterface;

#[Route('/formation')]
class FormationController extends AbstractController
{


       #[Route('/payment/{user_id}', name: 'app_payment1')]
    public function indexp(int $user_id): Response
    {
        return $this->render('formation/ClientView/paiment.html.twig', [
            'controller_name' => 'PaymentController',
            'stripe_key' => $_ENV["STRIPE_PUBLIC_KEY"],
            'user_id' => $user_id,
        ]);
    }
    ///payment
    
    #[Route('/payment/{user_id}/create-charge', name: 'app_stripe_charge2', methods: ['POST'])]
    public function createCharge(Request $request , int $user_id)
    {
        Stripe::setApiKey($_ENV["STRIPE_SECRET_KEY"]);
        Charge::create ([
                "amount" => 5 * 100,
                "currency" => "usd",
                "source" => $request->request->get('stripeToken'),
                "description" => "Binaryboxtuts Payment Test"
        ]);
        $this->addFlash(
            'success',
            'Payment Successful!'
        );
        return $this->redirectToRoute('Client_formation_index', ['user_id' => $user_id], Response::HTTP_SEE_OTHER);
    }  
    ///////////////////// Admin functions ///////////////////
    #[Route('/', name: 'app_formation_index', methods: ['GET'])]
    public function index(FormationRepository $formationRepository): Response
    {
        return $this->render('formation/AdminView/index.html.twig', [
            'formations' => $formationRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_formation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Get the existing Administrateur with ID 1
        $administrateur = $entityManager->getRepository(Administrateur::class)->find(1);
        
        $formation = new Formation();
        $form = $this->createForm(FormationType::class, $formation);
        $form->handleRequest($request);
    
        // Set the existing Administrateur as idAdministrateur
        $formation->setIdAdministrateur($administrateur);
        
        // Set the idEtudiant to 1
        $formation->setIdEtudiant(1);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($formation);
            $entityManager->flush();
    
            return $this->redirectToRoute('app_formation_index', [], Response::HTTP_SEE_OTHER);
        }
    
        return $this->renderForm('formation/AdminView/new.html.twig', [
            'formation' => $formation,
            'form' => $form,
        ]);
    }
    


    #[Route('/{IdFormation}/edit', name: 'app_formation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Formation $formation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(FormationType::class, $formation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_formation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('formation/AdminView/edit.html.twig', [
            'formation' => $formation,
            'form' => $form,
        ]);
    }

    #[Route('/{IdFormation}', name: 'app_formation_delete', methods: ['POST'])]
    public function delete(Request $request, Formation $formation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$formation->getIdFormation(), $request->request->get('_token'))) {
            // Remove associated Avis entities
            foreach ($formation->getAvis() as $avis) {
                $entityManager->remove($avis);
            }
    
            // Remove the Formation
            $entityManager->remove($formation);
            $entityManager->flush();
        }
    
        return $this->redirectToRoute('app_formation_index', [], Response::HTTP_SEE_OTHER);
    }
    

    //////////////////////// Client Functions /////////////////////
    #[Route('/Client/{user_id}', name: 'Client_formation_index', methods: ['GET'])]
public function indexClient(Request $request, FormationRepository $formationRepository, PaginatorInterface $paginator, int $user_id): Response
{
    $formationsQuery = $formationRepository->createQueryBuilder('e')
        ->orderBy('e.IdFormation', 'DESC')
        ->getQuery();

    $pagination = $paginator->paginate(
        $formationsQuery,
        $request->query->getInt('page', 1), // Get the page parameter from the URL, default to 1
        2 // Number of items per page
    );

    return $this->render('formation/ClientView/index.html.twig', [
        'formations' => $pagination, // Use the paginated results instead of $formationRepository->findAll()
        'user_id' => $user_id,
        'pagination' => $pagination,
    ]);
}
    /**
     * @Route("/search/formations", name="search_formations", methods={"GET"})
     */
    public function searchFormations(Request $request)
    {
        $searchTerm = $request->query->get('search');
        $entityManager = $this->getDoctrine()->getManager();
        $formations = $entityManager->getRepository(Formation::class)->findByNomFormation($searchTerm);

        $formattedFormations = [];
        foreach ($formations as $formation) {
            $formattedFormations[] = [
                'idFormation' => $formation->getId(),
                'nomFormation' => $formation->getNomFormation(),
                'description' => $formation->getDescription(),
                'dateDebut' => $formation->getDateDebut() ? $formation->getDateDebut()->format('Y-m-d') : null,
                'dateFin' => $formation->getDateFin() ? $formation->getDateFin()->format('Y-m-d') : null,
                'prix' => $formation->getPrix(),
                // Add more fields as needed
            ];
        }

        return new JsonResponse($formattedFormations);
    }

}
