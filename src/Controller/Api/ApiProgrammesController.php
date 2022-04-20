<?php

namespace App\Controller\Api;

use App\Controller\Dto\ProgrammeDto;
use App\Controller\ReturnValidationErrorsTrait;
use App\Entity\Programme;
use App\Repository\ProgrammeRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

use function App\Controller\count;

/**
 * @Route (path="/api/programmes")
 */
class ApiProgrammesController
{
    use ReturnValidationErrorsTrait;

    private int $articlesOnPage;

    private ValidatorInterface $validator;

    private ProgrammeRepository $programmeRepository;

    public function __construct(
        ProgrammeRepository $programmeRepository,
        ValidatorInterface $validator,
        string $articlesOnPage
    ) {
        $this->programmeRepository = $programmeRepository;
        $this->validator = $validator;
        $this->articlesOnPage = intval($articlesOnPage);
    }

    /**
     * @Route(methods={"POST"})
     */
    public function createProgrammeAction(ProgrammeDto $programmeDto): Response
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
    public function showAllPaginatedSortedFiltered(Request $request): array
    {
        $pager = [];
        $pager['page'] = $request->query->get('page', 1);
        $pager['size'] = $request->query->get('size', $this->articlesOnPage);

        $filters = [];
        $filters['name'] = $request->query->get('name', null);
        $filters['id'] = $request->query->get('id', null);
        $filters['isOnline'] = $request->query->get('isOnline', null);
        if (null !== $filters['isOnline']) {
            $filters['isOnline'] = $request->query->getBoolean('isOnline');
        }

        $sorter = $request->query->get('sortBy', null);
        $direction = $request->query->get('orderBy', null);

        return $this->programmeRepository
            ->showAllPaginatedSortedFiltered($pager, $filters, $sorter, $direction);
    }
}
