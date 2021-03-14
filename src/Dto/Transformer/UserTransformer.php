<?php

namespace App\Dto\Transformer;

use App\Dto\Response\UserResponse;

class UserTransformer extends AbstractTransformer
{
    /**
     * @param User $user
     * @return UserResponse
     */
    public function transformFromObject($user): ?UserResponse
    {
        if ($user) {
            $dto = new UserResponse();
            $dto->name = $user->getName();
            $dto->email = $user->getEmail();
            $dto->roles = $user->getRoles();
            $dto->createdAt = $user->getCreatedAt();
            $dto->updatedAt = $user->getUpdatedAt();

            return $dto;
        }

        return null;

    }
}