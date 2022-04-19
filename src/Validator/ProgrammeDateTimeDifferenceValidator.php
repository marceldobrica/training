<?php

declare(strict_types=1);

namespace App\Validator;

use App\Entity\Programme;
use Symfony\Component\HttpFoundation\File\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ProgrammeDateTimeDifferenceValidator extends ConstraintValidator
{
    private int $programmeMaxTimeInMinutes;

    private int $programmeMinTimeInMinutes;

    public function __construct(string $programmeMaxTimeInMinutes, string $programmeMinTimeInMinutes)
    {
        $this->programmeMaxTimeInMinutes = intval($programmeMaxTimeInMinutes);
        $this->programmeMinTimeInMinutes = intval($programmeMinTimeInMinutes);
    }

    public function validate($value, Constraint $constraint)
    {
        if (!$value instanceof Programme) {
            throw new UnexpectedTypeException($value, Programme::class);
        }

        if (!$constraint instanceof ProgrammeDateTimeDifference) {
            throw new UnexpectedTypeException($constraint, ProgrammeDateTimeDifference::class);
        }
        $interval = $value->getStartDate()->diff($value->getEndDate(), false);

        if (
            $value->getEndDate() > $value->getStartDate() &&
            $interval->y === 0 &&
            $interval->m === 0 &&
            $interval->d === 0 &&
            $interval->h * 60 + $interval->i >= $this->programmeMinTimeInMinutes &&
            $interval->h * 60 + $interval->i <= $this->programmeMaxTimeInMinutes
        ) {
            return;
        }
        $this->context->buildViolation($constraint->message)->setParameters([
            '{{ minTimeMin }}' => $this->programmeMinTimeInMinutes,
            '{{ maxTimeMin }}' => $this->programmeMaxTimeInMinutes
        ])->addViolation();
    }
}
