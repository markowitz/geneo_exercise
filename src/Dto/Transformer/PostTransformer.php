<?php

namespace App\Dto\Transformer;

use App\Dto\PostRequest;
use App\Entity\Post;

class PostTransformer extends AbstractTransformer
{

    /**
     * @param Post $post
     *
     * @return OrderResponseDto
     */
    public function transformFromObject($post)
    {
        $dto = new PostRequest();
        $userTransformer = new UserTransformer();
        $imageTransformer = new ImageTransformer();

        $dto->title = $post->getTitle();
        $dto->content = $post->getContent();
        $dto->author = $userTransformer->transformFromObject($post->getAuthor());
        $dto->images = $imageTransformer->transformFromObject($post->getImages());
        $dto->createdAt = $post->getCreatedAt();
        $dto->updatedAt = $post->getUpdatedAt();

        return $dto;
    }
}