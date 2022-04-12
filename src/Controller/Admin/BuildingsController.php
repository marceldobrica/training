<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Building;
use App\Form\Type\BuildingType;
use App\Form\Type\DeleteCancelType;
use App\Repository\BuildingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/buildings")
 */
class BuildingsController extends AbstractController
{
    private BuildingRepository $buildingRepository;

    private EntityManagerInterface $entityManager;

    public function __construct(BuildingRepository $buildingRepository, EntityManagerInterface $entityManager)
    {
        $this->buildingRepository = $buildingRepository;
        $this->entityManager = $entityManager;
    }


    /**
     * @Route(name="admin_buildings", methods={"GET"})
     */
    public function showBuildingsAction(): Response
    {
        $buildings = $this->buildingRepository->findAll();

        return $this->render('admin/buildings/index.html.twig', [
            'buildings' => $buildings
        ]);
    }

    /**
     * @Route ("/add", name="admin_buildings_add", methods={"GET", "POST"})
     */
    public function addBuildingAction(Request $request): Response
    {
        $building = new Building();
        $form = $this->createForm(BuildingType::class, $building);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $building = $form->getData();
            $this->entityManager->persist($building);
            $this->entityManager->flush();
            $this->addFlash(
                'success',
                'You have created a new building!'
            );

            return $this->redirectToRoute('admin_buildings');
        }

        return $this->renderForm('admin/buildings/form.html.twig', [
            'form' => $form,
        ]);
    }

    /**
     * @Route ("/delete/{id}", name="admin_buildings_delete", methods={"GET", "POST"})
     */
    public function deleteBuildingAction(Request $request, $id): Response
    {
        $building = $this->buildingRepository->findOneBy(['id' => $id]);
        $form = $this->createForm(DeleteCancelType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('delete')->isClicked()) {
                $this->entityManager->remove($building);
                $this->entityManager->flush();
                $this->addFlash(
                    'info',
                    'You have deleted the building with id=' . $id . '!'
                );
            }

            return $this->redirectToRoute('admin_buildings');
        }

        return $this->renderForm('admin/buildings/delete_form.html.twig', [
            'form' => $form,
            'building' => $building,
        ]);
    }

    /**
     * @Route ("/update/{id}", name="admin_buildings_update", methods={"GET", "POST"})
     */
    public function updateBuildingAction(Request $request, $id): Response
    {
        $building = $this->buildingRepository->findOneBy(['id' => $id]);
        $form = $this->createForm(BuildingType::class, $building);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $building = $form->getData();
            $this->entityManager->persist($building);
            $this->entityManager->flush();
            $this->addFlash(
                'info',
                'You have updated the building with id=' . $id . '!'
            );

            return $this->redirectToRoute('admin_buildings');
        }

        return $this->renderForm('admin/buildings/form.html.twig', [
            'form' => $form,
        ]);
    }
}
