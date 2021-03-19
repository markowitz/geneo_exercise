<?php

namespace App\Controller\Traits;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

trait ControllersTrait
{
     /**
     * Transform $request Body
     * @param Request $request
     * @return
     */
    public function transformJsonBody($request)
    {
        if ($request->getContentType() !== 'json') {

            throw new HttpException(Response::HTTP_UNSUPPORTED_MEDIA_TYPE, 'only accepts content-type application/json');

        }

        return $request->getContent();
    }

    /**
     * Format Validation errors
     * @param Symfony\Component\Validator\ConstraintViolationList $errors
     * @return array $errorRepsponse
     */
    public function validationErrorResponse($errors)
    {

        $errorResponse = [];

        foreach ($errors as $key => $error) {
            $errorTitle = str_replace(['[', ']'], '', $error->getPropertyPath());
            $errorResponse[$errorTitle][$key] = $error->getMessage();
        }

        return $errorResponse;
    }

}