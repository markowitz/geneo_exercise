<?php

namespace App\Controller;

use App\Dto\PostRequest;
use App\Dto\ImageRequest;
use App\Services\RequestService;
use App\Controller\Traits\ControllersTrait;
use Symfony\Component\HttpFoundation\{Request, Response};
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
abstract class BaseController extends AbstractController
{

    use ControllersTrait;

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

    public function createOrUpdateForPosts(Request $request, $postData = null)
    {
        $requestBody = $this->transformJsonBody($request);

        $dto = $this->requestService->mapContent($requestBody, PostRequest::class);

        $imageDtos = $this->imageDtos($request);

        $errors =   $this->validator->validate($dto);

        if (count($errors)) {
            $this->unlinkImages($imageDtos);

            return $this->json([
                    'message' =>  'Validation Error',
                    'errors' => $this->validationErrorResponse($errors)
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        try {

            $post = $postData ? $this->updatePost($postData, $dto, $imageDtos) : $this->createPost($dto, $imageDtos);

        } catch(\Exception $e) {

            $this->unlinkImages($imageDtos);

            return $this->json([
                'message' => 'an error occurred',
                'errors' => $e->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        }

        $post = $this->postTransformer->transformFromObject($post);

        $responseMessage = $postData ? "post updated successfully" : "post created successfully";

        $responseStatus  = $postData ? Response::HTTP_OK : Response::HTTP_CREATED;

        return $this->json([
            'message' => $responseMessage,
            'data' => $post
        ], $responseStatus);
    }

    /**
     * @param Request $request
     * @return Array $imageDtos
     */
    protected function imageDtos($request)
    {
        $imageDtos = [];

        if ($request->get('images') || $request->files->count() > 0) {

            $request = $request->get('images') ?? $request->files->all()['images'];

            $imageDtos = $this->requestService->mapRequestToFiles(
                            $request,
                            ImageRequest::class
                        );

        }

        return $imageDtos;
    }

    /**
     * create post repository
     * @param Post $post
     * @param array $imageDtos
     * @return Post $post
     */
    protected function createPost($dto, $imageDtos)
    {
        return $this->postRepo->create($dto, $this->getUser(), $imageDtos);
    }

    /**
     * update post repository
     * @param Post $post
     * @param [type] $dto
     * @param array $imageDtos
     * @return Post $post
     */
    protected function updatePost($post, $dto, $imageDtos)
    {
        return $this->postRepo->update($post, $dto, $this->getUser(), $imageDtos);
    }

    public function serviceRequest($request, $className)
    {
        return $this->requestService->mapContent($request, $className);
    }

}