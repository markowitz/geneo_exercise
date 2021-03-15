<?php

namespace App\Dto\Transformer;

use App\Dto\PostRequest;
use App\Entity\Post;

class PostTransformer extends AbstractTransformer
{

    /**
     * @param Post $post
     *
     * @return PostRequest
     */
    public function transformFromObject($post)
    {
        $dto = new PostRequest();

        $userTransformer    = new UserTransformer();
        $imageTransformer   = new ImageTransformer();
        $tagTransformer     = new TagTransformer();

        $dto->title     = $post->getTitle();
        $dto->content   = $post->getContent();
        $dto->author    = $userTransformer->transformFromObject($post->getAuthor());
        $dto->images    = $imageTransformer->transformFromObject($post->getImages());
        $dto->tags      = $tagTransformer->transformFromObjects($post->getTags());
        $dto->createdAt = $post->getCreatedAt();
        $dto->updatedAt = $post->getUpdatedAt();

        return $dto;
    }

}