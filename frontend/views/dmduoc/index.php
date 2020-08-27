<?php
use yii\helpers\Url;
use yii\helpers\Html;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use common\models\ThuVien;
use common\models\DuocTicket;
use common\models\DuongDung;
use kartik\grid\GridView;
use kartik\export\ExportMenu;
use yii\widgets\LinkPager;
use yii\widgets\Pjax;
use kartik\editable\Editable;

$lst_duongdung = DuongDung::find()->where(['status' => 1])->orderBy(['mota' => SORT_ASC])->all();
$arr= ['0.00' => '--Trống--'];
$arr+= ArrayHelper::map($lst_duongdung,'id','mota');
$flag_phienban = ThuVien::checkIsL2ByIDPhieuDuoc($id_phieu);
$phieuduoc = DuocTicket::findOne($id_phieu);
if($flag_phienban){ //Phiên bản L2
    $exportColumns = [
        [
            'attribute' => 'manhom',
            'label' => 'MA_NHOM',
            'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
        ],
        [
            'attribute' => 'ma_ax',
            'label' => 'MA_HOAT_CHAT',
            'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
        ],
        [
            'attribute' => 'hoatchat_ax',
            'label' => 'HOAT_CHAT',
            'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
        ],
        [
            'class'=>'kartik\grid\SerialColumn',
            'header'=>'STT',
            'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
        ],
        [
            'header' => 'MA_ATC',
            'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
            'value' => '',
        ],
        [
            'attribute' => 'ma_ax',
            'label' => 'MA_BYT',
            'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
        ],
        [
            'attribute' => 'sodangky_ax',
            'label' => 'SO_DANG_KY',
            'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
        ],
        [
            'attribute' => 'mavt',
            'label' => 'MA_THUOC',
            'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
        ],
        [
            'attribute' => 'ten_ax',
            'label' => 'TEN_THUOC',
            'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
        ],
        [
            'attribute' => 'hamluong_ax',
            'label' => 'HAM_LUONG',
            'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
        ],
        [
            'attribute' => 'donvitinh',
            'label' => 'DON_VI_TINH',
            'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
        ],
        [
            'attribute' => 'quycach',
            'label' => 'DONG_GOI',
            'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
        ],
        [
            'attribute' => 'ma_duongdung_ax',
            'label' => 'MA_DUONG_DUNG',
            'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
            'value' => function($model) {
                if(!$model['ma_duongdung_ax'] == '0.00') {
                    return '';
                }
                else{
                    return $model['ma_duongdung_ax'];
                }
            },
            ],
            [
                'attribute' => 'ma_duongdung_ax',
                'label' => 'DUONG_DUNG',
                'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
                'value' => function($model) {
                    $info_dd = DuongDung::findOne($model['ma_duongdung_ax']);
                    if($info_dd)
                        return $info_dd->mota;
                    else
                        return '';
                },
            ],
        [
            'attribute' => 'hamluong_ax',
            'label' => 'LIEU_LUONG',
            'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
        ],
        [
            'attribute' => 'nhasanxuat',
            'label' => 'HANG_SX',
            'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
            'value' => function($model) {
                if($model['nhasanxuat'] == ''){
                    return ThuVien::getInfoThamSo('HIS_NHASANXUAT_DEFAULT', 'Không rõ');
                }
                else{
                    return $model['nhasanxuat'];
                }
            },
        ],
        [
            'attribute' => 'nuocsanxuat',
            'label' => 'NUOC_SX',
            'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
            'value' => function($model) {
                if($model['nuocsanxuat'] == ''){
                    return ThuVien::getInfoThamSo('HIS_NUOCSANXUAT_DEFAULT', 'Không rõ');
                }
                else{
                    return $model['nuocsanxuat'];
                }
            },
        ],
        [
            'attribute' => 'nhathau',
            'label' => 'NHA_CUNG_CAP',
            'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
            'value' => function($model) {
                if($model['nhathau'] == ''){
                    return ThuVien::getInfoThamSo('HIS_NHATHAU_DEFAULT', 'Không rõ');
                }
                else{
                    return $model['nhathau'];
                }
            },
        ],
        [
            'attribute' => 'nhomthau',
            'label' => 'MA_NHOM_THAU',
            'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
            'value' => function($model) {
                if($model['tyle']>0){
                    switch (preg_replace('/\s+/', '', strtolower($model['nhomthau']))) {
                        case 'n1':
                            return 'N1';
                            break;
                        
                        case 'nhóm1':
                            return 'N1';
                            break;

                        case 'n2':
                            return 'N2';
                            break;

                        case 'nhóm2':
                            return 'N2';
                            break;

                        case 'n3':
                            return 'N3';
                            break;

                        case 'nhóm3':
                            return 'N3';
                            break;

                        case 'n4':
                            return 'N4';
                            break;

                        case 'nhóm4':
                            return 'N4';
                            break;

                        case 'n5':
                            return 'N5';
                            break;

                        case 'nhóm5':
                            return 'N5';
                            break;

                        default:
                            return ThuVien::getInfoThamSo('HIS_NHOMTHAU_DEFAULT', 'N1');
                            break;
                    }
                }
                else{
                    return '';
                }
            },
        ],
        [
            'attribute' => 'goithau',
            'label' => 'GOI_THAU',
            'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
            'value' => function($model) {
                if($model['tyle']>0){
                    switch (preg_replace('/\s+/', '', strtolower($model['goithau']))) {
                        case 'g1':
                            return 'G1';
                            break;

                        case 'g2':
                            return 'G2';
                            break;

                        case 'g3':
                            return 'G3';
                            break;

                        case 'g4':
                            return 'G4';
                            break;

                        case 'g5':
                            return 'G5';
                            break;

                        default:
                            return ThuVien::getInfoThamSo('HIS_GOITHAU_DEFAULT', 'G1');
                            break;
                    }
                }
                else{
                    return '';
                }
            },
        ],
        [
            'header' => 'SO_GOI_THAU',
            'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
            'value' => function($model) {
                if($model['tyle']>0){
                    return '01';
                }
                else{
                    return '';
                }
            },
        ],
        [
            'header' => 'DANGBAOCHE',
            'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
            'value' => function($model) {
                return '';
            },
        ],
        [
            'header' => 'TYLEHUHAO',
            'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
            'value' => function($model) {
                return '0';
            },
        ],
        [
            'attribute' => 'quyetdinh',
            'label' => 'QUYET_DINH',
            'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
        ],
        [
            'attribute' => 'congbo',
            'label' => 'CONG_BO',
            'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
            'value' => function($model) {
                if($model['tyle']>0){
                    return substr($model['congbo'], 6, 2).'/'.substr($model['congbo'], 4, 2).'/'.substr($model['congbo'], 0, 4);
                }
                else{
                    return '';
                }
            },
        ],
        [
            'header' => 'STT_BC_19_20',
            'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
            'value' => function($model) {
                return '';
            },
        ],
        [
            'attribute' => 'ten_ax',
            'label' => 'TEN_GIAM_DINH_BH',
            'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
        ],
        [
            'attribute' => 'tyle',
            'label' => 'BHYT_CHI_TRA',
            'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
        ],
        [
            'attribute' => 'dongia',
            'label' => 'GIA_BHYT_QUYET_TOAN',
            'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
        ],
        [
            'attribute' => 'dongia',
            'label' => 'GIA_THAU',
            'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
        ],
        [
            'attribute' => 'dongia',
            'label' => 'GIA_BAN',
            'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
        ],
        [
            'attribute' => 'mavt',
            'label' => 'MA_THUOC_BV',
            'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
        ],
        [
            'attribute' => 'dongia',
            'label' => 'GIA_BHYT',
            'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
        ],
        [
            'attribute' => 'dongia',
            'label' => 'GIA_DICH_VU',
            'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
        ],
    ];
}
else{ //Phiên bản L3
    $exportColumns = [
        [
            'attribute' => 'mavt',
            'label' => 'MAVATTU',
            'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
        ],
        [
            'attribute' => 'manhom',
            'label' => 'MANHOMVATTU',
            'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
        ],
        [
            'attribute' => 'madonvi',
            'label' => 'DVTT',
            'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
        ],
        [
            'attribute' => 'ten_ax',
            'label' => 'TENVATTU',
            'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
        ],
        [
            'attribute' => 'hoatchat_ax',
            'label' => 'HOATCHAT',
            'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
        ],
        [
            'attribute' => 'hamluong_ax',
            'label' => 'HAMLUONG',
            'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
        ],
        [
            'attribute' => 'donvitinh',
            'label' => 'DVT',
            'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
        ],
        [
            'attribute' => 'ma_duongdung_ax',
            'label' => 'CACHSUDUNG',
            'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
            'value' => function($model) {
                if(!$model['ma_duongdung_ax'] == '0.00') {
                    return '';
                }
                else{
                    return $model['ma_duongdung_ax'];
                }
            },
        ],
        [
            'header' => 'GHICHUVATTU',
            'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
            'value' => function($model) {
                return '';
            },
        ],
        [
            'attribute' => 'dongia',
            'label' => 'DONGIA_BV',
            'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
        ],
        [
            'attribute' => 'dongia',
            'label' => 'DONGIA_BH',
            'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
        ],
        [
            'header' => 'MALOAIHINH',
            'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
            'value' => function($model) {
                return '1';
            },
        ],
        [
            'attribute' => 'manhasanxuat',
            'label' => 'MANHASX',
            'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
            'value' => function($model) {
                if($model['manhasanxuat']){
                    return $model['manhasanxuat'];
                }
                else{
                    return ThuVien::getInfoThamSo('HIS_MANHASANXUAT_DEFAULT', '6061');
                }
            },
        ],
        [
            'attribute' => 'manuocsanxuat',
            'label' => 'MANUOCSX',
            'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
            'value' => function($model) {
                if($model['manuocsanxuat']){
                    return $model['manuocsanxuat'];
                }
                else{
                    return ThuVien::getInfoThamSo('HIS_MANUOCSANXUAT_DEFAULT', '1');
                }
            },
        ],
        [
            'header' => 'NGOAIDANHMUCBHYT',
            'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
            'value' => function($model) {
                return '0';
            },
        ],
        [
            'header' => 'TAMNGUNG',
            'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
            'value' => function($model) {
                return '0';
            },
        ],
        [
            'attribute' => 'sodangky_ax',
            'label' => 'SOGPDK',
            'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
        ],
        [
            'header' => 'TAMNGUNG',
            'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
            'value' => function($model) {
                return '';
            },
        ],
        [
            'attribute' => 'ma_ax',
            'label' => 'MABAOCAO_BYT',
            'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
        ],
        [
            'attribute' => 'loaithuoc',
            'label' => 'LOAIBAOCAO',
            'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
            'value' => function($model) {
                switch ($model['loaithuoc']) {
                    case 1:
                        return '1';
                        break;

                    case 2:
                        return '0';
                        break;

                    case 3:
                        return '2';
                        break;

                    case 4:
                        return '3';
                        break;

                    case 5:
                        return '3';
                        break;

                    default:
                        return '1';
                        break;
                }
            },
        ],
        [
            'header' => 'QUYCACH',
            'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
            'value' => function($model) {
                return '';
            },
        ],
        [
            'header' => 'MAVATTU_NHAP',
            'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
            'value' => function($model) {
                return '';
            },
        ],
        [
            'header' => 'MAVATTU5084',
            'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
            'value' => function($model) {
                return '';
            },
        ],
        [
            'header' => 'TENVATTUHIENTHI',
            'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
            'value' => function($model) {
                return '';
            },
        ],
        [
            'header' => 'MALIENTHONG_DMDC',
            'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
            'value' => function($model) {
                return '0';
            },
        ],
        [
            'attribute' => 'sodangky_ax',
            'label' => 'SO_DANGKY_DMDC',
            'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
        ],
        [
            'attribute' => 'ma_ax',
            'label' => 'MAHOATCHAT_DMDC',
            'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
        ],
        [
            'attribute' => 'ma_duongdung_ax',
            'label' => 'MADUONGDUNG_DMDC',
            'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
            'value' => function($model) {
                if(!$model['ma_duongdung_ax'] == '0.00') {
                    return '';
                }
                else{
                    return $model['ma_duongdung_ax'];
                }
            },
        ],
        [
            'attribute' => 'loaithuoc',
            'label' => 'LATHUOC',
            'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
            'value' => function($model) {
                switch ($model['loaithuoc']) {
                    case 1:
                        return '1';
                        break;

                    case 2:
                        return '0';
                        break;

                    case 3:
                        return '2';
                        break;

                    case 4:
                        return '3';
                        break;

                    case 5:
                        return '3';
                        break;

                    default:
                        return '1';
                        break;
                }
            },
        ],
        [
            'attribute' => 'sodangky_ax',
            'label' => 'SODANGKY',
            'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
        ],
        [
            'attribute' => 'quyetdinh',
            'label' => 'QUYETDINH',
            'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
        ],
        [
            'attribute' => 'ma_ax',
            'label' => 'MAHOATCHAT',
            'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
        ],
        [
            'attribute' => 'tyle',
            'label' => 'PHANTRAM_BHXH_TT',
            'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
        ],
        [
            'header' => 'STT_DMDC',
            'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
            'value' => function($model) {
                return '0';
            },
        ],
        [
            'attribute' => 'hoatchat_ax',
            'label' => 'HOACHAT_THEO_SDK_DMDC',
            'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
        ],
        [
            'attribute' => 'ma_duongdung_ax',
            'label' => 'DUONG_DUNG',
            'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
            'value' => function($model) {
                $info_dd = DuongDung::findOne($model['ma_duongdung_ax']);
                if($info_dd)
                    return $info_dd->mota;
                else
                    return '';
            },
        ],
        [
            'header' => 'DONGGOI_DMDC',
            'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
            'value' => function($model) {
                return '';
            },
        ],
        [
            'attribute' => 'nhasanxuat',
            'label' => 'HANG_SX',
            'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
            'value' => function($model) {
                if($model['nhasanxuat'] == ''){
                    return ThuVien::getInfoThamSo('HIS_NHASANXUAT_DEFAULT', 'Không rõ');
                }
                else{
                    return $model['nhasanxuat'];
                }
            },
        ],
        [
            'attribute' => 'nuocsanxuat',
            'label' => 'NUOC_SX',
            'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
            'value' => function($model) {
                if($model['nuocsanxuat'] == ''){
                    return ThuVien::getInfoThamSo('HIS_NUOCSANXUAT_DEFAULT', 'Không rõ');
                }
                else{
                    return $model['nuocsanxuat'];
                }
            },
        ],
        [
            'header' => 'MATINH_DMDC',
            'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
            'value' => function($model) {
                return '51';
            },
        ],
        [
            'attribute' => 'congbo',
            'label' => 'CONGBO_DMDC',
            'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
        ],
        [
            'attribute' => 'dongia',
            'label' => 'DONGIA_DMDC',
            'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
        ],
        [
            'attribute' => 'dongia',
            'label' => 'DONGIA_TT_DMDC',
            'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
        ],
        [
            'header' => 'SOLUONG_DMDC',
            'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
            'value' => function($model) {
                return '0';
            },
        ],
        [
            'attribute' => 'nhathau',
            'label' => 'NHA_CUNG_CAP',
            'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
            'value' => function($model) {
                if($model['nhathau'] == ''){
                    return ThuVien::getInfoThamSo('HIS_NHATHAU_DEFAULT', 'Không rõ');
                }
                else{
                    return $model['nhathau'];
                }
            },
        ],
        [
            'attribute' => 'manhasanxuat',
            'label' => 'MA_HANG_SANXUAT_DMDC',
            'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
            'value' => function($model) {
                if($model['manhasanxuat']){
                    return $model['manhasanxuat'];
                }
                else{
                    return ThuVien::getInfoThamSo('HIS_MANHASANXUAT_DEFAULT', '6061');
                }
            },
        ],
        [
            'attribute' => 'manuocsanxuat',
            'label' => 'MA_NUOC_SANXUAT_DMDC',
            'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
            'value' => function($model) {
                if($model['manuocsanxuat']){
                    return $model['manuocsanxuat'];
                }
                else{
                    return ThuVien::getInfoThamSo('HIS_MANUOCSANXUAT_DEFAULT', '1');
                }
            },
        ],
        [
            'header' => 'TEN_HIEN_THI',
            'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
            'value' => function($model) {
                return '';
            },
        ],
        [
            'header' => 'KY_THUAT_CAO',
            'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
            'value' => function($model) {
                return '0';
            },
        ],
        [
            'header' => 'TT_BIET_DUOC',
            'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
            'value' => function($model) {
                return '';
            },
        ],
        [
            'header' => 'DAMAP_DMDC',
            'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
            'value' => function($model) {
                return '0';
            },
        ],
        [
            'header' => 'TEN_TRUOC_MAP',
            'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
            'value' => function($model) {
                return '';
            },
        ],
        [
            'header' => 'HOAT_CHAT_TRUOC_MAP',
            'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
            'value' => function($model) {
                return '';
            },
        ],
        [
            'header' => 'DUONG_DUNG_TRUOC_MAP',
            'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
            'value' => function($model) {
                return '';
            },
        ],
        [
            'header' => 'DVT_TRUOC_MAP',
            'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
            'value' => function($model) {
                return '';
            },
        ],
        [
            'attribute' => 'dongia',
            'label' => 'DONGIA_BV_TRUOCMAP',
            'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
        ],
        [
            'attribute' => 'dongia',
            'label' => 'DONGIA_BH_TRUOCMAP',
            'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
        ],
        [
            'header' => 'HANG_SX_TRUOC_MAP',
            'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
            'value' => function($model) {
                return '';
            },
        ],
        [
            'header' => 'NUOC_SX_TRUOC_MAP',
            'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
            'value' => function($model) {
                return '';
            },
        ],
        [
            'header' => 'QUYETDINH_TRUOC_MAP',
            'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
            'value' => function($model) {
                return '';
            },
        ],
        [
            'header' => 'QUYCACH_TRUOC_MAP',
            'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
            'value' => function($model) {
                return '';
            },
        ],
        [
            'header' => 'CONGBO_TRUOC_MAP',
            'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
            'value' => function($model) {
                return '';
            },
        ],
        [
            'header' => 'SO_DK_DM_TRUOC_MAP',
            'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
            'value' => function($model) {
                return '';
            },
        ],
        [
            'header' => 'SO_DK_TRUOC_MAP',
            'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
            'value' => function($model) {
                return '';
            },
        ],
        [
            'attribute' => 'dongia',
            'label' => 'DONGIA_DM_TRUOC_MAP',
            'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
        ],
        [
            'header' => 'SOLUONG_DM_TRUOC_MAP',
            'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
            'value' => function($model) {
                return '0';
            },
        ],
        [
            'header' => 'NHATHAU_TRUOC_MAP',
            'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
            'value' => function($model) {
                return '';
            },
        ],
        [
            'attribute' => 'hamluong_ax',
            'label' => 'HAM_LUONG_DMDC',
            'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
        ],
        [
            'header' => 'DICHVU_KY_THUAT_CAO',
            'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
            'value' => function($model) {
                return '0';
            },
        ],
        [
            'header' => 'TIEN_BHXH_CHI',
            'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
            'value' => function($model) {
                return '0';
            },
        ],
        [
            'header' => 'TIEN_BN_CHI',
            'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
            'value' => function($model) {
                return '0';
            },
        ],
        [
            'header' => 'DK_BAO_QUAN',
            'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
            'value' => function($model) {
                return '';
            },
        ],
        [
            'header' => 'MANHACC',
            'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
            'value' => function($model) {
                return '0';
            },
        ],
        [
            'header' => 'DUOCCONLAI',
            'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
            'value' => function($model) {
                return '0';
            },
        ],
        [
            'attribute' => 'goithau',
            'label' => 'MA_GOI_THAU_VT',
            'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
            'value' => function($model) {
                if($model['tyle']>0){
                    switch (preg_replace('/\s+/', '', strtolower($model['goithau']))) {
                        case 'g1':
                            return 'G1';
                            break;

                        case 'g2':
                            return 'G2';
                            break;

                        case 'g3':
                            return 'G3';
                            break;

                        case 'g4':
                            return 'G4';
                            break;

                        case 'g5':
                            return 'G5';
                            break;

                        default:
                            return ThuVien::getInfoThamSo('HIS_GOITHAU_DEFAULT', 'G1');
                            break;
                    }
                }
                else{
                    return '';
                }
            },
        ],
        [
            'attribute' => 'nhomthau',
            'label' => 'MA_NHOM_THAU_VT',
            'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
            'value' => function($model) {
                if($model['tyle']>0){
                    switch (preg_replace('/\s+/', '', strtolower($model['nhomthau']))) {
                        case 'n1':
                            return 'N1';
                            break;
                        
                        case 'nhóm1':
                            return 'N1';
                            break;

                        case 'n2':
                            return 'N2';
                            break;

                        case 'nhóm2':
                            return 'N2';
                            break;

                        case 'n3':
                            return 'N3';
                            break;

                        case 'nhóm3':
                            return 'N3';
                            break;

                        case 'n4':
                            return 'N4';
                            break;

                        case 'nhóm4':
                            return 'N4';
                            break;

                        case 'n5':
                            return 'N5';
                            break;

                        case 'nhóm5':
                            return 'N5';
                            break;

                        default:
                            return ThuVien::getInfoThamSo('HIS_NHOMTHAU_DEFAULT', 'N1');
                            break;
                    }
                }
                else{
                    return '';
                }
            },
        ],
        [
            'attribute' => 'quyetdinh',
            'label' => 'SO_CV_GUI_BHXH',
            'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
        ],
        [
            'header' => 'SOCONGVAN_BHXH',
            'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
            'value' => function($model) {
                return '';
            },
        ],
        [
            'header' => 'KHONGNHAPSONGAY',
            'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
            'value' => function($model) {
                return '0';
            },
        ],
        [
            'header' => 'SOLUONG_QUYDINH',
            'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
            'value' => function($model) {
                return '';
            },
        ],
        [
            'header' => 'DINHMUC_MA_NHOM_VATTU',
            'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
            'value' => function($model) {
                return '0';
            },
        ],
        [
            'header' => 'STENT_VATTU',
            'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
            'value' => function($model) {
                return '0';
            },
        ],
        [
            'header' => 'STT_STENT',
            'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
            'value' => function($model) {
                return '1';
            },
        ],
        [
            'header' => 'NGOAI_DANH_MUC_BHPVI',
            'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
            'value' => function($model) {
                return '0';
            },
        ],
        [
            'header' => 'MA_DICH_VU_VTYT',
            'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
            'value' => function($model) {
                return '';
            },
        ],
        [
            'header' => 'THUOC_BAC_NAM',
            'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
            'value' => function($model) {
                return '';
            },
        ],
        [
            'header' => 'DANGTHUOC',
            'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
            'value' => function($model) {
                return '';
            },
        ],
        [
            'header' => 'ABC',
            'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
            'value' => function($model) {
                return '';
            },
        ],
        [
            'header' => 'VEN',
            'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
            'value' => function($model) {
                return '';
            },
        ],
        [
            'header' => 'MA_BAOCAO_QD_PL_M_CM',
            'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
            'value' => function($model) {
                return '';
            },
        ],
        [
            'header' => 'MACDINHTHUOC_GOIVATTUKTC',
            'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
            'value' => function($model) {
                return '';
            },
        ],
        [
            'header' => 'MACDINH_SUDUNGTRONGGOI',
            'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
            'value' => function($model) {
                return '';
            },
        ],
        [
            'header' => 'TEN_NOIDUNG_6556',
            'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
            'value' => function($model) {
                return '';
            },
        ],
        [
            'header' => 'DVT_6556',
            'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
            'value' => function($model) {
                return '';
            },
        ],
        [
            'header' => 'TT_THUOC',
            'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
            'value' => function($model) {
                return '';
            },
        ],
        [
            'header' => 'PHANTRAM_TT30',
            'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
            'value' => function($model) {
                return '';
            },
        ],
        [
            'header' => 'NGAYAPDUNG_TT30',
            'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
            'value' => function($model) {
                return '';
            },
        ],
    ];
}

$gridColumns = [
    [
        'class' => 'kartik\grid\CheckboxColumn',
        'headerOptions' => ['class' => 'kartik-sheet-style'],
    ],
    [
        'class'=>'kartik\grid\SerialColumn',
        'contentOptions'=>['class'=>'kartik-sheet-style'],
        'pageSummary'=>'Total',
        'header'=>'STT',
        'headerOptions'=>['class'=>'kartik-sheet-style'],
        'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
    ],
    [
        'class' => 'kartik\grid\EditableColumn',
        'attribute' => 'mavt',
        'label' => 'Mã VT',
        'vAlign' => 'middle', 
        'hAlign' => 'center',
        'refreshGrid' => true,
        'filterInputOptions' => ['maxlength' => 50, 'class' => 'form-control'],
        'readonly' => ($phieuduoc->status == 0) ? false : true,
        'editableOptions' =>function ($model, $key, $index) {
          return [
            'name' => 'mavt',
            'asPopover' => false,
            'inputType' => \kartik\editable\Editable::INPUT_TEXT,
            'resetButton' => ['class'=>'hide'],
            'formOptions'   => [
                'action'    => [
                    '/dmduoc/updatemavt'
                ],
            ],
          ];
        },
        'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
        'headerOptions'=>['class'=>'kartik-sheet-style', 'style' => 'min-width:120px;'],
    ],
    [
        'class' => 'kartik\grid\EditableColumn',
        'attribute' => 'manhom',
        'label' => 'Mã nhóm',
        'vAlign' => 'middle', 
        'hAlign' => 'center',
        'refreshGrid' => true,
        'filterInputOptions' => ['maxlength' => 50, 'class' => 'form-control'],
        'readonly' => ($phieuduoc->status == 0) ? false : true,
        'editableOptions' =>function ($model, $key, $index) {
          return [
            'name' => 'manhom',
            'asPopover' => false,
            'inputType' => \kartik\editable\Editable::INPUT_TEXT,
            'resetButton' => ['class'=>'hide'],
            'formOptions'   => [
                'action'    => [
                    '/dmduoc/updatemavt'
                ],
            ],
          ];
        },
        'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
        'headerOptions'=>['class'=>'kartik-sheet-style', 'style' => 'min-width:120px;'],
    ],
    [
        'attribute' => 'ma_ax',
        'label' => 'Mã BC',
        'vAlign' => 'middle', 
        'hAlign' => 'center',
        'filterInputOptions' => ['maxlength' => 50, 'class' => 'form-control'],
        'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
        'headerOptions'=>['class'=>'kartik-sheet-style', 'style' => 'min-width:70px;'],
    ],
    [
        'attribute' => 'ten_ax',
        'label' => 'Tên dược/ vật tư',
        'vAlign' => 'middle', 
        'filterInputOptions' => ['maxlength' => 255, 'class' => 'form-control'],
        'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
        'headerOptions' => ['class' => 'kartik-sheet-style', 'style' => 'min-width:150px;'],
    ],
    [
        'attribute' => 'hoatchat_ax',
        'label' => 'Hoạt chất',
        'vAlign' => 'middle', 
        'filterInputOptions' => ['maxlength' => 255, 'class' => 'form-control'],
        'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
        'headerOptions' => ['class' => 'kartik-sheet-style', 'style' => 'min-width:150px;'],
    ],      
    [
        'attribute' => 'hamluong_ax',
        'label' => 'Hàm lượng',
        'vAlign' => 'middle', 
        'hAlign' => 'center',
        'filterInputOptions' => ['maxlength' => 255, 'class' => 'form-control'],
        'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
        'headerOptions' => ['class' => 'kartik-sheet-style', 'style' => 'min-width:120px;'],
    ],
    [
        'attribute' => 'ma_duongdung_ax_filter',
        'label' => 'Đường dùng',
        'vAlign' => 'middle', 
        'hAlign' => 'center',
        'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
        'headerOptions' => ['class' => 'kartik-sheet-style', 'style' => 'min-width:150px;'],
        'value' => function($model) {
            if(!$model->duongdung) {
                return '';
            }
            else{
                return $model->duongdung->mota;
            }
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => $arr, 
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true, 'placeholder' => 'Chọn...'],
        ],
        'filterInputOptions' => ['multiple' => true],
        'format' => 'raw',
    ],
    [
        'attribute' => 'donvitinh',
        'label' => 'Đơn vị tính',
        'vAlign' => 'middle', 
        'hAlign' => 'center',
        'filterInputOptions' => ['maxlength' => 50, 'class' => 'form-control'],
        'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
        'headerOptions' => ['class' => 'kartik-sheet-style', 'style' => 'min-width:120px;'],
    ],
    [
        'attribute' => 'dongia',
        'label' => 'Đơn giá',
        'vAlign' => 'middle', 
        'hAlign' => 'right',
        'format'=>['decimal',2],
        'filterInputOptions' => ['maxlength' => 11, 'class' => 'form-control'],
        'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
        'headerOptions' => ['class' => 'kartik-sheet-style sort-number', 'style' => 'min-width:90px;'],
    ],
    [
        'attribute' => 'quycach',
        'label' => 'Quy cách',
        'vAlign' => 'middle', 
        'hAlign' => 'center',
        'filterInputOptions' => ['maxlength' => 255, 'class' => 'form-control'],
        'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
        'headerOptions' => ['class' => 'kartik-sheet-style', 'style' => 'min-width:150px;'],
    ],
    [
        'attribute' => 'sodangky_ax',
        'label' => 'Số đăng ký',
        'vAlign' => 'middle', 
        'hAlign' => 'center',
        'filterInputOptions' => ['maxlength' => 255, 'class' => 'form-control'],
        'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
        'headerOptions' => ['class' => 'kartik-sheet-style', 'style' => 'min-width:100px;'],
    ],
    [
        'attribute' => 'nhasanxuat',
        'label' => 'Nhà SX',
        'vAlign' => 'middle', 
        'filterInputOptions' => ['maxlength' => 255, 'class' => 'form-control'],
        'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
        'headerOptions' => ['class' => 'kartik-sheet-style', 'style' => 'min-width:150px;'],
    ],
    [
        'attribute' => 'nuocsanxuat',
        'label' => 'Nước SX',
        'vAlign' => 'middle', 
        'filterInputOptions' => ['maxlength' => 255, 'class' => 'form-control'],
        'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
        'headerOptions' => ['class' => 'kartik-sheet-style', 'style' => 'min-width:100px;'],
    ],
    [
        'attribute' => 'nhathau',
        'label' => 'Nhà thầu',
        'vAlign' => 'middle', 
        'filterInputOptions' => ['maxlength' => 255, 'class' => 'form-control'],
        'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
        'headerOptions' => ['class' => 'kartik-sheet-style', 'style' => 'min-width:150px;'],
    ],
    [
        'attribute' => 'quyetdinh',
        'label' => 'Quyết định',
        'vAlign' => 'middle', 
        'hAlign' => 'center',
        'filterInputOptions' => ['maxlength' => 50, 'class' => 'form-control'],
        'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
        'headerOptions' => ['class' => 'kartik-sheet-style', 'style' => 'min-width:150px;'],
    ],
    [
        'attribute' => 'congbo',
        'label' => 'Công bố',
        'vAlign' => 'middle', 
        'hAlign' => 'center',
        'filterInputOptions' => ['maxlength' => 8, 'class' => 'form-control'],
        'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
        'headerOptions' => ['class' => 'kartik-sheet-style sort-number', 'style' => 'min-width:100px;'],
    ],
    [
        'attribute' => 'loaithuoc_filter',
        'label' => 'Loại thuốc',
        'vAlign' => 'middle', 
        'hAlign' => 'center',
        'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
        'headerOptions' => ['class' => 'kartik-sheet-style', 'style' => 'min-width:130px;'],
        'value' => function($model) {
            switch ($model->loaithuoc) {
                case 1:
                    return 'Tân dược';
                break;
                case 2:
                    return 'Chế phẩm';
                break;
                case 3:
                    return 'Vị thuốc';
                break;
                case 4:
                    return 'VTYT';
                break;
                case 5:
                    return 'Hóa chất';
                break;
            }
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => array(1 => 'Tân dược', 2 => 'Chế phẩm', 3 => 'Vị thuốc', 4 => 'VTYT', 5 => 'Hóa chất'), 
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true, 'placeholder' => 'Chọn...'],
        ],
        'filterInputOptions' => ['multiple' => true],
        'format' => 'raw',
    ],
    [
        'attribute' => 'goithau',
        'label' => 'Gói thầu',
        'vAlign' => 'middle', 
        'hAlign' => 'center',
        'filterInputOptions' => ['maxlength' => 50, 'class' => 'form-control'],
        'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
        'headerOptions' => ['class' => 'kartik-sheet-style', 'style' => 'min-width:100px;'],
    ],
    [
        'attribute' => 'nhomthau',
        'label' => 'Nhóm thầu',
        'vAlign' => 'middle', 
        'hAlign' => 'center',
        'filterInputOptions' => ['maxlength' => 50, 'class' => 'form-control'],
        'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
        'headerOptions' => ['class' => 'kartik-sheet-style', 'style' => 'min-width:100px;'],
    ],
    [
        'attribute' => 'tyle',
        'label' => 'Tỷ lệ (%)',
        'vAlign' => 'middle', 
        'hAlign' => 'center',
        'filterInputOptions' => ['maxlength' => 3, 'class' => 'form-control'],
        'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
        'headerOptions' => ['class' => 'kartik-sheet-style sort-number', 'style' => 'min-width:90px;'],
    ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'dropdownOptions' => ['class' => 'float-right'],
        'vAlign' => 'middle',
        'header' => '',
        'hAlign' => 'center',
        'mergeHeader' => false,
        'template' => '{reload} {update} {delete}',
        'buttons' => [
            'update' => function ($url, $model, $key) {
                return Html::a('<span class="glyphicon glyphicon-pencil"></span>',false, [
                    'class' => 'activity-edit icon-skin',
                    'title' => 'Chỉnh sửa dữ liệu', 
                    'data-toggle' => 'tooltip',
                    'data-url' => $url,
                ]);
            },
            'reload' => function ($url, $model, $key) {
                return Html::a('<span class="glyphicon glyphicon-play"></span>',false, [
                    'class' => 'activity-reload icon-skin',
                    'title' => 'Cho phép tính lại đơn giá', 
                    'data-toggle' => 'tooltip',
                    'data-url' => $url,
                ]);
            },
            'delete' => function ($url, $model, $key) {
                return Html::a('<span class="glyphicon glyphicon-trash"></span>',false, [
                    'class' => 'activity-delete icon-skin',
                    'title' => 'Xóa dữ liệu', 
                    'data-toggle' => 'tooltip',
                    'data-url' => $url,
                    'data-confirm' => 'DVTT là <b></b> và mã dịch vụ là <b></b>',
                ]);
            },
        ],
        'headerOptions' => ['class' => 'kartik-sheet-style', 'style' => 'border-bottom : 1px solid transparent; min-width:70px; max-width:70px;'],
    ],
];
?>
<div class="content-wrapper">
    <div id="addModal" class="modal fade" data-backdrop="static">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header box-gridview">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"></h4>
                </div>
                <div class="modal-body">
                </div>
            </div>

        </div>
    </div>
    <div id="confirmModal" class="modal fade" data-backdrop="static">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header box-gridview">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"></h4>
                </div>
                <div class="modal-body">
                </div>
                <div class="modal-footer">
                </div>
            </div>

        </div>
    </div>
    <section class="content-header">
        <p class="breadcrumb box-gridview">Chi tiết phiếu tạo danh mục dược - p<?=$id_phieu;?> [<?=$flag_phienban ? 'His L2' : 'His L3';?>] - <?=$phieuduoc->donvi->tendonvi;?><?=$phieuduoc->saochep ? ' - [Cho phép nhân bản danh mục]' : '';?></p>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <?php
                        echo ExportMenu::widget([
                            'dataProvider' => $exProvider,
                            'autoXlFormat'=>true,
                            'columns' => $exportColumns,
                            'initProvider' => true,
                            'options' => ['id'=>'expIndex'],
                            'columnSelectorOptions'=>[
                                'label' => 'Chọn...',
                            ],
                            'hiddenColumns' => [],
                            'dropdownOptions' => [
                                'label' => 'Xuất tất cả',
                                'class' => 'btn btn-default',

                            ],
                            'exportConfig' => [
                                ExportMenu::FORMAT_PDF => ['filename' => 'grid-export_'.date('d_m_Y')],
                                ExportMenu::FORMAT_TEXT => ['filename' => 'grid-export_'.date('d_m_Y')],
                                ExportMenu::FORMAT_HTML => ['filename' => 'grid-export_'.date('d_m_Y')],
                                ExportMenu::FORMAT_CSV => ['filename' => 'grid-export_'.date('d_m_Y')],
                                ExportMenu::FORMAT_EXCEL => ['filename' => 'grid-export_'.date('d_m_Y')],
                            ],
                            'filename' => 'grid-export_'.date('d_m_Y')

                        ]);
                        ?>
                    </div>
                    <div class="box-body">
                        <?php
                        echo GridView::widget([
                            'id' => 'mygrid',
                            'dataProvider' => $dataProvider,
                            'filterModel' => $searchModel,
                            'filterSelector' => '#myPageSize',
                            'autoXlFormat'=>true,
                            'columns' => $gridColumns,
                            'pjax' => true,
                            'pjaxSettings' => [
                                'neverTimeout'=>true,
                                'options' => [
                                    'enablePushState' => false,
                                    'id' => 'theDatatable',
                                ]
                            ],
                            //'floatHeader' => true,
                            //'floatOverflowContainer' => true,
                            'headerRowOptions' => ['class' => 'kartik-sheet-style'],
                            'filterRowOptions' => ['class' => 'kartik-sheet-style'],
                            'toolbar' =>  [
                                [
                                    'content' =>
                                        ((ThuVien::laySLNhaSanXuatThieuMa($id_phieu)>0 && !$flag_phienban) ? Html::button('<i class="fa fa-industry"></i> '.'<i class="fa fa-exclamation" style="color:red;"></i>', [
                                            'class' => 'btn btn-skin btn-show-nhasx',
                                            'data-url' => Url::to(['dmduoc/shownhasx', 'id' => $id_phieu]),
                                            'title' => 'Danh sách nhà sản xuất thiếu mã',
                                        ]) : '') . ' '.
                                        ((ThuVien::laySLNuocSanXuatThieuMa($id_phieu)>0 && !$flag_phienban) ? Html::button('<i class="fa fa-flag-checkered"></i> '.'<i class="fa fa-exclamation" style="color:red;"></i>', [
                                            'class' => 'btn btn-default btn-show-nuocsx',
                                            'data-url' => Url::to(['dmduoc/shownuocsx', 'id' => $id_phieu]),
                                            'title' => 'Danh sách nước sản xuất thiếu mã',
                                        ]) : '') . ' '.
                                        Html::button('<i class="fa fa-plus"></i>', [
                                            'class' => 'btn btn-skin btn-add-grid',
                                            'data-url' => Url::to(['dmduoc/create', 'id' => $id_phieu]),
                                            'title' => 'Thêm mới',
                                        ]) . ' '.
                                        Html::a('<i class="fa fa-undo"></i>', Url::to(['dmduoc/index', 'id' => $id_phieu]), [
                                            'class' => 'btn btn-default',
                                            'title'=>'Làm mới dữ liệu',
                                            'data-pjax' => 0, 
                                        ]) . ' '.
                                        Html::button('<i class="fa fa-upload"></i>', [
                                            'class' => 'btn btn-skin btn-import-grid',
                                            'data-url' => Url::to(['dmduoc/import', 'id' => $id_phieu]),
                                            'title' => 'Import dữ liệu (Excel)',
                                        ]) . ' '.
                                        Html::activeDropDownList($searchModel, 'myPageSize', 
                                            [10 => 10, 20 => 50, 50 => 50],
                                            [
                                                'id'=>'myPageSize', 
                                                'class' => 'btn btn-skin',
                                                'style' => 'height:auto',
                                                'title'=>'Số dòng hiển thị trên trang',
                                            ]
                                        ), 
                                    'options' => ['class' => 'btn-group mr-2']
                                ],
                                '{export}',
                                '{toggleData}',
                            ],
                            'toggleDataContainer' => ['class' => 'btn-group mr-2'],
                            'export' => [
                                'fontAwesome' => true,
                            ],
                            'bordered' => true,
                            'striped' => false,
                            'condensed' => true,
                            'responsive' => true,
                            'responsiveWrap' => false,
                            'hover' => true,
                            'panel' => [
                                'type' => GridView::TYPE_PRIMARY,
                                'heading' => '<i class="fa fa-list-alt"></i>',
                                'after' => false,
                                'headingOptions' => ['class'=>'box-gridview'],
                            ],
                            'persistResize' => false,
                            'toggleDataOptions' => ['minCount' => 10],
                            'itemLabelSingle' => 'dữ liệu',
                            'itemLabelPlural' => 'dữ liệu',
                            'pager' => [
                                'options'=>['class'=>'pagination'], 
                                'prevPageLabel' => '<i class="fa fa-chevron-left" aria-hidden="true"></i>',
                                'nextPageLabel' => '<i class="fa fa-chevron-right" aria-hidden="true"></i>', 
                                'firstPageLabel'=>'<i class="fa fa-fast-backward" aria-hidden="true"></i>', 
                                'lastPageLabel'=>'<i class="fa fa-fast-forward" aria-hidden="true"></i>', 
                                'nextPageCssClass'=>'next',
                                'prevPageCssClass'=>'prev',
                                'firstPageCssClass'=>'first',
                                'lastPageCssClass'=>'last',
                                'maxButtonCount'=>4,
                            ],
                        ]);
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<?php $this->registerJs( '$(document).on("pjax:end", function() { var btns = $("[data-toggle=\'tooltip\']"); if (btns.length) { btns.tooltip(); } }); '); ?>