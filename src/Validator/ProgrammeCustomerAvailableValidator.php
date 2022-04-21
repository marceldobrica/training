<?php

declare(strict_types=1);

namespace App\Validator;

use App\Entity\Programme;
use App\Repository\ProgrammeRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class ProgrammeCustomerAvailableValidator extends ConstraintValidator
{
    private ProgrammeRepository $programmeRepository;

    public function __construct(ProgrammeRepository $programmeRepository)
    {
        $this->programmeRepository = $programmeRepository;
    }

    public function validate($value, Constraint $constraint)
    {
        if (!$value instanceof Programme) {
            throw new UnexpectedTypeException($value, Programme::class);
        }

        if (!$constraint instanceof ProgrammeCustomerAvailable) {
            throw new UnexpectedTypeException($constraint, ProgrammeCustomerAvailable::class);
        }

        foreach ($value->getCustomers() as $customer) {
            $resultTrainer = $this->programmeRepository->isUserOcupiedAsTrainer(
                $value->getStartDate(),
                $value->getEndDate(),
                $customer->getId()
            );

            $resultCustomer = $this->programmeRepository->isUserOcupiedAsCustomer(
                $value->getStartDate(),
                $value->getEndDate(),
                $customer->getId()
            );

            if (
                (count($resultCustomer) == 1 && $resultCustomer[0]['programmeid'] === $value->getId()) ||
                empty($resultCustomer) &&
                empty($resultTrainer)
            ) {
                return;
            }
        }

        $this->context->buildViolation($constraint->message)->addViolation();
    }
}
