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
        $conn = $this->entityManager->getConnection();
        $sql = "SELECT programme.name AS name, DAY(start_date) AS day, HOUR(start_date) AS hour, " .
            "COUNT(programmes_customers.user_id) AS number FROM programme INNER JOIN programmes_customers " .
            "ON programme.id = programmes_customers.programme_id GROUP BY programme.name, start_date " .
            "ORDER BY number DESC LIMIT 0,5";
        $stmt = $conn->prepare($sql);
        $results = $stmt->executeQuery()->fetchAllAssociative();

        return $this->render('admin/sql_report/index.html.twig', [
            'controller_name' => 'SqlReportController',
            'results' => $results
        ]);
    }
}
