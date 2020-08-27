<?php
namespace common\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
class DuongDung extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%duongdung}}';
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
            [['id', 'mota', 'status'], 'required'],
            [['mota', 'id'], 'trim'],
            [['id'], 'string', 'min' => 4, 'max' => 4],
            [['mota'], 'string', 'max' => 255],
            [['status'], 'in', 'range' => [0, 1]],
            [['id'],'unique','message'=>'Mã này đã tồn tại.'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' =>"Mã đường dùng",
            'mota' =>"Tên đường dùng",
            'status' =>"Trạng thái",
        ];
    }
}