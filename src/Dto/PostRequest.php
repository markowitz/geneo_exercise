<?php

namespace App\Dto;

use App\Entity\Post;
use App\Entity\Tag;
use Symfony\Component\Validator\Constraints as Assert;

class PostRequest
{
    public $id;
    /**
     * @Assert\NotBlank
     * @Assert\Length(min=3)
     * @Assert\NotNull
     */
    public $title;

    /**
     *  @Assert\NotBlank
     * @Assert\NotNull
     * @Assert\Length(min=8)
     */
    public $content;

    public $author;

    public $images = [];

    /**
     * @Assert\Type("string")
     */
    public $tags;

    public $slug;

    public $approved;

    public $createdAt;

    public $updatedAt;

    public function postTags($value)
    {
        $names = array_unique(array_filter(array_map('trim', explode(',',$value))));

        $tags = [];

        foreach($names as $name) {
            $tag = new Tag();
            $tag->setName($name);
            $tags[]=$tag;
        }

        return $tags;
    }


}