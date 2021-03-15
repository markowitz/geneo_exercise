<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class ApprovalRequest
{
    /**
     * @Assert\NotBlank
     * @Assert\NotNull
     * @Assert\Type("bool")
     */
    public $approved;
}