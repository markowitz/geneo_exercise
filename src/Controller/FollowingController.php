<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Annotation\Route;

class FollowingController extends AbstractController
{
    /**
     * @var UserRepo
     */
    private $userRepo;


    public function __construct(UserRepository $userRepo)
    {
        $this->userRepo = $userRepo;
    }


    /**
     * @Route("/api/follow/{id}", name="api_follow")
     */
    public function follow(User $user)
    {
        $this->userRepo->addFollowing($this->getUser(), $user);

        return $this->json([
            'message' => 'user followed'
        ], Response::HTTP_OK);
    }

    /**
     * @Route("/api/unfollow/{id}", name="api_unfollow")
     */
    public function unfollow(User $user)
    {
        $checkUser = $this->getUser()->getFollowing()->contains($user);

        if (!$checkUser) {
            return $this->json([
                'message' => 'you do not follow this user'
            ], Response::HTTP_BAD_REQUEST);
        }

        $this->userRepo->removeFollower($this->getUser(), $user);

        return $this->json([
            'message' => 'user followed'
        ], Response::HTTP_OK);
    }
}
