<?php

declare(strict_types=1);

namespace App\Validator;

use App\Entity\Programme;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class ProgrammeBuildingTimeValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        if (!$value instanceof Programme) {
            throw new UnexpectedTypeException($value, Programme::class);
        }

        if (!$constraint instanceof ProgrammeBuildingTime) {
            throw new UnexpectedTypeException($constraint, ProgrammeBuildingTime::class);
        }

        if ($value->isOnline) {
            return;
        }

        $startTimeBuilding = new \DateTime($value->getRoom()->getBuilding()->getStartTime()->format('H:i'));
        $endTimeBuilding = new \DateTime($value->getRoom()->getBuilding()->getEndTime()->format('H:i'));
        $startTimeProgramme = new \DateTime($value->getStartDate()->format('H:i'));
        $endTimeProgramme = new \DateTime($value->getEndDate()->format('H:i'));

        if ($startTimeProgramme >= $startTimeBuilding && $endTimeProgramme <= $endTimeBuilding) {
            return;
        }

        $this->context->buildViolation($constraint->message)->addViolation();
    }
}
