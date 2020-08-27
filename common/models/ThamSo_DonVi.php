<?php
namespace common\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
class ThamSo_DonVi extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%thamso_donvi}}';
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
            [['id_thamso', 'giatri'], 'string', 'max' => 255],
            [['id_donvi'], 'string', 'min' => 5, 'max' => 5],
            [['id_thamso', 'id_donvi', 'giatri'], 'trim'],
            [['id_thamso', 'id_donvi'], 'required'],
            ['id_donvi', 'validateDonVi'],
            ['id_thamso', 'validateThamSo'],
            [['id_donvi', 'id_thamso'], 'validateTrung'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id_donvi' =>"Mã đơn vị",
            'id_thamso' =>"Mã tham số cấu hình",
            'giatri' =>"Giá trị",
        ];
    }

    public function validateDonVi($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $info_dv = DonVi::findOne($this->id_donvi);
            if(!$info_dv) {
                $this->addError($attribute, 'Mã đơn vị không tồn tại');
            }
        }
    }

    public function validateThamSo($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $info_dv = Dmthamso::findOne($this->id_thamso);
            if(!$info_dv) {
                $this->addError($attribute, 'Tham số không tồn tại');
            }
        }
    }

    public function validateTrung($attribute, $params)
    {
        if($this->id == null){
            $info = $this::find()->where(['id_thamso' => $this->id_thamso, 'id_donvi' => $this->id_donvi])->exists();
        }
        else{
            $info = $this::find()->where(['id_thamso' => $this->id_thamso, 'id_donvi' => $this->id_donvi])->andWhere(['<>','id',$this->id])->exists();
        }
        if($info){
            $this->addError($attribute, 'Trùng dữ liệu');
        }
    }

    public function getDmthamso()
    {
        return $this->hasOne(Dmthamso::className(), ['id' => 'id_thamso']);
    }

    public function getDonvi()
    {
        return $this->hasOne(DonVi::className(), ['id' => 'id_donvi']);
    }
}