<?php

namespace App\Controller;

use App\Controller\Dto\ProgrammeDto;
use App\Entity\Programme;
use App\Repository\ProgrammeRepository;
use Symfony\Component\HttpFoundation\AcceptHeader;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route (path="/api/programme")
 */
class ProgrammeController
{
    use ReturnValidationErrorsTrait;

    private int $articlesOnPage;

    private ValidatorInterface $validator;

    private SerializerInterface $serializer;

    private ProgrammeRepository $programmeRepository;

    public function __construct(
        ProgrammeRepository $programmeRepository,
        ValidatorInterface $validator,
        SerializerInterface $serializer,
        string $articlesOnPage
    ) {
        $this->programmeRepository = $programmeRepository;
        $this->validator = $validator;
        $this->serializer = $serializer;
        $this->articlesOnPage = intval($articlesOnPage);
    }

    /**
     * @Route(methods={"POST"})
     */
    public function register(ProgrammeDto $programmeDto): Response
    {
        $programme = Programme::createFromDto($programmeDto);

        $errors = $this->validator->validate($programme);

        if (count($errors) > 0) {
            return $this->returnValidationErrors($errors);
        }

        $this->programmeRepository->add($programme);
        $savedProgrammeDto = ProgrammeDto::createFromProgramme($programme);

        return new JsonResponse($savedProgrammeDto, Response::HTTP_CREATED);
    }

    /**
     * @Route(methods={"GET"})
     */
    public function showAllPaginatedSortedFiltered(Request $request): Response
    {
        $acceptHeader = AcceptHeader::fromString($request->headers->get('Accept'));

        if (
            !$acceptHeader->has('application/xml') &&
            !$acceptHeader->has('application/json')
        ) {
            return new Response(
                "Bad request. Only accepted headers: 'application/json', 'application/xml'",
                Response::HTTP_BAD_REQUEST
            );
        }

        $pager = [];
        $pager['currentpage'] = $request->query->get('page', 1);
        $pager['articlesonpage'] = $request->query->get('size', $this->articlesOnPage);

        $filters = [];
        $filters['name'] = $request->query->get('name', '');
        $filters['id'] = $request->query->get('id', '');
        $filters['isOnline'] = $request->query->get('isOnline', '');
        if ($filters['isOnline'] !== '') {
            $filters['isOnline'] = $request->query->getBoolean('isOnline');
        }

        $sorter = $request->query->get('sortBy', '');
        $direction = $request->query->get('orderBy', '');

        if ($acceptHeader->has('application/json')) {
            $serializedProgrammes = $this->serializer->serialize(
                $this->programmeRepository->showAllPaginatedSortedFiltered($pager, $filters, $sorter, $direction),
                'json',
                ['groups' => 'api:programme:all']
            );

            return new JsonResponse($serializedProgrammes, Response::HTTP_OK, [], true);
        }

        if ($acceptHeader->has('application/xml')) {
            $serializedProgrammes = $this->serializer->serialize(
                $this->programmeRepository->showAllPaginatedSortedFiltered($pager, $filters, $sorter, $direction),
                'xml',
                ['groups' => 'api:programme:all']
            );

            return new Response($serializedProgrammes, Response::HTTP_OK, ['Content-Type' => 'application/xml']);
        }

        return new Response('Bad request', Response::HTTP_BAD_REQUEST);
    }
}
