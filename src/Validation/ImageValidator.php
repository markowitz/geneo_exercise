<?php

namespace App\Validation;

use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\ConstraintViolationList;

class ImageValidator
{
    /**
     * @var Validator
     */
    private $validator;


    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    /**
     * validates the image
     * @param UploadedFile $uploadedFile
     * @return ConstraintViolationList $violations
     */
    public function validate($uploadedFile): ConstraintViolationList
    {
        $violations = $this->validator->validate(
            $uploadedFile,
            [
                new File([
                    'maxSize' => '5M',
                    'mimeTypes' => [
                        'image/*',
                    ]
                ])
            ]
        );

        return $violations;
    }
}