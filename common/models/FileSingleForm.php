<?php
namespace common\models;

use Yii;
use yii\base\Model;

/**
 * Login form
 */
class FileSingleForm extends Model
{
    public $file;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['file'], 'file', 'skipOnEmpty' => false],
            [['file'], 'file', 'extensions' => ['xls', 'xlsx', 'csv'], 'checkExtensionByMimeType' => false],
        ];
    }

    public function attributeLabels()
    {
        return [
            'file' =>"File",
        ];
    }
}
