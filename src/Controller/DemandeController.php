<?php

namespace App\Controller;

use App\Entity\Statut;
use DateTimeImmutable;
use App\Entity\Demande;
use App\Form\StatutType;
use App\Form\DemandeType;
use App\Classe\CustomSearch;
use App\Form\CustomSearchType;
use App\Repository\DemandeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/demande')]
class DemandeController extends AbstractController
{
    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    
    #[Route('/', name: 'app_demande_index', methods: ['GET','POST'])]
    public function index(Request $request, DemandeRepository $demandeRepository): Response
    {
        if($this->getUser()->isManager())
        {           
            //My Custom Search
            $customsearch = new CustomSearch();
            $form = $this->createForm(CustomSearchType::class,$customsearch);
            $form->handleRequest($request);
            $idManager = $this->getUser()->getId();
            if ($form->isSubmitted() && $form->isValid())
            {
                $demandes= $demandeRepository->findWithCustomSearch($customsearch,$idManager);
            }
            else {
                //['ROLE_MANAGER'] Affichier les demandes par user
                $demandes = $demandeRepository->findByIdUser($idManager);
            }
        }else
        {
            //['ROLE_USER'] Recuperer ID_ville de User et affichier les demandes par idVille
            $idville = $this->getUser()->getVille()->getId();
            $demandes = $demandeRepository->findByVille($idville);
        }
     
        return $this->render('demande/index.html.twig', [
            'demandes' => $demandes,
            'form' => $form,
        ]);
    }

    #[Route('/ajout', name: 'app_demande_new', methods: ['GET', 'POST'])]
    public function new(Request $request, DemandeRepository $demandeRepository): Response
    {
        $demande = new Demande();
        $form = $this->createForm(DemandeType::class, $demande);
        $form->handleRequest($request);
        
        $createdAt = new DateTimeImmutable();
        $demande->setCreatedAt($createdAt);
        
        $manager = $this->getUser();
        $demande->setManager($manager);

        //Recuperer le 1ere statut A faire
        $statuts = $this->entityManager->getRepository(Statut::class)->findById(1);
        foreach ($statuts as $statut)
        {
            $demande->setStatut($statut);
        }

        if ($form->isSubmitted() && $form->isValid()) {       
            $demandeRepository->save($demande, true); 
            $this->addFlash('notice', 'Votre demande a été enregistrée');     
            return $this->redirectToRoute('app_demande_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('demande/new.html.twig', [
            'demande' => $demande,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_demande_show', methods: ['GET'])]
    public function show(Demande $demande): Response
    {      
        return $this->render('demande/show.html.twig', [
            'demande' => $demande,
        ]);
    }

    
    #[Route('/{id}/voir', name: 'app_demande_show_statut', methods: ['GET', 'POST'])]
    public function showstatut(Request $request, Demande $demande, DemandeRepository $demandeRepository): Response
    {
        $form = $this->createForm(StatutType::class, $demande);
        $form->handleRequest($request);
        $statut = $form->get('statut')->getData();
        if ($form->isSubmitted() && $form->isValid()) {
            $demande->setStatut($statut);
            $demandeRepository->save($demande, true); 
        }

        return $this->render('demande/show.html.twig', [
            'demande' => $demande,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_demande_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Demande $demande, DemandeRepository $demandeRepository): Response
    {
        $form = $this->createForm(DemandeType::class, $demande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $demandeRepository->save($demande, true);

            return $this->redirectToRoute('app_demande_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('demande/edit.html.twig', [
            'demande' => $demande,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_demande_delete', methods: ['POST'])]
    public function delete(Request $request, Demande $demande, DemandeRepository $demandeRepository): Response
    {
      
        if ($this->isCsrfTokenValid('delete'.$demande->getId(), $request->request->get('_token'))) {

            $demandeRepository->remove($demande, true);
          
        }

        return $this->redirectToRoute('app_demande_index', [], Response::HTTP_SEE_OTHER);
    }
}

