<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Statut;
use DateTimeImmutable;
use App\Entity\Demande;
use App\Form\StatutType;
use App\Form\DemandeType;
use App\Classe\CustomSearch;
use App\Form\CustomSearchType;
use App\Form\CustomSearchAllType;
use App\Repository\DemandeRepository;
use Symfony\Component\Mercure\Update;
use Doctrine\ORM\EntityManagerInterface;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
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
    public function index(Request $request, DemandeRepository $demandeRepository,SessionInterface $session): Response
    {
        if($this->getUser()->isManager())
        {           
            //My Custom Search
            $customsearch = new CustomSearch();
            $form = $this->createForm(CustomSearchType::class,$customsearch);
            $form->handleRequest($request);
            $idManager = $this->getUser()->getId();
            $formSearchAll="";

            if ($form->isSubmitted() && $form->isValid() && ($form->get('ville')->getData() != null || $form->get('string')->getData() != null || $form->get('typeAppareil')->getData() != null 
            || $form->get('statut')->getData() != null || 
            ($form->get('datefrom')->getData() != null && $form->get('dateto')->getData() != null) )
            || ($form->get('selectDate')->getData() != null))
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
             $form="";
             //My Custom Search All
             $customsearch = new CustomSearch();
            $formSearchAll = $this->createForm(CustomSearchAllType::class,$customsearch);
            $formSearchAll->handleRequest($request);
            if ($formSearchAll->isSubmitted() && $formSearchAll->isValid() && $formSearchAll->get('stringSearchAll')->getData() != null){
                $demandes= $demandeRepository->findWithCustomSearchAll($customsearch,$idville); 
            }
        }        
        $session->set('demandes', $demandes);
        return $this->render('demande/index.html.twig', [
            'demandes' => $demandes,
            'form' => $form,
            'formSearchAll' => $formSearchAll,
            
        ]);
    }

    // #[Route('/ajout', name: 'app_demande_new', methods: ['GET', 'POST'])]
    // public function new(Request $request, DemandeRepository $demandeRepository,HubInterface $hub): Response
    // {
    //     $demande = new Demande();
    //     $form = $this->createForm(DemandeType::class, $demande);
    //     $form->handleRequest($request);
        
    //     $createdAt = new DateTimeImmutable();
    //     $demande->setCreatedAt($createdAt);
        
    //     $manager = $this->getUser();
    //     $demande->setManager($manager);

    //     //Recuperer le 1ere statut A faire
    //     $statuts = $this->entityManager->getRepository(Statut::class)->findById(1);
    //     foreach ($statuts as $statut)
    //     {
    //         $demande->setStatut($statut);
    //     }
    //     if ($form->isSubmitted() && $form->isValid()) { 

    //        $idvilleUser = $this->getUser()->getVille()->getId();
    //        $idvilleDemande = $form->get('ville')->getData()->getId();
    //        if($idvilleUser == $idvilleDemande){
    //         $update = new Update(
    //             'https://example.com/books/1',
    //             json_encode(['status' => 'Une nouvelle demande a été ajoutée par '.$this->getUser()->getName()               
    //             ])
    //                              );
    //             $hub->publish($update); 
    //        }



    //              // $demandeRepository->save($demande, true);
    //         // $this->addFlash('notice', 'Votre demande a été enregistrée');     
    //         // return $this->redirectToRoute('app_demande_index', [], Response::HTTP_SEE_OTHER);
    //     }

    //     return $this->renderForm('demande/new.html.twig', [
    //         'demande' => $demande,
    //         'form' => $form,
    //     ]);
    // }


    #[Route('/ajout', name: 'app_demande_new', methods: ['GET', 'POST'])]
public function new(Request $request, DemandeRepository $demandeRepository, HubInterface $hub): Response
{
    $demande = new Demande();
    $form = $this->createForm(DemandeType::class, $demande);
    $form->handleRequest($request);

    $createdAt = new DateTimeImmutable();
    $demande->setCreatedAt($createdAt);

    $manager = $this->getUser();
    $demande->setManager($manager);

    // Recuperer le 1ere statut A faire
    $statuts = $this->entityManager->getRepository(Statut::class)->findById(1);
    foreach ($statuts as $statut) {
        $demande->setStatut($statut);
    }

    if ($form->isSubmitted() && $form->isValid()) {
        $idVilleDemande = $form->get('ville')->getData()->getId();
        $users = $this->entityManager->getRepository(User::class)->findBy(['ville' => $idVilleDemande]);
        $LoginUser = $this->getUser();
            $update = new Update(
                'https://example.com/books/1',
                json_encode(['status' => 'Une nouvelle demande a été ajoutée par '.$this->getUser()->getName(),
                'idVilleDemande' => $idVilleDemande              
                ])
                 );
                $hub->publish($update);
         
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

    #[Route('/export/csv', name: 'app_export_csv', methods: ['GET'])]
    public function exportCsv(SessionInterface $session): Response
    {  
    // Retrieve data from the database or other source
    $demandes = $session->get('demandes');
        foreach ($demandes as $demande) {
            $id= $demande->getId();
            $nomClient= $demande->getNomClient();
            $adresse= $demande->getAdresse();
            $codePostal= $demande->getCodePostal();
            $email= $demande->getEmail();
            $telephone= $demande->getTelephone();
            $dateDisponibilite= $demande->getDateDisponibilite();
            $nbrAppareil= $demande->getNbrAppareil();
            $description= $demande->getDescription();
            $createdAt= $demande->getCreatedAt();
            $ville= $demande->getVille();
            $typeAppareil= $demande->getTypeAppareil();
            $statut= $demande->getStatut();
        $demandeList[] = [$id,$nomClient,$adresse,$ville,$codePostal,$email,$telephone,$dateDisponibilite->format('Y-m-d'),$typeAppareil,$nbrAppareil,$statut,$description,$dateDisponibilite->format('Y-m-d')];
        }

        // Define the CSV file name
        $filename = 'exported_data_' . date('Y-m-d') . '.csv';

        // Create the CSV response object
        $response = new Response();

        // Set the content type and attachment header
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="' . $filename . '"');

        // Open a file handle to create the CSV file
        $handle = fopen('php://output', 'w');

        // Write the header row to the CSV file
        fputcsv($handle, ['N°demande', 'Nom Client', 'Adresse', 'Ville', 'Code Postal', 'Email', 'Téléphone', 'Date installation', 'Type Appareil', 'Nbr Appareil', 'Statut', 'Description', 'Date création']);

        // Write the data rows to the CSV file
        foreach ($demandeList as $row) {
            fputcsv($handle, $row);
        }

        // Close the file handle
        fclose($handle);

        // Set the CSV content as the response content
        $response->setContent(ob_get_clean());

        return $response;
    
    }

    #[Route('/export/excel', name: 'app_export_excel')]
    public function exportExcel(SessionInterface $session): Response
    {
        // start output buffering
        ob_start();

        // récupérer les données de la session
        $demandes = $session->get('demandes');

        // créer un nouveau document Excel
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // add border to all cells
        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ],
            ],
        ];

        // ajouter les en-têtes de colonnes
        $boldStyle = ['font' => ['bold' => true]];

        // ajouter les en-têtes de colonnes
        $sheet->setCellValue('A1', 'N° demande')->getStyle('A1')->applyFromArray($styleArray, $boldStyle);
        $sheet->setCellValue('B1', 'Nom Client')->getStyle('B1')->applyFromArray($styleArray, $boldStyle);
        $sheet->setCellValue('C1', 'Adresse')->getStyle('C1')->applyFromArray($styleArray, $boldStyle);
        $sheet->setCellValue('D1', 'Ville')->getStyle('D1')->applyFromArray($styleArray, $boldStyle);
        $sheet->setCellValue('E1', 'Code Postal')->getStyle('E1')->applyFromArray($styleArray, $boldStyle);
        $sheet->setCellValue('F1', 'Email')->getStyle('F1')->applyFromArray($styleArray, $boldStyle);
        $sheet->setCellValue('G1', 'Téléphone')->getStyle('G1')->applyFromArray($styleArray, $boldStyle);
        $sheet->setCellValue('H1', 'Date installation')->getStyle('H1')->applyFromArray($styleArray, $boldStyle);
        $sheet->setCellValue('I1', 'Type Appareil')->getStyle('I1')->applyFromArray($styleArray, $boldStyle);
        $sheet->setCellValue('J1', 'Nbr Appareil')->getStyle('J1')->applyFromArray($styleArray, $boldStyle);
        $sheet->setCellValue('K1', 'Statut')->getStyle('K1')->applyFromArray($styleArray, $boldStyle);
        $sheet->setCellValue('L1', 'Description')->getStyle('L1')->applyFromArray($styleArray, $boldStyle);
        $sheet->setCellValue('M1', 'Date création')->getStyle('M1')->applyFromArray($styleArray, $boldStyle);

        // set column widths
        $sheet->getColumnDimension('A')->setWidth(15);
        $sheet->getColumnDimension('B')->setWidth(15);
        $sheet->getColumnDimension('C')->setWidth(15);
        $sheet->getColumnDimension('D')->setWidth(15);
        $sheet->getColumnDimension('E')->setWidth(15);
        $sheet->getColumnDimension('F')->setWidth(15);
        $sheet->getColumnDimension('G')->setWidth(15);
        $sheet->getColumnDimension('H')->setWidth(15);
        $sheet->getColumnDimension('I')->setWidth(15);
        $sheet->getColumnDimension('J')->setWidth(15);
        $sheet->getColumnDimension('K')->setWidth(15);
        $sheet->getColumnDimension('L')->setWidth(15);
        $sheet->getColumnDimension('M')->setWidth(15);


        // ajouter les données
        $row = 2;
        foreach ($demandes as $demande) {
            $sheet->setCellValue('A'.$row, $demande->getId())->getStyle('A'.$row)->applyFromArray($styleArray);
            $sheet->setCellValue('B'.$row, $demande->getNomClient())->getStyle('B'.$row)->applyFromArray($styleArray);
            $sheet->setCellValue('C'.$row, $demande->getAdresse())->getStyle('C'.$row)->applyFromArray($styleArray);
            $sheet->setCellValue('D'.$row, $demande->getVille())->getStyle('D'.$row)->applyFromArray($styleArray);
            $sheet->setCellValue('E'.$row, $demande->getCodePostal())->getStyle('E'.$row)->applyFromArray($styleArray);
            $sheet->setCellValue('F'.$row, $demande->getEmail())->getStyle('F'.$row)->applyFromArray($styleArray);
            $sheet->setCellValue('G'.$row, $demande->getTelephone())->getStyle('G'.$row)->applyFromArray($styleArray);
            $sheet->setCellValue('H'.$row, $demande->getDateDisponibilite())->getStyle('H'.$row)->applyFromArray($styleArray);
            $sheet->setCellValue('I'.$row, $demande->getTypeAppareil())->getStyle('I'.$row)->applyFromArray($styleArray);
            $sheet->setCellValue('J'.$row, $demande->getNbrAppareil())->getStyle('J'.$row)->applyFromArray($styleArray);
            $sheet->setCellValue('K'.$row, $demande->getStatut())->getStyle('K'.$row)->applyFromArray($styleArray);
            $sheet->setCellValue('L'.$row, $demande->getDescription())->getStyle('L'.$row)->applyFromArray($styleArray);
            $sheet->setCellValue('M'.$row, $demande->getCreatedAt())->getStyle('M'.$row)->applyFromArray($styleArray);
            $row++;
        }

        // créer un objet Writer pour enregistrer le document en fichier Excel
        $writer = new Xlsx($spreadsheet);
        $filename = 'demandes.xlsx';

        // envoyer les headers
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$filename.'"');
        header('Cache-Control: max-age=0');

        // envoyer le fichier Excel au client
        $writer->save('php://output');
        $content = ob_get_clean();
        return new Response($content);
    }

}

