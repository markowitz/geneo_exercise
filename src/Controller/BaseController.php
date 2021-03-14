<?php

namespace App\Controller;

use App\Services\RequestService;
use App\Controller\Traits\ControllersTrait;
use App\Services\UploaderService;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class BaseController extends AbstractController
{

     /**
     * @var RequestService
     */
    public $requestService;

     /**
     * @var Validator
     */
    public $validator;

    public function __construct(RequestService $requestService, ValidatorInterface $validator)
    {
        $this->requestService = $requestService;
        $this->validator = $validator;
    }

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

    public function response(array $data, $status = Response::HTTP_OK)
    {
        return $this->json($data, $status);
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