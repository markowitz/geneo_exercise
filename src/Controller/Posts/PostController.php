<?php

namespace App\Controller\Posts;

use App\Entity\Post;
use App\Services\RequestService;
use App\Repository\PostRepository;
use App\Dto\{PostRequest, ImageRequest};
use App\Controller\Traits\ControllersTrait;
use App\Dto\Transformer\PostTransformer;
use App\Services\ImageUploader;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\{Request, Response};
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class PostController extends AbstractController
{
    use ControllersTrait;

    /**
     * @var PostRepo
     */
    private $postRepo;

    /**
     * @var PostTransformer
     */
    private $postTransformer;

    /**
     * @var RequestService
     */
    private $requestService;

    /**
     * @var Validator
     */
    private $validator;

    /**
     * @var ImageUploader
     */
    private $imageUploader;


    public function __construct(RequestService $requestService,
                                ValidatorInterface $validator,
                                PostRepository $postRepo,
                                PostTransformer $postTransformer,
                                ImageUploader $imageUploader) {

        $this->requestService = $requestService;
        $this->validator = $validator;
        $this->postRepo = $postRepo;
        $this->postTransformer = $postTransformer;
        $this->imageUploader = $imageUploader;
    }

    /**
     * create post
     * @Route("/api/post", name="create_post", methods={"POST"})
     */
    public function create(Request $request)
    {
        $requestBody = $this->transformJsonBody($request);

        $postRequestDto = $this->requestService->mapContent($requestBody, PostRequest::class);

        $errors =   $this->validator->validate($postRequestDto);

        if (count($errors)) {
            return $this->json([
                    'message' =>  'Validation Error',
                    'errors' => $this->validationErrorResponse($errors)
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $imageFilePaths = [];

        if ($postRequestDto->images) {
            $imageFilePaths = $this->imageUploader->handleUpload($postRequestDto->images);
        }

        $post = $this->postRepo->create($postRequestDto, $this->getUser(), $imageFilePaths);

        $post = $this->postTransformer->transformFromObject($post);

        return $this->json([
            'message' => "post created successfully",
            'data' => $post
        ], Response::HTTP_CREATED);
    }

     /**
      * edit post
     * @Route("/api/post/{id}/edit", name="edit_post", methods={"POST"})
     */
    public function edit(Request $request, $id)
    {
        $post = $this->postRepo->find($id);

        if (!$post) {
            return $this->json([
                'message' => 'post not found'
            ], Response::HTTP_NOT_FOUND);
        }

        $this->denyAccessUnlessGranted('edit', $post);

        $requestBody = $this->transformJsonBody($request);

        $postRequestDto = $this->requestService->mapContent($requestBody, PostRequest::class);

        $errors =   $this->validator->validate($postRequestDto);

        if (count($errors)) {

            return $this->json([
                    'message' =>  'Validation Error',
                    'errors' => $this->validationErrorResponse($errors)
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $imageFilePaths = [];

        if ($postRequestDto->images) {
            $imageFilePaths = $this->imageUploader->handleUpload($postRequestDto->images);
        }

        $post = $this->postRepo->update($post, $postRequestDto, $this->getUser(), $imageFilePaths);
        $post = $this->postTransformer->transformFromObject($post);

        return $this->json([
            'message' => "post updated successfully",
            'data' => $post
        ], Response::HTTP_OK);

    }

    /**
     * fetch posts
     * @Route("/api/posts", name="api_posts", methods={"GET"})
    */
    public function fetchPosts()
    {
        $user = $this->getUser();

        $posts = $this->postRepo->fetchApproved($user);

        $posts = $this->postTransformer->transformFromObjects($posts);

        return $this->json([
            'data' => $posts
        ], Response::HTTP_OK);

    }

    /**
     * view single post
     * @Route("/api/post/{slug}", name="fetch_post", methods={"GET"})
     */
    public function show(String $slug)
    {
        $post = $this->postRepo->findOneBy(['slug' => $slug]);

        if (!$post) {

            return $this->json([
                'message' => 'Not found'
            ], Response::HTTP_NOT_FOUND);
        }

        $this->denyAccessUnlessGranted('view', $post);

        $post = $this->postTransformer->transformFromObject($post);

        return $this->json([
            'data' => $post
        ], Response::HTTP_OK);

    }

    /**
     * delete post
     * @Route("/api/post/{id}", name="delete_post", methods={"DELETE"})
     */
    public function delete($id)
    {
        $post = $this->postRepo->find($id);

        if (!$post) {

            return $this->json([
                'message' => 'post not found'
            ], Response::HTTP_NOT_FOUND);
        }

        $this->denyAccessUnlessGranted('delete', $post);

        $this->postRepo->delete($post);

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }

}


