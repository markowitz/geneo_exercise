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

    /**
     * @Assert\Type(type={"array", "string"})
     */
    public $images = [];

    /**
     * @Assert\Type(type={"array", "string"})
     */
    public $tags;

    /**
     * @Assert\Type("string")
     */
    public $slug;

    /**
     * @Assert\Choice(0, 1)
     */
    public $approved;

    public $comments;

    public $createdAt;

    public $updatedAt;

    /**
     * persist Tags
     * @param array | string $value
     * @return Array $tags
     */
    public function postTags($value)
    {

        if (!is_array($value)) {
            $names = array_unique(array_filter(array_map('trim', explode(',',$value))));
        } else {
            $names = $value;
        }

        $tags = [];

        foreach($names as $name) {
            $tag = new Tag();
            $tag->setName($name);
            $tags[] = $tag;
        }

        return $tags;
    }


}
