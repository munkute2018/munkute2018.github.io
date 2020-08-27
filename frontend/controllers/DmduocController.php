<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\DmDuoc;
use common\models\DuocTicket;
use common\models\DmDuocSearch;
use common\models\DuongDung;
use common\models\NhaSanXuat;
use common\models\NuocSanXuat;
use common\models\FileDuocForm;
use yii\web\Response;
use yii\widgets\ActiveForm;
use yii\helpers\Json;
use common\models\ThuVien;
use yii\web\UploadedFile;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xls;

/**
 * Site controller
 */
class DmduocController extends Controller
{
    public function init()
    {
        parent::init();
        \Yii::$app->language = 'vi';
    }

    public function beforeAction($action) 
    { 
        $this->enableCsrfValidation = false; 
        return parent::beforeAction($action); 
    }

    public function actionIndex($id){
        if (Yii::$app->user->can('dmduoc/index', ['id_phieu' => $id])) {
            $searchModel = new DmDuocSearch();
            $searchModel->id_phieu = $id;
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

            $exProvider = ThuVien::layDanhSachDanhMucDuocExport($id);
            
            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'exProvider' => $exProvider,
                'id_phieu' => $id,
            ]);
        }
        else{
            return $this->render('//site/errorrole',['message' => 'Liên hệ người quản trị để cấp quyền cho bạn hoặc đăng nhập với tài khoản khác!']);
        }
    }

    public function actionImport($id)
    {
        if(isset($_SERVER['HTTP_REFERER']))
        {
            if (Yii::$app->user->can('dmduoc/import', ['id_phieu' => $id])) {
                $phieuduoc = DuocTicket::findOne($id);
                if($phieuduoc && $phieuduoc->status == 0){
                    $model = new FileDuocForm();
                    $model->phanloai = 1;
                    $model->mabc = 'C';
                    $model->tenvt = 'M';
                    $model->hoatchat = 'E';
                    $model->hamluong = 'K';
                    $model->duongdung = 'G';
                    $model->donvitinh = 'Q';
                    $model->dongia = 'R';
                    $model->quycach = 'P';
                    $model->sodangky = 'O';
                    $model->nhasx = 'V';
                    $model->nuocsx = 'W';
                    $model->nhathau = 'X';
                    $model->quyetdinh = 'Y';
                    $model->congbo = 'Z';
                    $model->loaithuoc = 'AB';
                    $model->goithau = 'AC';
                    $model->nhomthau = 'AE';
                    if(Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {  
                        $model->file = UploadedFile::getInstance($model, 'file');           
                        if ($model->file && $model->validate()) { 
                            //Đọc file    
                            try{        
                                $inputFileType = IOFactory::identify($model->file->tempName);
                                $objReader = IOFactory::createReader($inputFileType);
                                $objPHPExcel = $objReader->load($model->file->tempName);
                            }
                            catch(Exception $e){
                                die('Error');
                            }
                            $sheetData = $objPHPExcel->getActiveSheet(0);
                            $Totalrow = $sheetData->getHighestRow();
                            $transaction = \Yii::$app->db->beginTransaction();
                            $errorArr = [];
                            $spreadsheet;
                            $sheet;
                            $flagErr = false;
                            $rowErr = 2;
                            $rowStart = 2;
                            for($i = $rowStart; $i <= $Totalrow; $i++){
                                $info = new DmDuoc();
                                $info->id_phieu = $id;
                                $info->ten_ax = (string)$sheetData->getCell($model->tenvt.$i)->getValue();
                                $info->donvitinh = (string)$sheetData->getCell($model->donvitinh.$i)->getValue();
                                $info->dongia = $sheetData->getCell($model->dongia.$i)->getValue();
                                $info->quycach = (string)$sheetData->getCell($model->quycach.$i)->getValue();
                                $info->nhasanxuat = (string)$sheetData->getCell($model->nhasx.$i)->getValue();
                                $info->nuocsanxuat = (string)$sheetData->getCell($model->nuocsx.$i)->getValue();
                                $info->nhathau = (string)$sheetData->getCell($model->nhathau.$i)->getValue();
                                if($model->thanhtoan == 1){
                                    $info->ma_ax = (string)$sheetData->getCell($model->mabc.$i)->getValue();
                                    $info->quyetdinh = (string)$sheetData->getCell($model->quyetdinh.$i)->getValue();
                                    $info->congbo = (string)$sheetData->getCell($model->congbo.$i)->getValue();
                                    if($model->phanloai == 1){
                                        $info->hoatchat_ax = (string)$sheetData->getCell($model->hoatchat.$i)->getValue();
                                        $info->hamluong_ax = (string)$sheetData->getCell($model->hamluong.$i)->getValue();
                                        $info->ma_duongdung_ax = (string)$sheetData->getCell($model->duongdung.$i)->getValue();
                                        $info->sodangky_ax = (string)$sheetData->getCell($model->sodangky.$i)->getValue();
                                        $info->goithau = (string)$sheetData->getCell($model->goithau.$i)->getValue();
                                        $info->nhomthau = (string)$sheetData->getCell($model->nhomthau.$i)->getValue();
                                        $info->loaithuoc = (string)$sheetData->getCell($model->loaithuoc.$i)->getValue();
                                        $info->tyle = 100;
                                    }
                                    if($model->phanloai == 2){
                                        $info->loaithuoc = 4;
                                        $info->tyle = 100;
                                    }
                                }
                                else{
                                    $info->hoatchat_ax = (string)$sheetData->getCell($model->hoatchat.$i)->getValue();
                                    $info->hamluong_ax = (string)$sheetData->getCell($model->hamluong.$i)->getValue();
                                    $info->tyle = 0;
                                    $tendd = trim((string)$sheetData->getCell($model->duongdung.$i)->getValue());
                                    $infodd = DuongDung::find()->where('lower(mota) = lower("'.$tendd.'")')->one();
                                    if($infodd)
                                        $info->ma_duongdung_ax = $infodd->id;
                                    else
                                        $info->ma_duongdung_ax = '0.00';
                                    $loaithuoc = $sheetData->getCell($model->loaithuoc.$i)->getValue();
                                    if($loaithuoc != 1 && $loaithuoc != 2 && $loaithuoc != 3 && $loaithuoc != 4 && $loaithuoc != 5){
                                        $info->loaithuoc = $model->phanloai;
                                    }
                                    else{
                                        $info->loaithuoc = $loaithuoc;
                                    }
                                }
                                if (!$info->validate() || ($model->thanhtoan == 1 && $model->phanloai == 1 && !$info->validateThuocBHYT()) || ($model->thanhtoan == 1 && $model->phanloai == 2 && !$info->validateVTBHYT()) || !$info->save()) {
                                    $errorArr = $info->getErrors();
                                    if(!$flagErr){
                                        $spreadsheet = new Spreadsheet();
                                        $sheet = $spreadsheet->getActiveSheet();
                                        $sheet->getColumnDimension('A')->setAutoSize(true);
                                        $sheet->getColumnDimension('B')->setAutoSize(true);
                                        $sheet->setCellValue('A1', 'Vị trí dòng');
                                        $sheet->setCellValue('B1', 'Thông tin lỗi dữ liệu');
                                    }
                                    $sheet->setCellValue('A'.$rowErr, 'Dòng '.$i);
                                    $msg = "";
                                    foreach($errorArr as $key=>$val){
                                        foreach($val as $keychild=>$valchild){
                                            if($msg != ""){
                                                $msg.=PHP_EOL;
                                            }
                                            $msg.=$valchild;
                                        }
                                    }
                                    $sheet->setCellValue('B'.$rowErr, $msg);
                                    $rowErr++;
                                    $flagErr = true;
                                }
                            }
                            if($flagErr){
                                $transaction->rollBack();
                                $sheet->getStyle('A1:B'.($rowErr-1))->getAlignment()->applyFromArray([
                                    'vertical'     => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                                    'wrapText'     => TRUE
                                ]);
                                $sheet->getStyle('A1:B'.($rowErr-1))->getFont()->applyFromArray([ 'name' => 'Time New Roman']);
                                $sheet->getStyle('A1:B1')->getFont()->applyFromArray([ 'bold' => TRUE]);
                                $folder = trim(Yii::getAlias('@app/runtime/export'));
                                $filename="Error_Import_".(time()).".xls";
                                $file = $folder.'/'.$filename;
                                $writer = IOFactory::createWriter($spreadsheet, 'Xls');
                                ob_start();
                                $writer->save("php://output");
                                $xlsData = ob_get_contents();
                                ob_end_clean();
                                return Json::encode(array('status' => false, 'hideModal' => false, 'message' => 'Lỗi định dạng file import!', 'file_err' => "data:application/vnd.ms-excel;base64,".base64_encode($xlsData)));
                            }
                            else{
                                $transaction->commit(); 
                                return Json::encode(array('status' => true, 'message' => 'Import thành công '.($Totalrow-1).' dữ liệu!'));
                            }
                        } 
                        else {
                            $transaction->rollBack();
                            return Json::encode(array('status' => false, 'hideModal' => false, 'message' => 'Thêm không thành công! Vui lòng kiểm tra lại dữ liệu hoặc kết nối.'));
                        }
                    }
                    return Json::encode(array('status' => true, 'title' => 'Import danh mục dược', 'data' => $this->renderAjax('_import', ['model' => $model, 'phienban' => $phieuduoc->phienban])));
                }
                else{
                    return Json::encode(array('status' => false, 'hideModal' => true, 'message' => 'Thật xin lỗi! Phiếu tạo danh mục dược của bạn đã được chốt dữ liệu.'));
                }
            }
            else{
                return Json::encode(array('status' => false, 'hideModal' => false, 'message' => 'Bạn không có quyền sử dụng tính năng này hay can thiệp vào phiếu của người dùng khác! Liên hệ người quản trị để cấp quyền cho bạn hoặc đăng nhập với tài khoản khác!'));
            }
        }
        else{
            $this->layout= "main_error";
            return $this->render('//site/error',['message' => 'Thật xin lỗi! Thông tin mà bạn đang tìm kiếm hiện tại không tồn tại!']);
        }
    }

    public function actionImportvalidate() {
        if(isset($_SERVER['HTTP_REFERER']))
        {
            $model = new FileDuocForm();
            if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
                \Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
        }
        else{
            $this->layout= "main_error";
            return $this->render('//site/error',['message' => 'Thật xin lỗi! Thông tin mà bạn đang tìm kiếm hiện tại không tồn tại!']);
        }
    }

    public function actionShownhasx($id)
    {
        if(isset($_SERVER['HTTP_REFERER']))
        {
            if(Yii::$app->user->can('dmduoc/shownhasx', ['id_phieu' => $id])) {
                $phieuduoc = DuocTicket::findOne($id);
                if($phieuduoc && $phieuduoc->status == 0){
                    $dataProvider = ThuVien::layDSNhaSanXuatThieuMa($id);
                    return Json::encode(array('status' => true, 'title' => 'Cập nhật danh mục nhà sản xuất chưa tồn tại (Mã phiếu: p'.$id.')', 'data' => $this->renderAjax('_shownhasx', ['nhasxProvider' => $dataProvider])));
                }
                else{
                    return Json::encode(array('status' => false, 'hideModal' => true, 'message' => 'Thật xin lỗi! Phiếu tạo danh mục dược của bạn đã được chốt dữ liệu.'));
                }
            }
            else{
                return Json::encode(array('status' => false, 'hideModal' => false, 'message' => 'Bạn không có quyền sử dụng tính năng này hay can thiệp vào phiếu của người dùng khác! Liên hệ người quản trị để cấp quyền cho bạn hoặc đăng nhập với tài khoản khác!'));
            }
        }
        else{
            $this->layout= "main_error";
            return $this->render('//site/error',['message' => 'Thật xin lỗi! Thông tin mà bạn đang tìm kiếm hiện tại không tồn tại!']);
        }
    }

    public function actionShownuocsx($id)
    {
        if(isset($_SERVER['HTTP_REFERER']))
        {
            if(Yii::$app->user->can('dmduoc/shownuocsx', ['id_phieu' => $id])) {
                $phieuduoc = DuocTicket::findOne($id);
                if($phieuduoc && $phieuduoc->status == 0){
                    $dataProvider = ThuVien::layDSNuocSanXuatThieuMa($id);
                    return Json::encode(array('status' => true, 'title' => 'Cập nhật danh mục nước sản xuất chưa tồn tại (Mã phiếu: p'.$id.')', 'data' => $this->renderAjax('_shownuocsx', ['nuocsxProvider' => $dataProvider])));
                }
                else{
                    return Json::encode(array('status' => false, 'hideModal' => true, 'message' => 'Thật xin lỗi! Phiếu tạo danh mục dược của bạn đã được chốt dữ liệu.'));
                }
            }
            else{
                return Json::encode(array('status' => false, 'hideModal' => false, 'message' => 'Bạn không có quyền sử dụng tính năng này hay can thiệp vào phiếu của người dùng khác! Liên hệ người quản trị để cấp quyền cho bạn hoặc đăng nhập với tài khoản khác!'));
            }
        }
        else{
            $this->layout= "main_error";
            return $this->render('//site/error',['message' => 'Thật xin lỗi! Thông tin mà bạn đang tìm kiếm hiện tại không tồn tại!']);
        }
    }

    public function actionFormvalidate($id) {
        if(isset($_SERVER['HTTP_REFERER']))
        {
            $model = new ListDvkt();
            $model->id_phieu = $id;
            if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
                \Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
        }
        else{
            $this->layout= "main_error";
            return $this->render('//site/error',['message' => 'Thật xin lỗi! Thông tin mà bạn đang tìm kiếm hiện tại không tồn tại!']);
        }
    }

    public function actionLoadphanloai() {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $id = end($_POST['depdrop_parents']);
            switch ($id) {
                case 0:
                    $out[] = ['id' => 1, 'name' => 'Thuốc thường'];
                    $out[] = ['id' => 2, 'name' => 'Chế phẩm'];
                    $out[] = ['id' => 3, 'name' => 'Vị thuốc'];
                    $out[] = ['id' => 4, 'name' => 'VTYT'];
                    $out[] = ['id' => 5, 'name' => 'Hóa chất'];
                    $selected = 1;
                    return ['output' => $out, 'selected' => $selected];
                    break;

                case 1:
                    $out[] = ['id' => 1, 'name' => 'Thuốc'];
                    $out[] = ['id' => 2, 'name' => 'VTYT'];
                    $selected = 1;
                    return ['output' => $out, 'selected' => $selected];
                    break;
                
                default:
                    return ['output' => '', 'selected' => ''];
                    break;
            }
        }
        return ['output' => '', 'selected' => ''];
    }

    public function actionLoadvitri($vitri='') {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $out[] = ['id' => 'A', 'name' => 'A'];
            $out[] = ['id' => 'B', 'name' => 'B'];
            $out[] = ['id' => 'C', 'name' => 'C'];
            $out[] = ['id' => 'D', 'name' => 'D'];
            $out[] = ['id' => 'E', 'name' => 'E'];
            $out[] = ['id' => 'F', 'name' => 'F'];
            $out[] = ['id' => 'G', 'name' => 'G'];
            $out[] = ['id' => 'H', 'name' => 'H'];
            $out[] = ['id' => 'I', 'name' => 'I'];
            $out[] = ['id' => 'J', 'name' => 'J'];
            $out[] = ['id' => 'K', 'name' => 'K'];
            $out[] = ['id' => 'L', 'name' => 'L'];
            $out[] = ['id' => 'M', 'name' => 'M'];
            $out[] = ['id' => 'N', 'name' => 'N'];
            $out[] = ['id' => 'O', 'name' => 'O'];
            $out[] = ['id' => 'P', 'name' => 'P'];
            $out[] = ['id' => 'Q', 'name' => 'Q'];
            $out[] = ['id' => 'R', 'name' => 'R'];
            $out[] = ['id' => 'S', 'name' => 'S'];
            $out[] = ['id' => 'T', 'name' => 'T'];
            $out[] = ['id' => 'U', 'name' => 'U'];
            $out[] = ['id' => 'V', 'name' => 'V'];
            $out[] = ['id' => 'W', 'name' => 'W'];
            $out[] = ['id' => 'X', 'name' => 'X'];
            $out[] = ['id' => 'Y', 'name' => 'Y'];
            $out[] = ['id' => 'Z', 'name' => 'Z'];
            $out[] = ['id' => 'AA', 'name' => 'AA'];
            $out[] = ['id' => 'AB', 'name' => 'AB'];
            $out[] = ['id' => 'AC', 'name' => 'AC'];
            $out[] = ['id' => 'AD', 'name' => 'AD'];
            $out[] = ['id' => 'AE', 'name' => 'AE'];
            $out[] = ['id' => 'AF', 'name' => 'AF'];
            $out[] = ['id' => 'AG', 'name' => 'AG'];
            $out[] = ['id' => 'AH', 'name' => 'AH'];
            $out[] = ['id' => 'AI', 'name' => 'AI'];
            $out[] = ['id' => 'AJ', 'name' => 'AJ'];
            $out[] = ['id' => 'AK', 'name' => 'AK'];
            $out[] = ['id' => 'AL', 'name' => 'AL'];
            $out[] = ['id' => 'AM', 'name' => 'AM'];
            $out[] = ['id' => 'AN', 'name' => 'AN'];
            $out[] = ['id' => 'AO', 'name' => 'AO'];
            $out[] = ['id' => 'AP', 'name' => 'AP'];
            $out[] = ['id' => 'AQ', 'name' => 'AQ'];
            $out[] = ['id' => 'AR', 'name' => 'AR'];
            $out[] = ['id' => 'AS', 'name' => 'AS'];
            $out[] = ['id' => 'AT', 'name' => 'AT'];
            $out[] = ['id' => 'AU', 'name' => 'AU'];
            $out[] = ['id' => 'AV', 'name' => 'AV'];
            $out[] = ['id' => 'AW', 'name' => 'AW'];
            $out[] = ['id' => 'AX', 'name' => 'AX'];
            $out[] = ['id' => 'AY', 'name' => 'AY'];
            $out[] = ['id' => 'AZ', 'name' => 'AZ'];

            $thanhtoan = $_POST['depdrop_parents'][0];
            $phanloai = $_POST['depdrop_parents'][1];
            //BHYT + Thuốc
            if($thanhtoan == 1 && $phanloai == 1){
                switch ($vitri) {
                    case 'mabc':
                        $selected = 'C';
                        return ['output' => $out, 'selected' => $selected];
                        break;
                    
                    case 'tenvt':
                        $selected = 'M';
                        return ['output' => $out, 'selected' => $selected];
                        break;
                    
                    case 'hoatchat':
                        $selected = 'E';
                        return ['output' => $out, 'selected' => $selected];
                        break;
                    
                    case 'hamluong':
                        $selected = 'K';
                        return ['output' => $out, 'selected' => $selected];
                        break;
                    
                    case 'duongdung':
                        $selected = 'G';
                        return ['output' => $out, 'selected' => $selected];
                        break;
                    
                    case 'donvitinh':
                        $selected = 'Q';
                        return ['output' => $out, 'selected' => $selected];
                        break;
                    
                    case 'dongia':
                        $selected = 'R';
                        return ['output' => $out, 'selected' => $selected];
                        break;
                    
                    case 'quycach':
                        $selected = 'P';
                        return ['output' => $out, 'selected' => $selected];
                        break;
                    
                    case 'sodangky':
                        $selected = 'O';
                        return ['output' => $out, 'selected' => $selected];
                        break;
                    
                    case 'nhasx':
                        $selected = 'V';
                        return ['output' => $out, 'selected' => $selected];
                        break;
                    
                    case 'nuocsx':
                        $selected = 'W';
                        return ['output' => $out, 'selected' => $selected];
                        break;
                    
                    case 'nhathau':
                        $selected = 'X';
                        return ['output' => $out, 'selected' => $selected];
                        break;
                    
                    case 'quyetdinh':
                        $selected = 'Y';
                        return ['output' => $out, 'selected' => $selected];
                        break;
                    
                    case 'congbo':
                        $selected = 'Z';
                        return ['output' => $out, 'selected' => $selected];
                        break;
                    
                    case 'loaithuoc':
                        $selected = 'AB';
                        return ['output' => $out, 'selected' => $selected];
                        break;
                    
                    case 'goithau':
                        $selected = 'AC';
                        return ['output' => $out, 'selected' => $selected];
                        break;
                    
                    case 'nhomthau':
                        $selected = 'AE';
                        return ['output' => $out, 'selected' => $selected];
                        break;
                    
                    default:
                        return ['output' => '', 'selected' => ''];
                        break;
                }
            }
            //BHYT + VTYT
            else if($thanhtoan == 1 && $phanloai == 2){
                switch ($vitri) {
                    case 'mabc':
                        $selected = 'C';
                        return ['output' => $out, 'selected' => $selected];
                        break;
                    
                    case 'tenvt':
                        $selected = 'H';
                        return ['output' => $out, 'selected' => $selected];
                        break;

                    case 'donvitinh':
                        $selected = 'L';
                        return ['output' => $out, 'selected' => $selected];
                        break;
                    
                    case 'dongia':
                        $selected = 'M';
                        return ['output' => $out, 'selected' => $selected];
                        break;
                    
                    case 'quycach':
                        $selected = 'I';
                        return ['output' => $out, 'selected' => $selected];
                        break;

                    case 'nhasx':
                        $selected = 'K';
                        return ['output' => $out, 'selected' => $selected];
                        break;
                    
                    case 'nuocsx':
                        $selected = 'J';
                        return ['output' => $out, 'selected' => $selected];
                        break;
                    
                    case 'nhathau':
                        $selected = 'O';
                        return ['output' => $out, 'selected' => $selected];
                        break;
                    
                    case 'quyetdinh':
                        $selected = 'P';
                        return ['output' => $out, 'selected' => $selected];
                        break;
                    
                    case 'congbo':
                        $selected = 'Q';
                        return ['output' => $out, 'selected' => $selected];
                        break;
                    
                    default:
                        return ['output' => '', 'selected' => ''];
                        break;
                }
            }
            //Viện phí
            else{
                switch ($vitri) {
                    case 'tenvt':
                        $selected = 'A';
                        return ['output' => $out, 'selected' => $selected];
                        break;
                    
                    case 'hoatchat':
                        $selected = 'B';
                        return ['output' => $out, 'selected' => $selected];
                        break;
                    
                    case 'hamluong':
                        $selected = 'C';
                        return ['output' => $out, 'selected' => $selected];
                        break;
                    
                    case 'duongdung':
                        $selected = 'D';
                        return ['output' => $out, 'selected' => $selected];
                        break;
                    
                    case 'donvitinh':
                        $selected = 'E';
                        return ['output' => $out, 'selected' => $selected];
                        break;
                    
                    case 'dongia':
                        $selected = 'F';
                        return ['output' => $out, 'selected' => $selected];
                        break;
                    
                    case 'quycach':
                        $selected = 'G';
                        return ['output' => $out, 'selected' => $selected];
                        break;

                    case 'nhasx':
                        $selected = 'H';
                        return ['output' => $out, 'selected' => $selected];
                        break;
                    
                    case 'nuocsx':
                        $selected = 'I';
                        return ['output' => $out, 'selected' => $selected];
                        break;
                    
                    case 'nhathau':
                        $selected = 'J';
                        return ['output' => $out, 'selected' => $selected];
                        break;
                    
                    case 'loaithuoc':
                        $selected = 'K';
                        return ['output' => $out, 'selected' => $selected];
                        break;
                    
                    default:
                        return ['output' => '', 'selected' => ''];
                        break;
                }
            }
        }
        return ['output' => '', 'selected' => ''];
    }

    public function actionUpdatenhasx() {
        if (isset($_POST['hasEditable']) && isset($_POST['mansx']) && isset($_POST['tennsx']) && isset($_POST['idphieu'])) {
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            $id = $_POST['mansx'];
            $ten = $_POST['tennsx'];
            $id_phieu = (int)$_POST['idphieu'];
            if(Yii::$app->user->can('dmduoc/updatenhasx', ['id_phieu' => $id_phieu])) {
                $model = new NhaSanXuat();
                $model->id = $id;
                $model->ten_nsx = $ten;
                $model->status = 1;
                if (!$model->validate() || !$model->save()) {
                    $errorArr = $model->getErrors();
                    $msg = "";
                    foreach($errorArr as $key=>$val){
                        foreach($val as $keychild=>$valchild){
                            if($msg != ""){
                                $msg.=' | ';
                            }
                            $msg.=$valchild;
                        }
                    }
                    return ['output'=>'', 'message'=> $msg];
                }
                else{
                    return ['output'=>$model->id, 'message'=>''];
                }
            }
            else{
                return ['output'=>'', 'message' => 'Bạn không có quyền sử dụng tính năng này hay can thiệp vào phiếu của người dùng khác! Liên hệ người quản trị để cấp quyền cho bạn hoặc đăng nhập với tài khoản khác!'];
            }
        }
        else{
            return Json::encode(['output'=>'', 'message'=>'Lỗi yêu cầu điều chỉnh!']);
        }
    }

    public function actionUpdatenuocsx() {
        if (isset($_POST['hasEditable']) && isset($_POST['mansx']) && isset($_POST['tennsx']) && isset($_POST['idphieu'])) {
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            $id = $_POST['mansx'];
            $ten = $_POST['tennsx'];
            $id_phieu = (int)$_POST['idphieu'];
            if(Yii::$app->user->can('dmduoc/updatenuocsx', ['id_phieu' => $id_phieu])) {
                $model = new NuocSanXuat();
                $model->id = $id;
                $model->ten_nsx = $ten;
                $model->status = 1;
                if (!$model->validate() || !$model->save()) {
                    $errorArr = $model->getErrors();
                    $msg = "";
                    foreach($errorArr as $key=>$val){
                        foreach($val as $keychild=>$valchild){
                            if($msg != ""){
                                $msg.=' | ';
                            }
                            $msg.=$valchild;
                        }
                    }
                    return ['output'=>'', 'message'=> $msg];
                }
                else{
                    return ['output'=>$model->id, 'message'=>''];
                }
            }
            else{
                return ['output'=>'', 'message' => 'Bạn không có quyền sử dụng tính năng này hay can thiệp vào phiếu của người dùng khác! Liên hệ người quản trị để cấp quyền cho bạn hoặc đăng nhập với tài khoản khác!'];
            }
        }
        else{
            return Json::encode(['output'=>'', 'message'=>'Lỗi yêu cầu điều chỉnh!']);
        }
    }public function actionUpdatemavt() {
        if (Yii::$app->request->post('hasEditable')) {
            Yii::$app->response->format = Response::FORMAT_JSON;
  
            $id_dmduoc = Yii::$app->request->post('editableKey');
            $out = [
                'output'    => '',
                'message'   => '',
            ];
            $dmduoc = DmDuoc::findOne($id_dmduoc);
            if($dmduoc && $dmduoc->duocticket->status == 0){
                if (Yii::$app->user->can('dmduoc/updatemavt', ['id_phieu' => $dmduoc->id_phieu])) {
                    $posted = current($_POST['DmDuoc']);
                    $post['DmDuoc'] = $posted;
                    if(array_key_exists("mavt",$post['DmDuoc'])){
                        if($dmduoc->mavt != $post['DmDuoc']["mavt"]){
                            $dmduoc->mavt = $post['DmDuoc']["mavt"];
                        }
                        if($dmduoc->validate() && $dmduoc->save()){
                            $output = $dmduoc->mavt;
                            $message = '';
                        }
                        else{
                            $errorArr = $dmduoc->getErrors();
                            $msg = "";
                            foreach($errorArr as $key=>$val){
                                foreach($val as $keychild=>$valchild){
                                    if($msg != ""){
                                        $msg.=' | ';
                                    }
                                    $msg.=$valchild;
                                }
                            }
                            return ['output'=>'', 'message'=> $msg];
                        }
                        $out = Json::encode(['output'=>$output, 'message' => $message]);
                    }
                    if(array_key_exists("manhom",$post['DmDuoc'])){
                        if($dmduoc->manhom != $post['DmDuoc']["manhom"]){
                            $dmduoc->manhom = $post['DmDuoc']["manhom"];
                        }
                        if($dmduoc->validate() && $dmduoc->save()){
                            $output = $dmduoc->manhom;
                            $message = '';
                        }
                        else{
                            $errorArr = $dmduoc->getErrors();
                            $msg = "";
                            foreach($errorArr as $key=>$val){
                                foreach($val as $keychild=>$valchild){
                                    if($msg != ""){
                                        $msg.=' | ';
                                    }
                                    $msg.=$valchild;
                                }
                            }
                            return ['output'=>'', 'message'=> $msg];
                        }
                        $out = Json::encode(['output'=>$output, 'message' => $message]);
                    }
                }
                else{
                    $out = Json::encode(['output'=>'', 'message' => 'Bạn không có quyền sử dụng tính năng này hay can thiệp vào phiếu của người dùng khác! Liên hệ người quản trị để cấp quyền cho bạn hoặc đăng nhập với tài khoản khác!']);
                }
            }
            else{
                $out = Json::encode(['output'=>'', 'message' => 'Thật xin lỗi! Chi tiết danh mục dược không tồn tại hoặc phiếu tạo danh mục dược của bạn đã được chốt dữ liệu.']);
            }
            echo $out;

            return;
        }
        else{
            return Json::encode(['output'=>'', 'message'=>'Lỗi yêu cầu điều chỉnh!']);
        }
    }
}
