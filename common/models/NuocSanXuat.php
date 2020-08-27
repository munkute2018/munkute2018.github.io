<?php
namespace common\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
class NuocSanXuat extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%nuocsanxuat}}';
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
            [['id', 'ten_nsx', 'status'], 'required'],
            [['ten_nsx'], 'trim'],
            [['ten_nsx'], 'string', 'max' => 255],
            [['id'], 'integer', 'min' => 0],
            [['status'], 'in', 'range' => [0, 1]],
            [['id'],'unique','message'=>'Mã này đã tồn tại.'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' =>"Mã nước sản xuất",
            'ten_nsx' =>"Tên nước sản xuất",
            'status' =>"Trạng thái",
        ];
    }
}