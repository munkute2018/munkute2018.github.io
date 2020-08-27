<?php
namespace common\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
class HisWork extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%hiswork}}';
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
            [['bhyt_new', 'vp_new', 'phienban'], 'required'],
            [['bhyt_new', 'vp_new', 'bhyt_old', 'vp_old'], 'integer', 'min' => 0],
            [['ghichu'], 'string', 'max' => 255],
            [['ghichu'], 'trim'],
            [['phienban'], 'in', 'range' => [0, 1]],
            ['bhyt_new', 'validateBhytnew'],
            ['vp_new', 'validateVpnew'],
            ['bhyt_old', 'validateBhytold'],
            ['vp_old', 'validateVpold'],
            ['bhyt_new', 'validateChangeAll'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'Mã phiếu',
            'bhyt_new' => 'Thông tư giá BHYT mới',
            'bhyt_old' =>'Thông tư giá BHYT cũ',
            'vp_new' =>'Thông tư giá VP mới',
            'vp_old' =>'Thông tư giá VP cũ',
            'phienban' => 'Phần mềm HIS',
            'ghichu' => 'Ghi chú'
        ];
    }

    public function validateBhytnew($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $info = Dmthongtuhis::find()->where(['status' => 1, 'id' => $this->bhyt_new])->andWhere(['OR', ['bhxh' => 1], ['bhxh' => 3]])->exists();
            if (!$info && $this->bhyt_new != 0) {
                $this->addError($attribute, 'Thông tư giá BHYT mới không tồn tại hoặc không còn được sử dụng.');
            }
        }
    }

    public function validateBhytold($attribute, $params)
    {
        if (!$this->hasErrors()) {
            if($this->bhyt_old != ''){
                if($this->bhyt_old == $this->bhyt_new){
                    $this->addError($attribute, 'Thông tư giá BHYT cũ không được trùng thông thư BHYT mới.');
                }
                else if(!Dmthongtuhis::find()->where(['status' => 1, 'id' => $this->bhyt_old])->andWhere(['OR', ['bhxh' => 1], ['bhxh' => 3]])->exists()){
                    $this->addError($attribute, 'Thông tư giá BHYT cũ không tồn tại hoặc không còn được sử dụng.');
                }
            }
        }
    }

    public function validateVpnew($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $info = Dmthongtuhis::find()->where(['status' => 1, 'id' => $this->vp_new])->andWhere(['OR', ['bhxh' => 2], ['bhxh' => 3]])->exists();
            if (!$info && $this->vp_new != 0) {
                $this->addError($attribute, 'Thông tư giá VP mới không tồn tại hoặc không còn được sử dụng.');
            }
        }
    }

    public function validateVpold($attribute, $params)
    {
        if (!$this->hasErrors()) {
            if($this->vp_old != ''){
                if($this->vp_old == $this->vp_new){
                    $this->addError($attribute, 'Thông tư giá VP cũ không được trùng thông thư VP mới.');
                }
                else if(!Dmthongtuhis::find()->where(['status' => 1, 'id' => $this->vp_old])->andWhere(['OR', ['bhxh' => 2], ['bhxh' => 3]])->exists()){
                    $this->addError($attribute, 'Thông tư giá VP cũ không tồn tại hoặc không còn được sử dụng.');
                }
            }
        }
    }

    public function validateChangeAll($attribute, $params)
    {
        if (!$this->hasErrors()) {
            if($this->bhyt_new == 0 && $this->vp_new == 0){
                $this->addError($attribute, 'Vui lòng chọn thông tư BHYT hoặc VP cần thay đổi!!!!.');
            }
        }
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'id_user']);
    }
}