<?php

namespace App\Controller\Admin;

use App\Repository\ProgrammeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin")
 */
class SqlReportController extends AbstractController
{
    private ProgrammeRepository $programmeRepository;

    private EntityManagerInterface $entityManager;

    public function __construct(ProgrammeRepository $programmeRepository, EntityManagerInterface $entityManager)
    {
        $this->programmeRepository = $programmeRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/sql-report", name="admin_sql_report", methods={"GET"})
     */
    public function showReportAction(): Response
    {
        $results = $this->programmeRepository->returnBusiestDate();

        return $this->render('admin/sql_report/index.html.twig', [
            'controller_name' => 'SqlReportController',
            'results' => $results
        ]);
    }
}
