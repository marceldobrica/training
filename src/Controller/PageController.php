<?php

namespace App\Controller;

use App\Entity\Building;
use App\Entity\Room;
use App\Entity\User;
use App\Entity\Programme;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PageController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/message", methods={"GET"})
     */
    public function index(): Response
    {
        //I'm able to make and save a building...
//        $building = new Building();
//        $building->setStartTime(new \DateTime('now'));
//        $building->setEndTime(new \DateTime('+12 hours'));
//        $this->entityManager->persist($building);
//        $this->entityManager->flush();

        //I'm able to make and save a room...
//        $buildingRepository = $this->entityManager->getRepository(Building::class);
//        $building = $buildingRepository->find(1);
//        $room = new Room();
//        $room->capacity = 10;
//        $room->name = 'Camera albastra';
//        $room->setBuilding($building);
//        $this->entityManager->persist($room);
//        $this->entityManager->flush();

//        //I'm able to make and save a user...
//        $customer = new User();
//        $customer->setRoles(['customer', 'user']);
//        $customer->setPassword('PAROLA');
////        $programme = new Programme();
////        $programme->setStartDate(new \DateTime('now'));
////        $programme->setEndDate(new \DateTime('+2 hours'));
////        $programme->setCustomers(new ArrayCollection([$customer]));
//        $this->entityManager->persist($customer);
////        $this->entityManager->persist($programme);
//        $this->entityManager->flush();

//        //Verify add user to programe/delete user from programe add user 7 to program 3 and delete
//        $userRepository = $this->entityManager->getRepository(User::class);
//        $user = $userRepository->find(7);
//        $programmeRepository = $this->entityManager->getRepository(Programme::class);
//        $programme = $programmeRepository->find(3);
////        $user->addProgramme($programme);
//
////        $user->removeProgramme($programme);
//        $programme->addCustomer($user);
//        $this->entityManager->persist($user);
//        $this->entityManager->flush();
        return new Response('Some response');
    }

    /**
     * @Route("/cars", methods={"POST"})
     */
    public function cars(Request $request): Response
    {
        return new JsonResponse($request->getContent(), Response::HTTP_ACCEPTED, []);
    }
}
