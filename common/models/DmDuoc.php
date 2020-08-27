<?php
namespace common\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use common\models\DuocTicket;
use common\models\ThuVien;
class DmDuoc extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%dmduoc}}';
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
            [['hoatchat_ax', 'hamluong_ax', 'ten_ax', 'quycach', 'nhasanxuat', 'nuocsanxuat', 'nhathau', 'sodangky_ax'], 'string', 'max' => 255],
            [['ma_ax', 'goithau', 'nhomthau', 'donvitinh', 'mavt', 'manhom', 'quyetdinh'], 'string', 'max' => 50],
            [['ma_duongdung_ax'], 'string', 'max' => 4],
            [['congbo'], 'string', 'max' => 8],
            [['quyetdinh', 'donvitinh', 'goithau', 'nhomthau', 'congbo', 'ma_duongdung_ax', 'ma_ax', 'sodangky_ax', 'hoatchat_ax', 'hamluong_ax', 'ten_ax', 'quycach', 'nhasanxuat', 'nuocsanxuat', 'nhathau', 'mavt', 'manhom'], 'trim'],
            [['ten_ax', 'id_phieu', 'donvitinh', 'dongia', 'loaithuoc'], 'required'],
            [['dongia'], 'number', 'min' => 0, 'max' => 999999999],
            [['tyle'], 'integer', 'min' => 0, 'max' => 100],
            [['loaithuoc'], 'in', 'range' => [1,2,3,4,5]],
            ['mavt', 'validateMaVT'],
            ['manhom', 'validateMaNhom'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' =>"Mã",
            'mavt' =>"Mã VT",
            'manhom' =>"Mã nhóm",
            'id_phieu' =>"Mã phiếu",
            'ma_ax' =>"Mã BC",
            'hoatchat_ax' =>"Hoạt chất",
            'ma_duongdung_ax' =>"Mã đường dùng",
            'hamluong_ax' =>"Hàm lượng",
            'ten_ax' =>"Tên",
            'sodangky_ax' =>"SĐK",
            'quycach' =>"Quy cách",
            'donvitinh' =>"Đơn vị tính",
            'dongia' =>"Đơn giá",
            'nhasanxuat' =>"Nhà sản xuất",
            'nuocsanxuat' =>"Nước sản xuất",
            'nhathau' =>"Nhà thầu",
            'quyetdinh' =>"Quyết định",
            'congbo' =>"Công bố",
            'loaithuoc' =>"Loại thuốc",
            'goithau' =>"Gói thầu",
            'nhomthau' =>"Nhóm thầu",
            'tyle' =>"Tỷ lệ",
        ];
    }

    public function validateMaVT($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $info = $this::find()->where(['mavt' => $this->mavt])->andWhere(['<>','id',$this->id])->exists();
            if($info) {
                $this->addError($attribute, 'Đã tồn tại mã vật tư này!');
            }
            $lst = $this::findOne($this->id);
            if($this->mavt != '' && !$lst->duocticket->phienban){
                if(!ctype_digit($this->mavt))    {
                    $this->addError($attribute, 'Mã vật tư phải là số nguyên!');
                }
                else{
                    $this->mavt = (string)(int)$this->mavt; 
                }
            }
        }
    }

    public function validateMaNhom($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $lst = $this::findOne($this->id);
            if($this->manhom != '' && !$lst->duocticket->phienban){
                if(!ctype_digit($this->manhom))    {
                    $this->addError($attribute, 'Mã nhóm phải là số nguyên!');
                }
                else{
                    $this->manhom = (string)(int)$this->manhom; 
                }
            }
        }
    }

    public function validateThuocBHYT(){
        if($this->ma_ax == '' && $this->ma_ax != '40.17')
            $this->addError('ma_ax', 'Mã báo cáo không được để trống');
        else if($this->hoatchat_ax == '')
            $this->addError('hoatchat_ax', 'Hoạt chất không được để trống');
        $info_dd = DuongDung::findOne($this->ma_duongdung_ax);
        if(!$info_dd)
            $this->addError('ma_duongdung_ax', 'Mã đường dùng không được để trống');
        if($this->sodangky_ax == '' && $this->ma_ax != '40.17')
            $this->addError('sodangky_ax', 'Số đăng ký không được để trống');
        if($this->quyetdinh == '' && $this->ma_ax != '40.17')
            $this->addError('quyetdinh', 'Số quyết định không được để trống');
        if($this->congbo == '' && $this->ma_ax != '40.17')
            $this->addError('congbo', 'Ngày công bố không được để trống');
        if($this->loaithuoc > 3)
            $this->addError('congbo', 'Thông tin loại thuốc không hợp lệ');

        if (!$this->hasErrors())
            return true;
    }

    public function validateVTBHYT(){
        if($this->ma_ax == '')
            $this->addError('ma_ax', 'Mã báo cáo không được để trống');
        if($this->quyetdinh == '')
            $this->addError('quyetdinh', 'Số quyết định không được để trống');
        if($this->congbo == '')
            $this->addError('congbo', 'Ngày công bố không được để trống');
        if (!$this->hasErrors())
            return true;
    }

    public function getDuocticket()
    {
        return $this->hasOne(DuocTicket::className(), ['id' => 'id_phieu']);
    }
    
    public function getDuongdung()
    {
        return $this->hasOne(DuongDung::className(), ['id' => 'ma_duongdung_ax']);
    }
}