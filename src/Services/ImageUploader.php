<?php

namespace App\Services;

use App\Validation\ImageValidator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpFoundation\File\File as FileObject;
use Gedmo\Sluggable\Util\Urlizer;

class ImageUploader
{
    /**
     * @var ImageValidator
     */
    private $imageValidator;

    /**
     * @var string
     */
    private $uploadsPath;

    public function __construct(ImageValidator $imageValidator, string $uploadsPath)
    {
        $this->imageValidator = $imageValidator;
        $this->uploadsPath = $uploadsPath;
    }

    /**
     * @param {string | UploadedFile} $images
     *
     */
    public function handleUpload($images)
    {

        $filePaths = $this->UploadImages($images);

        return $filePaths;
    }


    protected function checkValidBase64($images)
    {
        $extractString = explode(";base64,", $images);

        $images = isset($extractString[1]) ? $extractString[1] : $images;

        if (base64_encode(base64_decode($images, true)) !== $images){

            throw new HttpException(Response::HTTP_BAD_REQUEST, 'invalid file input');

         }

        return $images;
    }

    /**
     * convert base64 to UploadFile type
     * @param string $image
     * @return array $filename;
     */
    protected function UploadImages($images)
    {
        $temp = [];

        if (is_string($images)) {

            $images = $this->checkValidBase64($images);
            $uploadedFile = base64_decode($images);
            $temp[] = $this->storeTempImage($uploadedFile);

        } else {

            foreach($images as $image) {
                $image = $this->checkValidBase64($image);
                $uploadedFile = base64_decode($image);
                $temp[] = $this->storeTempImage($uploadedFile);
            }
        }

        $images = $this->validateImages($temp);

        return $this->uploadImage($images);

    }

    /**
     * validate images before saving
     * @param array $images
     */
    public function validateImages($images)
    {

        foreach($images as $image) {

            $errors = $this->imageValidator->validate($image);

            if (count($errors) > 0) {
                throw new HttpException(Response::HTTP_BAD_REQUEST, $errors[0]->getMessage());
            }

        }

        return $images;

    }

    /**
     * @param $decodedImage
     * @return FileObject
     */
    protected function storeTempImage($decodedImage)
    {
        $tmpPath = sys_get_temp_dir().'/sf_upload'.uniqid();

        file_put_contents($tmpPath, $decodedImage);
        return new FileObject($tmpPath);
    }

    /**
     * @param array $images
     * @return array $image
     */
    protected function uploadImage($images)
    {
        $imagePath = [];

        foreach ($images as $image) {
            $destination = $this->uploadsPath.'/images';

            $originalFilename = pathinfo($image->getFilename(), PATHINFO_FILENAME);

            $newFilename = Urlizer::urlize($originalFilename).'-'.uniqid().'.'.$image->guessExtension();

            $image->move(
                $destination,
                $newFilename
            );

            $imagePath[] = [
                            'file_name' => $newFilename,
                            'file_path' => "$destination/$newFilename"
                        ];
        }

        return $imagePath;

    }

}
