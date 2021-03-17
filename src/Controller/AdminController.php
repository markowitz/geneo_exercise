<?php

namespace App\Controller;

use App\Controller\Traits\ControllersTrait;
use App\Dto\Transformer\PostTransformer;
use App\Entity\Post;
use App\Entity\User;
use App\Repository\PostRepository;
use App\Services\RequestService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Annotation\Route;
use App\Dto\ApprovalRequest;
use App\Repository\UserRepository;

class AdminController extends AbstractController
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
     * @var UserRepo
     */
    private $userRepo;

    public function __construct(PostRepository $postRepo, PostTransformer $postTransformer, RequestService $requestService, UserRepository $userRepo)
    {
        $this->postRepo = $postRepo;
        $this->postTransformer = $postTransformer;
        $this->requestService = $requestService;
        $this->userRepo = $userRepo;
    }

    /**
     * @Route("/api/admin/pending-posts", name="pending_posts", methods={"GET"})
     */
    public function fetchAllPendingPosts()
    {
        $this->denyAccessUnlessGranted(User::ADMIN);
        $posts = $this->postRepo->fetchAllPendingPosts();
        $posts = $this->postTransformer->transformFromObjects($posts);

        return $this->json([
            'data' => $posts
        ], Response::HTTP_OK);
    }

     /**
     * @Route("/api/admin/post/{id}/approval", name="approve_post", methods={"POST"})
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

        $dto = $this->requestService->mapContent($request, ApprovalRequest::class);

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
     * @Route("/api/admin/user/{user}", name="admin_delete_user", methods={"DELETE"})
     */
    public function delete(User $user)
    {
        $this->denyAccessUnlessGranted(User::ADMIN);

        try {

            $this->userRepo->delete($user);

        } catch(\Exception $e) {
            throw new HttpException(Response::HTTP_BAD_REQUEST, $e->getMessage());
        }

        return $this->json(null, Response::HTTP_NO_CONTENT);


    }
}
