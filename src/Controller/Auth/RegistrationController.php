<?php

namespace App\Controller\Auth;

use Exception;
use App\Dto\RegisterRequest;
use App\Controller\BaseController;
use App\Http\ApiResponse;
use App\Repository\UserRepository;
use App\Services\RequestService;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RegistrationController extends BaseController
{

    /**
     * @var UserRepo
     */
    private $userRepo;

    public function __construct(RequestService $requestService, ValidatorInterface $validator, UserRepository $userRepo)
    {
        parent::__construct($requestService, $validator);
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
            return new ApiResponse('Validation Error',
                     null,
                     $this->validationErrorResponse($errors),
                     Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $user = $this->userRepo->findBy([
            'email' => $dto->email
        ]);

        if ($user) {
            return $this->json([
                'error' => 'user already exists'
            ], Response::HTTP_BAD_REQUEST);
        }


        try {

            $this->userRepo->create($dto);

        } catch (UniqueConstraintViolationException $exception) {

          throw new HttpException(Response::HTTP_BAD_REQUEST, $exception->getMessage());
        }

        return $this->json([
            'message' => 'user registered successfully'
            ], Response::HTTP_CREATED);
    }
}
