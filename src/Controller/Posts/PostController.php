<?php

namespace App\Controller\Posts;

use App\Controller\BaseController;
use App\Controller\Traits\ControllersTrait;
use App\Dto\ImageRequest;
use App\Dto\PostRequest;
use App\Dto\Transformer\PostTransformer;
use App\Repository\PostRepository;
use App\Services\RequestService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class PostController extends BaseController
{
    /**
     * @var PostRepo
     */
    private $postRepo;

    /**
     * @var PostTransformer
     */
    private $postTransformer;

    public function __construct(RequestService $requestService, ValidatorInterface $validator, PostRepository $postRepo, PostTransformer $postTransformer)
    {
        parent::__construct($requestService, $validator);

        $this->postRepo = $postRepo;
        $this->postTransformer = $postTransformer;
    }

    /**
     * @Route("/api/post", name="api_post", methods={"POST"})
     */
    public function create(Request $request)
    {

        $request = $this->transformJsonBody($request);

        $dto = $this->requestService->mapContent($request, PostRequest::class);

        $imageDtos = [];

        if ($request->get('images') || $request->files->count() > 0) {

            $request = $request->get('images') ?? $request->files->all()['images'];

            $imageDtos = $this->requestService->mapRequestToFiles(
                            $request,
                            ImageRequest::class
                        );

        }

        $errors = $this->validator->validate($dto);

        if (count($errors)) {
            $this->unlinkImages($imageDtos);

            return $this->response([
                    'message' =>  'Validation Error',
                    'errors' => $this->validationErrorResponse($errors)
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {

            $post = $this->postRepo->create($dto, $this->getUser(), $imageDtos);

        } catch(Exception $e) {

            $this->unlinkImages($imageDtos);

            return $this->response([
                'message' => 'an error occurred while trying to register',
                'errors' => $e->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        }

        $post = $this->postTransformer->transformFromObject($post);

        return $this->response([
            'message' => 'post created successfully',
            'data' => $post
        ], Response::HTTP_CREATED);

    }

    /**
     * @Route("/api/post/{slug}", name="fetch_post", methods={"GET"})
     */
    public function fetch($slug)
    {

        $post = $this->postRepo->findOneBy([
            'slug' => $slug
        ]);

        if (!$post) {
            return $this->response([
                'message' => "post doesn't exist"
            ], Response::HTTP_NOT_FOUND);
        }

        $post = $this->postTransformer->transformFromObject($post);

        return $this->response([
            'data' => $post
        ], Response::HTTP_OK);
    }
}


