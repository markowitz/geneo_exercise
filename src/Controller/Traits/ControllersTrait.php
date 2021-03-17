<?php

namespace App\Controller\Traits;

trait ControllersTrait
{
     /**
     * Transform $request Body
     * @param Request $request
     * @return
     */
    public function transformJsonBody($request)
    {
        if ($request->getContentType() == 'json') {

            $data = json_decode($request->getContent(), true);

            $request->request->replace($data);
        }

        return $request;
    }

    /**
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