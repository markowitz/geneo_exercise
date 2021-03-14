<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class ImageRequest
{
    /**
     * @Assert\NotBlank
     */
    public $file_name;

    /**
     * @Assert\NotBlank
     */
    public $file_path;
}