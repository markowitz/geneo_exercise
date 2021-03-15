<?php

namespace App\Controller\Posts;

use App\Entity\Post;
use App\Controller\BaseController;
use App\Dto\PostCommentRequest;
use App\Dto\Transformer\PostCommentTransformer;
use App\Exceptions\HttpException;
use App\Repository\PostCommentRepository;
use App\Services\RequestService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class PostCommentController extends BaseController
{

    /**
     * @var PostCommentRepo
     */
    private $postCommentRepo;

    /**
     * @var postCommentTransformer
     */
    private $postCommentTransformer;

    public function __construct(RequestService $requestService, ValidatorInterface $validator, PostCommentRepository $postCommentRepo, PostCommentTransformer $postCommentTransformer)
    {
        parent::__construct($requestService, $validator);

        $this->postCommentRepo = $postCommentRepo;
        $this->postCommentTransformer = $postCommentTransformer;

    }
    /**
     * @Route("/api/post/{post}/comment", name="post_comment", methods={"POST"})
     */
    public function create(Request $request, Post $post): Response
    {
        $this->denyAccessUnlessGranted('create', $post);

        $request = $this->transformJsonBody($request);

        $dto = $this->serviceRequest($request, PostCommentRequest::class);

        try {
            $postComment = $this->postCommentRepo->create($post, $dto, $this->getUser());
            $postComment = $this->postCommentTransformer->transformFromObject($postComment);

        } catch (HttpException $e) {

            throw new HttpException($e->getMessage(), Response::HTTP_BAD_REQUEST);

        }

        return $this->json([
            'data' => $postComment
        ], Response::HTTP_CREATED);
    }
}
