<?php

namespace App\Controller\Auth;

use App\Controller\Traits\ControllersTrait;
use App\Dto\RegisterRequest;
use App\Http\ApiResponse;
use App\Repository\UserRepository;
use App\Services\RequestService;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RegistrationController extends AbstractController
{

    use ControllersTrait;

    /**
     * @var UserRepo
     */
    private $userRepo;

     /**
     * @var Validator
     */
    private $validator;

     /**
     * @var RequestService
     */
    private $requestService;

    public function __construct(RequestService $requestService, ValidatorInterface $validator, UserRepository $userRepo)
    {
        $this->requestService = $requestService;
        $this->validator = $validator;
        $this->userRepo = $userRepo;
    }

    /**
     * @Route("/api/register", name="api_register")
     * @param Request $request
     */
    public function register(Request $request)
    {
        $request = $this->transformJsonBody($request);

        $dto = $this->requestService->mapContent($request, RegisterRequest::class);

        $errors = $this->validator->validate($dto);

        if (count($errors)) {
            return $this->json([
                'message' => 'Validation Error',
                'errors' => $this->validationErrorResponse($errors),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $user = $this->userRepo->findOneBy([
                'email' => $dto->email ]);

        if ($user) {
            return $this->json([
                'error' => 'user already exists'
            ], Response::HTTP_BAD_REQUEST);
        }

        $this->userRepo->create($dto);

        return $this->json([
            'message' => 'user registered successfully'
            ], Response::HTTP_CREATED);
    }
}
