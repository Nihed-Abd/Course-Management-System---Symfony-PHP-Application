<?php

namespace App\Controller;

use App\Entity\Rating;
use App\Entity\Avis;

use App\Form\RatingType;
use App\Repository\RatingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/rating')]
class RatingController extends AbstractController
{
    #[Route('/', name: 'app_rating_index', methods: ['GET'])]
    public function index(RatingRepository $ratingRepository): Response
    {
        return $this->render('rating/index.html.twig', [
            'ratings' => $ratingRepository->findAll(),
        ]);
    }


    #[Route('/new/{user_id}/{idFormation}/{idAvis}', name: 'app_rating_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, int $idFormation, int $user_id, int $idAvis): Response
    {
        $rating = new Rating();
        $form = $this->createForm(RatingType::class, $rating);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            // Fetch the Avis entity corresponding to $idAvis
            $avis = $entityManager->getRepository(Avis::class)->find($idAvis);
            if (!$avis) {
                throw $this->createNotFoundException('Avis not found');
            }
    
            $rating->setIdAvis($avis);
            $rating->setDate(new \DateTime());

            $entityManager->persist($rating);
            $entityManager->flush();
    
            return $this->redirectToRoute('app_avis_index', ['user_id' => $user_id, 'idFormation' => $idFormation], Response::HTTP_SEE_OTHER);
        }
    
        return $this->renderForm('rating/ClientView/new.html.twig', [
            'rating' => $rating,
            'form' => $form,
            'idFormation' => $idFormation,
            'user_id' => $user_id,
            'idAvis' => $idAvis,
        ]);
    }
    
    

    #[Route('/{idRating}', name: 'app_rating_show', methods: ['GET'])]
    public function show(Rating $rating): Response
    {
        return $this->render('rating/show.html.twig', [
            'rating' => $rating,
        ]);
    }

    #[Route('/{idRating}/edit', name: 'app_rating_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Rating $rating, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(RatingType::class, $rating);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_rating_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('rating/edit.html.twig', [
            'rating' => $rating,
            'form' => $form,
        ]);
    }

    #[Route('/{idRating}', name: 'app_rating_delete', methods: ['POST'])]
    public function delete(Request $request, Rating $rating, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$rating->getIdRating(), $request->request->get('_token'))) {
            $entityManager->remove($rating);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_rating_index', [], Response::HTTP_SEE_OTHER);
    }
}
