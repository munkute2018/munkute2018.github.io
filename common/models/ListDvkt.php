<?php
namespace common\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use common\models\HisWork;
use common\models\ThuVien;
class ListDvkt extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%listdvkt}}';
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
            [['ten_dichvu', 'madmbyt', 'nhomdichvuid', 'manhomdichvu', 'nhom_mabhyt_id', 'khoanmucid', 'nhomketoanid', 'donvi', 'giadichvu', 'gianuocngoai', 'quyetdinh', 'ngaycongbo', 'sttmau21', 'mabytmau21', 'loaipttt', 'matt37', 'matt4350', 'chuyenkhoaid', 'tendichvubhyt', 'khoa', 'ghichu'], 'string', 'max' => 255],
            [['ma_dichvu'], 'string', 'max' => 15],
            [['id_donvi'], 'string', 'min' => 5, 'max' => 5],
            [['ten_dichvu', 'ma_dichvu'], 'trim'],
            [['ten_dichvu', 'id_phieu', 'id_donvi', 'id_dichvu', 'gia_bhyt_old', 'gia_vp_old'], 'required'],
            [['gia_bhyt_old', 'gia_vp_old', 'id_dichvu'], 'integer', 'min' => 0, 'max' => 999999999],
            ['id_donvi', 'validateDonVi'],
            [['id_phieu', 'id_donvi', 'id_dichvu'], 'validateTrung'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id_donvi' =>"Mã đơn vị",
            'id_dichvu' =>"Mã dịch vụ",
            'ma_dichvu' =>"Mã báo cáo dịch vụ",
            'ten_dichvu' =>"Tên dịch vụ",
            'gia_bhyt_old' =>"Giá BHYT hiện tại",
            'gia_vp_old' =>"Giá viện phí hiện tại",
            'gia_bhyt_new' =>"Giá BHYT mới",
            'gia_vp_new' =>"Giá viện phí mới",
            'madmbyt' =>"Mã danh mục BYT",
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

    public function validateTrung($attribute, $params)
    {
        if($this->id == null){
            $info = $this::find()->where(['id_phieu' => $this->id_phieu, 'id_donvi' => $this->id_donvi, 'id_dichvu' => $this->id_dichvu])->exists();
        }
        else{
            $info = $this::find()->where(['id_phieu' => $this->id_phieu, 'id_donvi' => $this->id_donvi, 'id_dichvu' => $this->id_dichvu])->andWhere(['<>','id',$this->id])->exists();
        }
        if($info){
            $this->addError($attribute, 'Trùng dữ liệu');
        }
    }

    public function saveThongTinGia()
    {
        if (!$this->hasErrors()) {
            $info_phieu = HisWork::findOne($this->id_phieu);
            if($info_phieu && $info_phieu->status == 0){
                $bhytnew = ThuVien::covertDonGia($info_phieu->bhyt_new, $info_phieu->bhyt_old, $this->id_donvi, $this->ma_dichvu, $this->ten_dichvu, $this->gia_bhyt_old);
                $this->gia_bhyt_new = $bhytnew["dongia"];
                $this->tooltip_bhyt = $bhytnew["tooltip"];

                $vpnew = ThuVien::covertDonGia($info_phieu->vp_new, $info_phieu->vp_old, $this->id_donvi, $this->ma_dichvu, $this->ten_dichvu, $this->gia_vp_old);
                $this->gia_vp_new = $vpnew["dongia"];
                $this->tooltip_vp = $vpnew["tooltip"];

                $this->status = ThuVien::setStatusListDvkt($this->tooltip_bhyt, $this->tooltip_vp);

                if($this->save()){
                    return true;
                }
                else{
                    return false;
                }
            }
            else{
                return false;
            }
        }
    }
    
    public function getHiswork()
    {
        return $this->hasOne(HisWork::className(), ['id' => 'id_phieu']);
    }
}