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
use Symfony\Component\Validator\Validator\ValidatorInterface;

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

    /**
     * @var Validator
     */
    private $validator;

    public function __construct(PostRepository $postRepo,
                                PostTransformer $postTransformer,
                                RequestService $requestService,
                                UserRepository $userRepo,
                                ValidatorInterface $validator)
    {

        $this->postRepo = $postRepo;
        $this->postTransformer = $postTransformer;
        $this->requestService = $requestService;
        $this->userRepo = $userRepo;
        $this->validator = $validator;
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
    public function approval(Request $request, $id) {

        $post = $this->postRepo->find($id);

        if (!$post) {
            return $this->json([
                'message' => 'Post not found'
            ], Response::HTTP_NOT_FOUND);
        }

        $this->denyAccessUnlessGranted(User::ADMIN);

        $request = $this->transformJsonBody($request);

        $dto = $this->requestService->mapContent($request, ApprovalRequest::class);

        $errors = $this->validator->validate($dto);

        if (count($errors)) {
            return $this->json([
                    'message' =>  'Validation Error',
                    'errors' => $this->validationErrorResponse($errors)
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $this->postRepo->approval($post, $dto->approved);

        return $this->json([
            'message' => "success"
        ], Response::HTTP_OK);
    }

    /**
     * @Route("/api/admin/user/{id}", name="admin_delete_user", methods={"DELETE"})
     */
    public function delete($id)
    {
        $user = $this->userRepo->find($id);

        if (!$user) {
            return $this->json([
                'message' => 'User not found'
            ], Response::HTTP_NOT_FOUND);
        }

        $this->denyAccessUnlessGranted(User::ADMIN);
        $this->userRepo->delete($user);

        return $this->json(null, Response::HTTP_NO_CONTENT);

    }
}
