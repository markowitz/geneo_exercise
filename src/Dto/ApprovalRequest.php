<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class ApprovalRequest
{
    /**
     * @Assert\NotBlank
     * @Assert\NotNull
     * @Assert\Type("integer")
     * @Assert\Range(min=0, max=1)
     */
    public $approved;
}