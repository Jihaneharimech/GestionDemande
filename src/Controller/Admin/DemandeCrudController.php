<?php

namespace App\Controller\Admin;

use DateTimeImmutable;
use App\Entity\Demande;
use Doctrine\ORM\EntityManagerInterface;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Symfony\Component\HttpFoundation\Request;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use Symfony\Component\HttpFoundation\StreamedResponse;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TelephoneField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class DemandeCrudController extends AbstractCrudController
{
    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    
    public static function getEntityFqcn(): string
    {
        return Demande::class;
    }

    public function configureFields(string $pageName): iterable
    {
        $createdAt = new DateTimeImmutable();
        
        $demande = [
            IdField::new('id','N°Demande')->hideOnForm(),
            TextField::new('nomClient','Nom Client'),
            EmailField::new('email','Email'),
            TelephoneField::new('telephone'),
            TextField::new('adresse'),
            AssociationField::new('ville','Ville'),
            TextField::new('codePostal','Code Postal'),
            AssociationField::new('typeAppareil','Appareil installé'),
            IntegerField::new('nbrAppareil','Nombre Appareil'),
            DateTimeField::new('dateDisponibilite','Date instalation'),
            AssociationField::new('statut','Statut'),
            TextEditorField::new('description','Description'),
            DateTimeField::new('createdAt', 'Passée le')
            ->hideOnForm()
            ->setFormTypeOption('disabled', false)
            ->setFormTypeOption('data', $createdAt),
        ];

        return $demande;
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add('id')
            ->add('nomClient')
            ->add('email')
            ->add('telephone')
            ->add('ville')
            ->add('statut')
            ->add('dateDisponibilite')
            ->add('createdAt')
            ->add('manager')
        ;
    }

    public function configureActions(Actions $actions): Actions
    {
        $exportToExcel = Action::new('Export')
        ->linkToCrudAction('exportToExcel')
        ->createAsGlobalAction();  
  

        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->add(Crud::PAGE_INDEX, $exportToExcel);

    }

    public function exportToExcel(Request $request): StreamedResponse
    {
        // Retrieve the filter criteria from the request
        $filters = $request->get('filters', []);

        // Retrieve the filter criteria from the request
        $id = $filters['id']['value'] ?? null;
        $nomClient = $filters['nomClient']['value'] ?? null;
        $email = $filters['email']['value'] ?? null;
        $telephone = $filters['telephone']['value'] ?? null;
        $ville = $filters['ville']['value'] ?? null;
        $statut = $filters['statut']['value'] ?? null;
        $dateDisponibilite = $filters['dateDisponibilite']['value'] ?? null;
        $createdAt = $filters['createdAt']['value'] ?? null;
        $manager = $filters['manager']['value'] ?? null;

        // Build the filter criteria array based on the filter values
        $criteria = [];
        if ($id) {
            $criteria['id'] = $id;
        }
        if ($nomClient) {
            $criteria['nomClient'] = $nomClient;
        }
        if ($email) {
            $criteria['email'] = $email;
        }
        if ($telephone) {
            $telephone['telephone'] = $telephone;
        }
        if ($ville) {
            $criteria['ville'] = $ville;
        }
        if ($statut) {
            $criteria['statut'] = $statut;
        }
        if ($dateDisponibilite) {
            $criteria['dateDisponibilite'] = new \DateTime($dateDisponibilite);
        }
        if ($createdAt) {
            $criteria['createdAt'] = new \DateTime($createdAt);
        }
        if ($manager) {
            $criteria['manager'] = $manager;
        }

        // Retrieve the entity data by filter
        $demandes = $this->entityManager->getRepository(Demande::class)->findBy($criteria);

                
            // Create a new Spreadsheet object
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

            // Set the column headers
            $sheet->setCellValue('A1', 'N° demande')->getStyle('A1')->applyFromArray($styleArray);
            $sheet->setCellValue('B1', 'Nom Client')->getStyle('B1')->applyFromArray($styleArray);
            $sheet->setCellValue('C1', 'Adresse')->getStyle('C1')->applyFromArray($styleArray);
            $sheet->setCellValue('D1', 'Ville')->getStyle('D1')->applyFromArray($styleArray);
            $sheet->setCellValue('E1', 'Code Postal')->getStyle('E1')->applyFromArray($styleArray);
            $sheet->setCellValue('F1', 'Email')->getStyle('F1')->applyFromArray($styleArray);
            $sheet->setCellValue('G1', 'Téléphone')->getStyle('G1')->applyFromArray($styleArray);
            $sheet->setCellValue('H1', 'Date installation')->getStyle('H1')->applyFromArray($styleArray);
            $sheet->setCellValue('I1', 'Type Appareil')->getStyle('I1')->applyFromArray($styleArray);
            $sheet->setCellValue('J1', 'Nbr Appareil')->getStyle('J1')->applyFromArray($styleArray);
            $sheet->setCellValue('K1', 'Statut')->getStyle('K1')->applyFromArray($styleArray);
            $sheet->setCellValue('L1', 'Description')->getStyle('L1')->applyFromArray($styleArray);
            $sheet->setCellValue('M1', 'Date création')->getStyle('M1')->applyFromArray($styleArray);

            // Bold the column headers
            $headerStyle = $sheet->getStyle('A1:M1');
            $headerFont = $headerStyle->getFont();
            $headerFont->setBold(true);

            // Set the data rows
            $rowIndex = 2;
            foreach ($demandes as $demande) {
                $sheet->setCellValue('A'.$rowIndex, $demande->getId())->getStyle('A'.$rowIndex)->applyFromArray($styleArray);
                $sheet->setCellValue('B'.$rowIndex, $demande->getNomClient())->getStyle('B'.$rowIndex)->applyFromArray($styleArray);
                $sheet->setCellValue('C'.$rowIndex, $demande->getAdresse())->getStyle('C'.$rowIndex)->applyFromArray($styleArray);
                $sheet->setCellValue('D'.$rowIndex, $demande->getVille())->getStyle('D'.$rowIndex)->applyFromArray($styleArray);
                $sheet->setCellValue('E'.$rowIndex, $demande->getCodePostal())->getStyle('E'.$rowIndex)->applyFromArray($styleArray);
                $sheet->setCellValue('F'.$rowIndex, $demande->getEmail())->getStyle('F'.$rowIndex)->applyFromArray($styleArray);
                $sheet->setCellValue('G'.$rowIndex, $demande->getTelephone())->getStyle('G'.$rowIndex)->applyFromArray($styleArray);
                $sheet->setCellValue('H'.$rowIndex, $demande->getDateDisponibilite())->getStyle('H'.$rowIndex)->applyFromArray($styleArray);
                $sheet->setCellValue('I'.$rowIndex, $demande->getTypeAppareil())->getStyle('I'.$rowIndex)->applyFromArray($styleArray);
                $sheet->setCellValue('J'.$rowIndex, $demande->getNbrAppareil())->getStyle('J'.$rowIndex)->applyFromArray($styleArray);
                $sheet->setCellValue('K'.$rowIndex, $demande->getStatut())->getStyle('K'.$rowIndex)->applyFromArray($styleArray);
                $sheet->setCellValue('L'.$rowIndex, $demande->getDescription())->getStyle('L'.$rowIndex)->applyFromArray($styleArray);
                $sheet->setCellValue('M'.$rowIndex, $demande->getCreatedAt())->getStyle('M'.$rowIndex)->applyFromArray($styleArray);
                $rowIndex++;
            }

            // Create a new Xlsx object to write the Spreadsheet data to a file
            $writer = new Xlsx($spreadsheet);

            // Set the response headers to stream the file to the user
            $response = new StreamedResponse(function () use ($writer) {
                $writer->save('php://output');
            });
            $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            $response->headers->set('Content-Disposition', 'attachment;filename=demandees.xlsx');
            $response->headers->set('Cache-Control', 'max-age=0');

        return $response;
    }
    
}
