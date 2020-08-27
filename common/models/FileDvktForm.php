<?php
namespace common\models;

use Yii;
use yii\base\Model;

/**
 * Login form
 */
class FileDvktForm extends Model
{
    public $file;
    public $dvtt;
    public $madv;
    public $mabc;
    public $tendv;
    public $giabhyt;
    public $giavp;
    public $dvttl2;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['file'], 'file', 'skipOnEmpty' => false],
            [['file'], 'file', 'extensions' => ['xls', 'xlsx', 'csv'], 'checkExtensionByMimeType' => false],
            [['dvtt', 'madv', 'mabc', 'tendv', 'giabhyt', 'giavp', 'dvttl2'], 'trim'],
            [['dvtt', 'madv', 'mabc', 'tendv', 'giabhyt', 'giavp'], 'string', 'max' => 2],
            [['dvtt', 'madv', 'mabc', 'tendv', 'giabhyt', 'giavp'], 'match', 'pattern' => '/^[A-Z]+$/'],
            [['dvttl2'], 'string', 'min' => 5, 'max' => 5],
        ];
    }

    public function attributeLabels()
    {
        return [
            'file' =>"File",
            'dvtt' =>"Vị trí cột mã đơn vị",
            'madv' =>"Vị trí cột mã dịch vụ",
            'mabc' =>"Vị trí cột mã báo cáo",
            'tendv' =>"Vị trí cột tên đơn vị",
            'giabhyt' =>"Vị trí cột giá bảo hiểm",
            'giavp' =>"Vị trí cột giá viện phí",
            'dvttl2' =>"Mã đơn vị",
        ];
    }
}
