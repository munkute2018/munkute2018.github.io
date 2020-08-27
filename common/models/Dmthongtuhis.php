<?php
namespace common\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
class Dmthongtuhis extends ActiveRecord
{
    public $posted;
    public static function tableName()
    {
        return '{{%dmthongtuhis}}';
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
            [['name', 'bhxh', 'status', 'posted'], 'required'],
            [['name'], 'trim'],
            [['name'], 'string', 'max' => 255],
            [['bhxh'], 'in', 'range' => [1, 2, 3]],
            [['status'], 'in', 'range' => [0, 1]],
            ['posted', 'date', 'format' => 'dd/MM/yyyy'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => "Mã quyết định",
            'bhxh' =>"Loại hình áp dụng",
            'name' =>"Tiêu đề quyết định (thông tư)",
            'status' =>"Trạng thái hoạt động",
            'posted' =>"Ngày áp dụng",
        ];
    }

    public function saveThongTu()
    {
        $this->posted_at = strtotime(str_replace("/", "-", $this->posted));
        if($this->save()){
            return true;
        }
        else{
            return false;
        }
    }
}