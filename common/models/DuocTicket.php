<?php
namespace common\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
class DuocTicket extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%duoc_ticket}}';
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
            [['phienban'], 'required'],
            [['ghichu'], 'string', 'max' => 255],
            [['ghichu'], 'trim'],
            [['phienban', 'saochep'], 'in', 'range' => [0, 1]],
            [['id_donvi'], 'string', 'min' => 5, 'max' => 5],
            ['id_donvi', 'validateDonVi'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'phienban' => 'Phần mềm HIS',
            'ghichu' => 'Ghi chú',
            'id' => 'Mã',
            'id_donvi' => 'DVTT',
            'saochep' => 'Sao chép'
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

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'id_user']);
    }

    public function getDonvi()
    {
        return $this->hasOne(DonVi::className(), ['madonvi' => 'id_donvi']);
    }
}