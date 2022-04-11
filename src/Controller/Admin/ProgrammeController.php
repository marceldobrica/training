<?php

namespace App\Controller\Admin;

use App\Entity\Programme;
use App\Form\Type\DeleteCancelType;
use App\Form\Type\ProgrammeType;
use App\Repository\ProgrammeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProgrammeController extends AbstractController
{
    private ProgrammeRepository $programmeRepository;

    private EntityManagerInterface $entityManager;

    private int $articlesOnPage;

    public function __construct(
        ProgrammeRepository $programmeRepository,
        EntityManagerInterface $entityManager,
        string $articlesOnPage
    ) {
        $this->programmeRepository = $programmeRepository;
        $this->entityManager = $entityManager;
        $this->articlesOnPage = intval($articlesOnPage);
    }

    /**
     * @Route("/admin/programme", name="app_admin_programme", methods={"GET"})
     */
    public function showProgrammesAction(Request $request): Response
    {
        $currentPage = $request->query->get('page', 1);
        $pageSize = $request->query->get('size', $this->articlesOnPage);
        $nrProgrammes = $this->programmeRepository->countProgrammes();
        $currentPosition = ($currentPage - 1) * $pageSize;
        $nextPage = ($currentPosition + $pageSize < $nrProgrammes) ? $currentPage + 1 : $currentPage;
        $previousPage = ($currentPosition >= $pageSize) ?   $currentPage - 1 : $currentPage;
        $programmes = $this->programmeRepository->findAllPaginated($currentPage, $pageSize);

        return $this->render('admin/programme/index.html.twig', [
            'programmes' => $programmes,
            'previous_page' => $previousPage,
            'next_page' => $nextPage,
            'current_page' => $currentPage,
            'page_size' => $pageSize,
        ]);
    }

    /**
     * @Route("/admin/programme/add", name="app_admin_programme_add", methods={"GET", "POST"})
     */
    public function addProgrammeAction(Request $request): Response
    {
        $programme = new Programme();
        $form = $this->createForm(ProgrammeType::class, $programme);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $programme = $form->getData();
            $this->entityManager->persist($programme);
            $this->entityManager->flush();
            $this->addFlash(
                'success',
                'You have created a new programme!'
            );

            return $this->redirectToRoute('app_admin_programme');
        }

        return $this->renderForm('admin/programme/form.html.twig', [
            'form' => $form,
        ]);
    }

    /**
     * @Route("/admin/programme/delete/{id}", name="app_admin_programme_delete", methods={"GET", "POST"})
     */
    public function deleteProgrammeAction(Request $request, $id): Response
    {
        $programme = $this->programmeRepository->findOneBy(['id' => $id]);
        $form = $this->createForm(DeleteCancelType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('delete')->isClicked()) {
                $this->entityManager->remove($programme);
                $this->entityManager->flush();
                $this->addFlash(
                    'warning',
                    'You have deleted the user with id=' . $id . '!'
                );
            }

            return $this->redirectToRoute('app_admin_programme');
        }

        return $this->renderForm('admin/programme/delete_form.html.twig', [
            'form' => $form,
            'programme' => $programme,
        ]);
    }

    /**
     * @Route("/admin/programme/update/{id}", name="app_admin_programme_update", methods={"GET", "POST"})
     */
    public function updateProgrammeAction(Request $request, $id): Response
    {
        $programme = $this->programmeRepository->findOneBy(['id' => $id]);
        $form = $this->createForm(ProgrammeType::class, $programme);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $programme = $form->getData();
            $this->entityManager->persist($programme);
            $this->entityManager->flush();
            $this->addFlash(
                'info',
                'You have updated the programme with id=' . $id . '!'
            );

            return $this->redirectToRoute('app_admin_programme');
        }

        return $this->renderForm('admin/programme/form.html.twig', [
            'form' => $form,
        ]);
    }
}
