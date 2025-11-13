<?php

namespace app\modules\api\helpers;

use Yii;
use yii\web\UploadedFile;

class ImageUploadHelper
{
    /**
     * Uploads an image file to the specified path.
     *
     * @param UploadedFile $imageFile The uploaded image file instance.
     * @param string $directory The target directory to save the image.
     * @param string|null $fileName Optional custom file name. Defaults to a unique name.
     * @return string|false The file path of the saved image or false on failure.
     */
    public static function uploadImage(UploadedFile $imageFile, string $directory = '@webroot/uploads', string $fileName = null)
    {
        $directory = Yii::getAlias($directory);

        // Ensure the target directory exists.
        if (!file_exists($directory)) {
            if (!mkdir($directory, 0777, true) && !is_dir($directory)) {
                Yii::error("Failed to create directory: $directory", __METHOD__);
                return false;
            }
        }

        // Generate a unique file name if not provided.
        if ($fileName === null) {
            $fileName = Yii::$app->security->generateRandomString() . '.' . $imageFile->extension;
        }

        // Set the full file path.
        $filePath = $directory . DIRECTORY_SEPARATOR . $fileName;

        // Save the file.
        if ($imageFile->saveAs($filePath)) {
            // Return the web-accessible path.
            return Yii::getAlias('@web/uploads/' . $fileName);
        } else {
            Yii::error("Failed to save file: $filePath", __METHOD__);
            return false;
        }
    }


    /**
     * Validates an uploaded file to ensure it is an image.
     *
     * @param UploadedFile|null $imageFile The uploaded file instance.
     * @return bool True if the file is a valid image, false otherwise.
     */
    public static function validateImage($imageFile)
    {
        // Return true if no image file is provided (optional image)
        if ($imageFile === null) {
            return true;
        }

        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];

        if (!in_array(strtolower($imageFile->extension), $allowedExtensions, true)) {
            return false;
        }

        if ($imageFile->size > 5 * 1024 * 1024) { // 5 MB size limit
            return false;
        }

        return true;
    }
}
