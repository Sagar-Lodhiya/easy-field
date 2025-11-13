<?php

namespace app\controllers;

use Imagine\Image\Box;
use yii\helpers\BaseUrl;
use yii\imagine\Image;
use yii\web\UploadedFile;

class UploadController extends \yii\web\Controller {

    public function actionCommon($attribute) {
        $imageFile = UploadedFile::getInstanceByName($attribute);
        $directory = \Yii::getAlias('@app/web/uploads') . DIRECTORY_SEPARATOR;
        
        if ($imageFile) {
            $filetype = mime_content_type($imageFile->tempName);
            $allowedImages = ['image/png', 'image/jpeg', 'image/gif'];
            $allowedFiles = array_merge($allowedImages, ['video/mp4', 'application/pdf']);
            
            if (!in_array(strtolower($filetype), $allowedFiles)) {
                return json_encode(['files' => [
                    'error' => "File type not supported",
                ]]);
            }
            
            $uid = uniqid(time(), true);
            $fileName = $uid . '.' . $imageFile->extension;
            $filePath = $directory . $fileName;
    
            if ($imageFile->saveAs($filePath)) {
                // Process images only
                if (in_array($filetype, $allowedImages)) {
                    Image::getImagine()->open($filePath)->thumbnail(new Box(500, 500))->save($filePath, ['quality' => 100]);
                }
                
                $path = BaseUrl::home() . 'uploads/' . $fileName;
    
                return json_encode([
                    'files' => [
                        'name' => $fileName,
                        'size' => $imageFile->size,
                        "url" => $path,
                        "thumbnailUrl" => $path,
                        "deleteUrl" => 'image-delete?name=' . $fileName,
                        "deleteType" => "POST",
                        'error' => ""
                    ]
                ]);
            }
        }
        return '';
    }
    

}
