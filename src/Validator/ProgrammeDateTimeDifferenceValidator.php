<?php

declare(strict_types=1);

namespace App\Validator;

use App\Entity\Programme;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

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
        $minutes = $interval->days * 24 * 60 + $interval->h * 60 + $interval->i;

        if (
            $value->getEndDate() > $value->getStartDate() &&
            $minutes >= $this->programmeMinTimeInMinutes &&
            $minutes <= $this->programmeMaxTimeInMinutes
        ) {
            return;
        }
        $this->context->buildViolation($constraint->message)->setParameters([
            '{{ minTimeMin }}' => $this->programmeMinTimeInMinutes,
            '{{ maxTimeMin }}' => $this->programmeMaxTimeInMinutes
        ])->addViolation();
    }
}
