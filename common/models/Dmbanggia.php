<?php
namespace common\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
class Dmbanggia extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%dmbanggia}}';
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
            [['stt', 'name', 'dongia', 'type', 'status'], 'required'],
            [['name', 'ghichu'], 'string', 'max' => 255],
            [['name', 'ghichu'], 'trim'],
            [['dongia'], 'integer', 'min' => 0, 'max' => 999999999],
            [['type'], 'in', 'range' => [1, 2, 3]],
            [['stt'], 'integer', 'min' => 1, 'max' => 9999],
            [['status'], 'in', 'range' => [0, 1]],
            ['stt', 'validateTrung'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'stt' =>"Mã TT37",
            'name' =>"Nội dung",
            'ghichu' =>"Ghi chú",
            'type' =>"Loại",
            'dongia' =>"Đơn giá",
            'status' =>"Trạng thái hoạt động",
        ];
    }

    public function validateTrung($attribute, $params)
    {
        if (!$this->hasErrors()) {
            if($this->id == null){
            $info = $this::find()->where(['stt' => $this->stt, 'ghichu' => $this->ghichu, 'id_thongtu' => $this->id_thongtu])->exists();
            }
            else{
                $info = $this::find()->where(['stt' => $this->stt, 'ghichu' => $this->ghichu, 'id_thongtu' => $this->id_thongtu])->andWhere(['<>','id',$this->id])->exists();
            }
            if($info){
                $this->addError($attribute, 'Không thể tồn tại 2 dòng dữ liệu có cùng mã TT37 và ghi chú.');
            }
        }
    }
    
    public function getDmthongtuhis()
    {
        return $this->hasOne(Dmthongtuhis::className(), ['id' => 'id_thongtu']);
    }
}