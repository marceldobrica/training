<?php

namespace App\Controller\Admin;

use App\Repository\ProgrammeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin")
 */
class SqlReportController extends AbstractController
{
    private ProgrammeRepository $programmeRepository;

    public function __construct(ProgrammeRepository $programmeRepository)
    {
        $this->programmeRepository = $programmeRepository;
    }

    /**
     * @Route("/sql-report", name="admin_sql_report", methods={"GET"})
     */
    public function showReportAction(): Response
    {
        $results = $this->programmeRepository->returnBusiestDate();

        return $this->render('admin/sql_report/index.html.twig', [
            'results' => $results
        ]);
    }
}
