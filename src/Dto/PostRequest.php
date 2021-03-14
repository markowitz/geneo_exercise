<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class PostRequest
{
    /**
     * @Assert\NotBlank
     * @Assert\Length(min=3)
     */
    public $title;

    /**
     * @Assert\NotBlank
     * @Assert\Length(min=8)
     */
    public $content;

    public $author;

    public $images;

    public $createdAt;

    public $updatedAt;
}