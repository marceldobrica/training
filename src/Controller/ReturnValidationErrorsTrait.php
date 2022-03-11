<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolation;

trait ReturnValidationErrorsTrait
{
    public function returnValidationErrors($errors): Response
    {
        $errorArray = [];

        /* @var ConstraintViolation $error */
        foreach ($errors as $error) {
            $errorArray[$error->getPropertyPath()] = $error->getMessage();
        }

        return new JsonResponse($errorArray);
    }
}
