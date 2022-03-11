<?php

declare(strict_types=1);

namespace App\Feeder;

use App\Animal\Animal;
use App\Animal\Duck;
use Symfony\Component\Validator\Constraints\Range;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AnimalFeeder
{
    private ValidatorInterface $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    public function feedBird(Animal $animal): bool
    {
        if (!$animal instanceof Duck) {
            throw new \UnexpectedValueException('Vrem egalitate, dar nu pentru catei');
        }

        $rangeConstraint = new Range([
            'min' => 1,
            'max' => 100,
            'notInRangeMessage' => 'You must not be hungry or too fat',
        ]);

        $errors = $this->validator->validate($animal->getBelly(), $rangeConstraint);

        if (count($errors) > 0) {
            return false;
        }

        $animal->setBelly($animal->getBelly() + 1);

        return true;
    }
}
