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
     * handles the file upload
     * @param string|array $images
     * @return array $filePaths
     */
    public function handleUpload($images)
    {

        $filePaths = $this->UploadImages($images);

        return $filePaths;
    }

    /**
     * check if image is valid base64
     * @param string $image
     * @return $image
     */
    protected function checkValidBase64($image)
    {
        $extractString = explode(";base64,", $image);

        $image = isset($extractString[1]) ? $extractString[1] : $image;

        if (base64_encode(base64_decode($image, true)) !== $image){

            throw new HttpException(Response::HTTP_BAD_REQUEST, 'invalid file input');

         }

        return $image;
    }

    /**
     * convert base64 to UploadFile type
     * @param string $image
     * @return array $filename;
     */
    protected function UploadImages($images)
    {
        $filePaths = [];

        $images = is_string($images) ? [$images] : $images;

        foreach($images as $image) {

            $image = $this->checkValidBase64($image);

            $uploadedFile = base64_decode($image);

            $tempFile = $this->storeTempImage($uploadedFile);

            $errors = $this->imageValidator->validate($tempFile);

            if (count($errors)) {
                $this->unlinkImages($filePaths);

                throw new HttpException(Response::HTTP_UNSUPPORTED_MEDIA_TYPE, $errors[0]->getMessage());
            }

            $filePaths[] = $this->uploadImage($tempFile);

        }

        return $filePaths;

    }

    /**
     * unlink uploaded images due to validation error
     * @param array $images
     */
    public function unlinkImages($filePaths)
    {
        if (!count($filePaths)) {
            return;
        }

        array_walk($filePaths, function($filePath) {
                unlink($filePath['file_path']);
        });

    }

    /**
     * store temp image
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
     * upload image to server
     * @param array $images
     * @return array $image
     */
    protected function uploadImage($image)
    {
        $destination = $this->uploadsPath.'/images';

        $originalFilename = pathinfo($image->getFilename(), PATHINFO_FILENAME);

        $newFilename = Urlizer::urlize($originalFilename).'-'.uniqid().'.'.$image->guessExtension();

        $image->move(
            $destination,
            $newFilename
        );

        return  [
                'file_name' => $newFilename,
                'file_path' => "$destination/$newFilename"
                ];

    }

}
