<?php

namespace App\Dto\Transformer;

use App\Dto\Response\TagResponse;

class TagTransformer extends AbstractTransformer
{
    /**
     * @param PostComment $postComment
     *
     * @return PostCommentDto
     */
    public function transformFromObject($tag)
    {
        $dto = $tag->getName();

        return $dto;
    }
}