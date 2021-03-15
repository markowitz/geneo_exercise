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

        foreach ($errors as $error) {

            $errorTitle = str_replace(['[', ']'], '', $error->getPropertyPath());
            $errorResponse[$errorTitle] = $error->getMessage();

        }
        return $errorResponse;
    }

    /**
     * @param array $imageDtos
     */
    public function unlinkImages($imageDtos)
    {
        if (count($imageDtos)) {

            array_walk($imageDtos, function($imageDto) {
                unlink('uploads/images/'.$imageDto->file_name);
            });

        }
    }
}