<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Room;
use App\Form\Type\DeleteCancelType;
use App\Form\Type\RoomType;
use App\Repository\RoomRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RoomsController extends AbstractController
{
    private RoomRepository $roomRepository;

    private EntityManagerInterface $entityManager;

    public function __construct(RoomRepository $roomRepository, EntityManagerInterface $entityManager)
    {
        $this->roomRepository = $roomRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/admin/rooms", name="app_admin_rooms", methods={"GET"})
     */
    public function showRoomsAction(): Response
    {
        $rooms = $this->roomRepository->findAll();

        return $this->render('admin/rooms/index.html.twig', [
            'rooms' => $rooms,
        ]);
    }

    /**
     * @Route("/admin/rooms/add", name="app_admin_rooms_add", methods={"GET", "POST"})
     */
    public function addRoomAction(Request $request): Response
    {
        $room = new Room();
        return $this->handleForm($room, $request);
    }

    /**
     * @Route("/admin/rooms/delete/{id}", name="app_admin_rooms_delete", methods={"GET", "POST"})
     */
    public function deleteRoomAction(Request $request, $id): Response
    {
        $room = $this->roomRepository->findOneBy(['id' => $id]);
        $form = $this->createForm(DeleteCancelType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('delete')->isClicked()) {
                $this->entityManager->remove($room);
                $this->entityManager->flush();
            }

            return $this->redirectToRoute('app_admin_rooms');
        }
        return $this->renderForm('admin/rooms/delete_form.html.twig', [
            'form' => $form,
            'user' => $room,
        ]);
    }

    /**
     * @Route("/admin/rooms/update/{id}", name="app_admin_rooms_update", methods={"GET", "POST"})
     */
    public function updateRoomAction(Request $request, $id): Response
    {
        $room = $this->roomRepository->findOneBy(['id' => $id]);
        return $this->handleForm($room, $request);
    }

    private function handleForm(Room $room, Request $request): Response
    {
        $form = $this->createForm(RoomType::class, $room);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $room = $form->getData();
            $this->entityManager->persist($room);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_admin_rooms');
        }

        return $this->renderForm('admin/rooms/form.html.twig', [
            'form' => $form,
        ]);
    }
}
