<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class PostCommentRequest
{

    /**
     * @Assert\NotBlank
     * @Assert\NotNull
     */
    public $comment;

    public $user;

}