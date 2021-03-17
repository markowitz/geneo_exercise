<?php

namespace App\Controller\Posts;

use App\Entity\Post;
use App\Controller\BaseController;
use App\Controller\Traits\ControllersTrait;
use App\Dto\PostCommentRequest;
use App\Dto\Transformer\PostCommentTransformer;
use App\Repository\PostCommentRepository;
use App\Services\RequestService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class PostCommentController extends AbstractController
{

    use ControllersTrait;

    /**
     * @var PostCommentRepo
     */
    private $postCommentRepo;

    /**
     * @var postCommentTransformer
     */
    private $postCommentTransformer;

    /**
     * @var RequestService
     */
    public $requestService;

     /**
     * @var Validator
     */
    public $validator;

    public function __construct(RequestService $requestService,
                                ValidatorInterface $validator,
                                PostCommentRepository $postCommentRepo,
                                PostCommentTransformer $postCommentTransformer)
    {
        $this->requestService  = $requestService;
        $this->validator  = $validator;
        $this->postCommentRepo = $postCommentRepo;
        $this->postCommentTransformer = $postCommentTransformer;

    }
    /**
     * @Route("/api/post/{post}/comment", name="post_comment", methods={"POST"})
     */
    public function create(Request $request, Post $post): Response
    {
        $this->denyAccessUnlessGranted('view', $post);

        $request = $this->transformJsonBody($request);

        $postCommentDto = $this->requestService->mapContent($request, PostCommentRequest::class);

        $errors = $this->validator->validate($postCommentDto);

        if (count($errors)) {
            return $this->json([
                'message' => 'Validation Error',
                'errors' => $this->validationErrorResponse($errors)
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $postComment = $this->postCommentRepo->create($post, $postCommentDto, $this->getUser());
        $postComment = $this->postCommentTransformer->transformFromObject($postComment);

        return $this->json([
            'data' => $postComment
        ], Response::HTTP_CREATED);
    }
}
