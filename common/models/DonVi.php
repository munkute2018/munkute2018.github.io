<?php
namespace common\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
class DonVi extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%dmdonvi}}';
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
            [['madonvi', 'tendonvi', 'tuyen', 'hang', 'id_huyen', 'id_parent', 'status', 'phienban'], 'required'],
            [['tendonvi', 'madonvi', 'id_parent'], 'trim'],
            [['madonvi'], 'string', 'min' => 5, 'max' => 5],
            [['tendonvi'], 'string', 'max' => 255],
            [['hang'], 'in', 'range' => [0, 1, 2, 3, 4, 5]],
            [['tuyen'], 'in', 'range' => [1, 2, 3, 4, 5]],
            [['phienban'], 'in', 'range' => [0, 1, 2]],
            ['id_huyen', 'validateHuyen'],
            ['id_parent', 'validateDonViQuanLy'],
            [['status'], 'in', 'range' => [0, 1]],
            [['phone'], 'match', 'pattern' => '/((\+[0-9]{6})|0)[-]?[0-9]{7}/'],
            [['madonvi'],'unique','message'=>'Mã đơn vị này đã tồn tại.'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'madonvi' =>"Mã đơn vị",
            'tendonvi' =>"Tên đơn vị",
            'tuyen' =>"Tuyến",
            'hang' =>"Hạng",
            'id_parent' =>"Đơn vị quản lý",
            'id_huyen' =>"Huyện",
            'phone' =>"Số điện thoại",
            'phienban' =>"Phiên bản phần mềm",
            'status' =>"Trạng thái",
        ];
    }

    public function validateHuyen($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $info = Huyen::find()->where(['status' => 1, 'id' => $this->id_huyen])->exists();
            if (!$info) {
                $this->addError($attribute, 'Huyện này đã không còn tồn tại.');
            }
        }
    }

    public function validateDonViQuanLy($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $info = DonVi::find()->where(['madonvi' => $this->id_parent])->exists();
            if (!$info && $this->madonvi != $this->id_parent) {
                $this->addError($attribute, 'Đơn vị quản lý không hợp lệ.');
            }
        }
    }

    public function getHuyen() {
        return $this->hasOne(Huyen::className(), ['id' => 'id_huyen']);
    }

    public function getThamso_donvi() {
        return $this->hasMany(ThamSo_DonVi::className(), ['id_donvi' => 'madonvi']);
    }
}