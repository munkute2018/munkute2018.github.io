<?php
namespace common\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
class Huyen extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%huyen}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['huyen', 'status'], 'required'],
            [['huyen'], 'trim'],
            [['huyen'], 'string', 'max' => 100],
            [['status'], 'in', 'range' => [0, 1]],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' =>"Mã huyện",
            'huyen' =>"Tên huyện",
            'status' =>"Trạng thái",
        ];
    }
}