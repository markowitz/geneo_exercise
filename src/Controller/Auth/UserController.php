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
     * @Route("/api/admin/user/{id}", name="admin_delete_user", methods={"DELETE"})
     */
    public function delete(User $user)
    {
        $this->denyAccessUnlessGranted(User::ADMIN);

        $this->userRepo->delete($user);

        return $this->json(null, Response::HTTP_NO_CONTENT);

    }
}
