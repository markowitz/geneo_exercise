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
     * @Route("/api/user/delete/{user}", name="api_user")
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

    /**
     * @Route("/api/user/posts", "api_user_posts")
     */
    public function fetchPendingPosts()
    {
        $user = $this->getUser();
        $posts = $this->postRepo->fetchUserPendingPosts($user);
        $posts = $this->postTransformer->transformFromObjects($posts);

        return $this->json([
            'data' => $posts
        ], Response::HTTP_OK);
    }
}
