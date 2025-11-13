<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * FileUploadForm is the model behind the file upload form.
 *
 */
class FileUploadForm extends Model
{
    public $file;

    
    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // file is required
            [['file'], 'required'],
        ];
    }
}
