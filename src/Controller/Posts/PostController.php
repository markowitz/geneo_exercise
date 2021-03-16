<?php

namespace App\Controller\Posts;

use App\Controller\BaseController;
use App\Dto\ApprovalRequest;
use App\Dto\Transformer\PostTransformer;
use App\Entity\Post;
use App\Entity\User;
use App\Repository\PostRepository;
use App\Services\RequestService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class PostController extends BaseController
{
    /**
     * @var PostRepo
     */
    public $postRepo;

    /**
     * @var PostTransformer
     */
    public $postTransformer;

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
        return parent::createOrUpdateForPosts($request);
    }

    /**
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
     * @Route("/api/post/{slug}", name="fetch_post", methods={"GET"})
     */
    public function show(String $slug)
    {

        try {

            $post = $this->postRepo->findOneBy(['slug' => $slug]);

        } catch(\Throwable $e) {

            throw $this->createNotFoundException('page not found');
        }

        $this->denyAccessUnlessGranted('view', $post);

        $post = $this->postTransformer->transformFromObject($post);

        return $this->json([
            'data' => $post
        ], Response::HTTP_OK);

    }


      /**
     * @Route("/api/posts/pending", name="pending_posts", methods={"GET"})
     */
    public function fetchPendingPosts()
    {
        $this->denyAccessUnlessGranted(User::ADMIN);
        $posts = $this->postRepo->fetchPendingPosts();
        $posts = $this->postTransformer->transformFromObjects($posts);

        return $this->json([
            'data' => $posts
        ], Response::HTTP_OK);
    }

    /**
     * @Route("/api/post/{id}/approval", name="pending_posts", methods={"POST"})
     */
    public function approval(Request $request, Post $post)
    {

        $this->denyAccessUnlessGranted(User::ADMIN);

        try {

            $post = $this->postRepo->findOneBy(['slug' => $post->getSlug()]);

        } catch(\Throwable $e) {

            throw $this->createNotFoundException('page not found');
        }

        $request = $this->transformJsonBody($request);

        $dto = $this->serviceRequest($request, ApprovalRequest::class);

        $errors = $this->validator->validate($dto);

        if (count($errors)) {
            return $this->json([
                    'message' =>  'Validation Error',
                    'errors' => $this->validationErrorResponse($errors)
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {

            $this->postRepo->approval($post, $dto->approved);

        } catch(\Exception $e) {
            throw new HttpException($e->getMessage());
        }

        return $this->json([
            'message' => "post approved successfully"
        ], Response::HTTP_OK);
    }

    /**
     * @Route("/api/post/{id}/edit", name="edit_post", methods={"POST"})
     */
    public function edit(Post $post, Request $request)
    {
        $this->denyAccessUnlessGranted('edit', $post);

        return parent::createOrUpdateForPosts($request, $post);
    }

    /**
     * @Route("/api/post/{id}", name="delete_post", methods={"DELETE"})
     */
    public function delete(Post $post)
    {
        $this->denyAccessUnlessGranted('delete', $post);

        try {
            $this->postRepo->delete($post);
        } catch(\Exception $e) {

            throw new HttpException(Response::HTTP_BAD_REQUEST, $e->getMessage());

        }

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }

}


