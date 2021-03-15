<?php

namespace App\Controller;

use Exception;
use App\Dto\PostRequest;
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

    public function createOrUpdateForPosts(Request $request, $post = null)
    {
        $request = $this->transformJsonBody($request);
        $dto = $this->requestService->mapContent($request, PostRequest::class);
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

            $post = $post ? $this->updatePost($post, $dto, $imageDtos) : $this->createPost($dto, $imageDtos);

        } catch(Exception $e) {

            $this->unlinkImages($imageDtos);

            return $this->json([
                'message' => 'an error occurred while trying to register',
                'errors' => $e->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        }

        $post = $this->postTransformer->transformFromObject($post);

        return $this->json([
            'message' => 'post created successfully',
            'data' => $post
        ], Response::HTTP_CREATED);
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