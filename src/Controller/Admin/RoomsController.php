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

/**
 * @Route("/admin/rooms")
 */
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
     * @Route(name="admin_rooms", methods={"GET"})
     */
    public function showRoomsAction(): Response
    {
        $rooms = $this->roomRepository->findAll();

        return $this->render('admin/rooms/index.html.twig', [
            'rooms' => $rooms,
        ]);
    }

    /**
     * @Route("/add", name="admin_rooms_add", methods={"GET", "POST"})
     */
    public function addRoomAction(Request $request): Response
    {
        $room = new Room();
        $form = $this->createForm(RoomType::class, $room);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $room = $form->getData();
            $this->entityManager->persist($room);
            $this->entityManager->flush();
            $this->addFlash(
                'success',
                'You have created a new room!'
            );

            return $this->redirectToRoute('admin_rooms');
        }

        return $this->renderForm('admin/rooms/form.html.twig', [
            'form' => $form,
        ]);
    }

    /**
     * @Route("/delete/{id}", name="admin_rooms_delete", methods={"GET", "POST"})
     */
    public function deleteRoomAction(Request $request, $id): Response
    {
        $room = $this->roomRepository->findOneBy(['id' => $id]);
        if (null === $room) {
            return new Response('Room not found!', Response::HTTP_NOT_FOUND);
        }

        $form = $this->createForm(DeleteCancelType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('delete')->isClicked()) {
                $this->entityManager->remove($room);
                $this->entityManager->flush();
                $this->addFlash(
                    'info',
                    'You have deleted the room with id=' . $id . '!'
                );
            }

            return $this->redirectToRoute('admin_rooms');
        }

        return $this->renderForm('admin/rooms/delete_form.html.twig', [
            'form' => $form,
            'room' => $room,
        ]);
    }

    /**
     * @Route("/update/{id}", name="admin_rooms_update", methods={"GET", "POST"})
     */
    public function updateRoomAction(Request $request, $id): Response
    {
        $room = $this->roomRepository->findOneBy(['id' => $id]);
        if (null === $room) {
            return new Response('Room not found!', Response::HTTP_NOT_FOUND);
        }

        $form = $this->createForm(RoomType::class, $room);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $room = $form->getData();
            $this->entityManager->persist($room);
            $this->entityManager->flush();
            $this->addFlash(
                'info',
                'You have updated the room with id=' . $id . '!'
            );

            return $this->redirectToRoute('admin_rooms');
        }

        return $this->renderForm('admin/rooms/form.html.twig', [
            'form' => $form,
        ]);
    }
}
