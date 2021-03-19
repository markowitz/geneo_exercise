<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class ApprovalRequest
{
    /**
     * @Assert\NotBlank
     * @Assert\NotNull
     * @Assert\Choice(0, 1)
     */
    public $approved;
}