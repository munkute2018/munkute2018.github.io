<?php
namespace common\models;

use Yii;
use yii\base\Model;

/**
 * Login form
 */
class FileDuocForm extends Model
{
    public $file;
    public $thanhtoan;
    public $phanloai;
    public $madv;
    public $mabc;
    public $tenvt;
    public $hoatchat;
    public $hamluong;
    public $duongdung;
    public $donvitinh;
    public $dongia;
    public $quycach;
    public $sodangky;
    public $nhasx;
    public $nuocsx;
    public $nhathau;
    public $quyetdinh;
    public $congbo;
    public $loaithuoc;
    public $goithau;
    public $nhomthau;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['file'], 'file', 'skipOnEmpty' => false],
            [['file'], 'file', 'extensions' => ['xls', 'xlsx', 'csv'], 'checkExtensionByMimeType' => false],
            [['thanhtoan'], 'in', 'range' => [0, 1]],
            [['phanloai'], 'in', 'range' => [1, 2, 3, 4, 5]],
            [['mabc', 'tenvt', 'hoatchat', 'hamluong', 'duongdung', 'donvitinh', 'dongia', 'quycach', 'sodangky', 'nhasx', 'nuocsx', 'nhathau', 'quyetdinh', 'congbo', 'loaithuoc', 'goithau', 'nhomthau'], 'trim'],
            [['mabc', 'tenvt', 'hoatchat', 'hamluong', 'duongdung', 'donvitinh', 'dongia', 'quycach', 'sodangky', 'nhasx', 'nuocsx', 'nhathau', 'quyetdinh', 'congbo', 'loaithuoc', 'goithau', 'nhomthau'], 'string', 'max' => 2],
            [['mabc', 'tenvt', 'hoatchat', 'hamluong', 'duongdung', 'donvitinh', 'dongia', 'quycach', 'sodangky', 'nhasx', 'nuocsx', 'nhathau', 'quyetdinh', 'congbo', 'loaithuoc', 'goithau', 'nhomthau'], 'match', 'pattern' => '/^[A-Z]+$/'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'file' => "File",
            'thanhtoan' => "Loại thanh toán",
            'phanloai' => "Phân loại",
            'mabc' => "Cột mã báo cáo",
            'tenvt' =>"Cột tên vật tư",
            'hoatchat' =>"Cột hoạt chất",
            'hamluong' =>"Cột hàm lượng",
            'duongdung' =>"Cột đường dùng",
            'donvitinh' =>"Cột ĐVT",
            'dongia' =>"Cột đơn giá",
            'quycach' =>"Cột quy cách",
            'sodangky' =>"Cột SĐK",
            'nhasx' =>"Cột nhà sản xuất",
            'nuocsx' =>"Cột nước sản xuất",
            'nhathau' =>"Cột nhà thầu",
            'quyetdinh' =>"Cột quyết định",
            'congbo' =>"Cột công bố",
            'loaithuoc' =>"Cột loại thuốc",
            'goithau' =>"Cột gói thầu",
            'nhomthau' =>"Cột nhóm thầu",
        ];
    }
}
