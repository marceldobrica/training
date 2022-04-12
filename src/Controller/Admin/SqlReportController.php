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
     * @Route("/sql-report", name="admin_sql_report")
     */
    public function showReportAction(): Response
    {
        $conn = $this->entityManager->getConnection();
        $sql = "select programme.name as name, DAY(start_date) as day, Hour(start_date) as hour, " .
            "count(user.id) as number from programme inner join programmes_customers " .
            "on programme.id = programmes_customers.programme_id inner join user " .
            "on programmes_customers.user_id = user.id group by programme.name, start_date " .
            "order by number desc limit 0,5";
        $stmt = $conn->prepare($sql);
        $results = $stmt->executeQuery()->fetchAllAssociative();

        return $this->render('admin/sql_report/index.html.twig', [
            'controller_name' => 'SqlReportController',
            'results' => $results
        ]);
    }
}
