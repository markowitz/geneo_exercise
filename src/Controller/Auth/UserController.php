<?php

namespace App\Controller\Auth;

use App\Entity\User;
use App\Dto\Transformer\PostTransformer;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @var PostRepo
     */
    private $postRepo;

    /**
     * @var PostTransformer
     */
    private $postTransformer;

    /**
     * @var UserRepo
     */
    private $userRepo;

    public function __construct(PostRepository $postRepo, PostTransformer $postTransformer, UserRepository $userRepo)
    {
        $this->postRepo = $postRepo;
        $this->postTransformer = $postTransformer;
        $this->userRepo = $userRepo;
    }

    /**
     * @Route("/api/user/posts")
     */
    public function fetchUserPendingPosts()
    {
        $user = $this->getUser();
        $posts = $this->postRepo->fetchUserPendingPosts($user);
        $posts = $this->postTransformer->transformFromObjects($posts);

        return $this->json([
            'data' => $posts
        ], Response::HTTP_OK);
    }
}
