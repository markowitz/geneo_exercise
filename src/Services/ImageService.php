<?php

namespace App\Services;

use App\Validation\ImageValidator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpFoundation\File\File as FileObject;
use Gedmo\Sluggable\Util\Urlizer;

class ImageService
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
    public function handleUpload($image)
    {
        if (is_string($image)) {
            $uploadedFile = $this->uploadBase64($image);

        } else {

            $uploadedFile =  $this->uploadImage($image);

        }

        return $uploadedFile;
    }


    protected function uploadBase64($images)
    {
        $extractString = explode(";base64,", $images);

        $images        = isset($extractString[1]) ? $extractString[1] : $images;

        $checkBase64   = $this->checkBase64($images);

        if (!$checkBase64) {
            throw new HttpException(Response::HTTP_BAD_REQUEST, 'invalid file input');
        }

        return $this->convertToBase64($images);
    }

    /**
     * @param string $image
     * @return array $filename;
     */
    protected function convertToBase64($image)
    {
        $uploadedFile = base64_decode($image);
        $uploadedFile = $this->storeTempImage($uploadedFile);

        $volations = $this->imageValidator->validate($uploadedFile);

        if (count($volations) > 0) {
            throw new HttpException(Response::HTTP_BAD_REQUEST, $volations[0]->getMessage());
        }

        return $this->uploadImage($uploadedFile, true);

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
     * @param SplFileInfo $uploadedFile
     * @return array $image
     */
    protected function uploadImage($uploadedFile, $fromBase64 = false)
    {
        $destination = $this->uploadsPath.'/images';

        $originalFilename = pathinfo($fromBase64 ? $uploadedFile->getFilename() : $uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);

        $newFilename = Urlizer::urlize($originalFilename).'-'.uniqid().'.'.$uploadedFile->guessExtension();

        $uploadedFile->move(
            $destination,
            $newFilename
        );

        return [
            'file_name' => $newFilename,
            'file_path' => "$destination/$newFilename"
        ];
    }

    /**
     * @param string $str
     * @return bool
     */
    protected function checkBase64($str)
    {
        if (base64_encode(base64_decode($str, true)) === $str){
            return true;
         }

         return false;

    }
}
