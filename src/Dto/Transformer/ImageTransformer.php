<?php

namespace App\Dto\Transformer;

use App\Dto\ImageRequest;

class ImageTransformer extends AbstractTransformer
{
    /**
     * @param User $user
     * @return array | null
     */
    public function transformFromObject($images)
    {
        $dtoImages = [];

        if (count($images)) {
            foreach($images as $image) {
                $dto = new ImageRequest();
                $dto->file_name = $image->getFileName();
                $dto->file_path = $image->getFilePath();
                $dtoImages[] = $dto;
            }
        }

        return $dtoImages;

    }
}