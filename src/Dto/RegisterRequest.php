<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class RegisterRequest
{
    /**
     * @Assert\NotBlank
     * @Assert\Length(min=3)
     */
    public $name;

    /**
     * @Assert\NotBlank
     * @Assert\Email
     */
    public $email;

    /**
     * @Assert\NotBlank
     * @Assert\Length(min=8)
     * @Assert\Regex(
     * pattern="/\d/",
     * match=true,
     * message="password must contain at least a number"
     * )
     */
    public $password;

    public $roles = [];
}