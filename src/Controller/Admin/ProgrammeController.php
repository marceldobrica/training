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

    public function __construct(ProgrammeRepository $programmeRepository, EntityManagerInterface $entityManager)
    {
        $this->programmeRepository = $programmeRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/admin/programme", name="app_admin_programme")
     */
    public function showProgrammesAction(): Response
    {
        $programmes = $this->programmeRepository->findAll();

        return $this->render('admin/programme/index.html.twig', [
            'programmes' => $programmes
        ]);
    }

    /**
     * @Route("/admin/programme/add", name="app_admin_programme_add")
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
     * @Route("/admin/programme/delete/{id}", name="app_admin_programme_delete")
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
     * @Route("/admin/programme/update/{id}", name="app_admin_programme_update")
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
