<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\ListDvkt;
use common\models\HisWork;
use common\models\ListDvktSearch;
use common\models\FileDvktForm;
use common\models\OptionUser;
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
class ConvertController extends Controller
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
        if (Yii::$app->user->can('convert/index', ['id_hiswork' => $id])) {
            $searchModel = new ListDvktSearch();
            $searchModel->id_phieu = $id;
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
            
            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
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
            if (Yii::$app->user->can('convert/import', ['id_hiswork' => $id])) {
                $hiswork = HisWork::findOne($id);
                if($hiswork && $hiswork->status == 0){
                    $model = new FileDvktForm();
                    if($hiswork->phienban == 0){
                        $option = OptionUser::findOne(['status' => 1, 'id_user' => Yii::$app->user->getId()]);
                        if($option){
                            $model->dvtt = $option->col_dvtt;
                            $model->madv = $option->col_madv;
                            $model->mabc = $option->col_mabc;
                            $model->tendv = $option->col_tendv;
                            $model->giabhyt = $option->col_giabhyt;
                            $model->giavp = $option->col_giavp;
                        }
                    }
                    if(Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {  
                        $model->file = UploadedFile::getInstance($model, 'file');           
                        if ($model->file && $model->validate()) { 
                            //Lưu tham số người dùng
                            if($hiswork->phienban == 0){
                                $thamso = OptionUser::findOne(['status' => 1, 'id_user' => Yii::$app->user->getId()]);
                                if(!$thamso){
                                    $thamso = new OptionUser;
                                }
                                $thamso->status = 1;
                                $thamso->id_user = Yii::$app->user->getId();
                                $thamso->col_dvtt = $model->dvtt;
                                $thamso->col_madv = $model->madv;
                                $thamso->col_mabc = $model->mabc;
                                $thamso->col_tendv = $model->tendv;
                                $thamso->col_giabhyt = $model->giabhyt;
                                $thamso->col_giavp = $model->giavp;
                                $thamso->save();
                            }
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
                            $rowStart = $hiswork->phienban == 0 ? (ThuVien::getInfoThamSo('HIS_DOIGIA_ROW_IMPORT_L3', 2)) : (ThuVien::getInfoThamSo('HIS_DOIGIA_ROW_IMPORT_L2', 4));
                            for($i = $rowStart; $i <= $Totalrow; $i++){
                                $info = new ListDvkt();
                                $info->id_phieu = $id;
                                if($hiswork->phienban == 0){
                                    $info->id_donvi = (string)$sheetData->getCell($model->dvtt.$i)->getValue();
                                    $info->id_dichvu = $sheetData->getCell($model->madv.$i)->getValue();
                                    $info->ma_dichvu = (string)$sheetData->getCell($model->mabc.$i)->getValue();
                                    $info->ten_dichvu = (string)$sheetData->getCell($model->tendv.$i)->getValue();
                                    $info->gia_bhyt_old = $sheetData->getCell($model->giabhyt.$i)->getValue();
                                    $info->gia_vp_old = $sheetData->getCell($model->giavp.$i)->getValue();
                                }
                                else{
                                    $info->id_donvi = (string)$model->dvttl2;
                                    $info->id_dichvu = $sheetData->getCell('A'.$i)->getValue();
                                    $info->ma_dichvu = (string)$sheetData->getCell('B'.$i)->getValue();
                                    $info->ten_dichvu = (string)$sheetData->getCell('C'.$i)->getValue();
                                    $info->gia_bhyt_old = $sheetData->getCell('K'.$i)->getValue();
                                    $info->gia_vp_old = $sheetData->getCell('M'.$i)->getValue();
                                    //Các chi tiết còn lại
                                    $info->nhomdichvuid = (string)$sheetData->getCell('D'.$i)->getValue();
                                    $info->manhomdichvu = (string)$sheetData->getCell('E'.$i)->getValue();
                                    $info->nhom_mabhyt_id = (string)$sheetData->getCell('F'.$i)->getValue();
                                    $info->madmbyt = (string)$sheetData->getCell('G'.$i)->getValue();
                                    $info->khoanmucid = (string)$sheetData->getCell('H'.$i)->getValue();
                                    $info->nhomketoanid = (string)$sheetData->getCell('I'.$i)->getValue();
                                    $info->donvi = (string)$sheetData->getCell('J'.$i)->getValue();
                                    $info->giadichvu = (string)$sheetData->getCell('L'.$i)->getValue();
                                    $info->gianuocngoai = (string)$sheetData->getCell('N'.$i)->getValue();
                                    $info->quyetdinh = (string)$sheetData->getCell('O'.$i)->getValue();
                                    $info->ngaycongbo = (string)$sheetData->getCell('P'.$i)->getValue();
                                    $info->sttmau21 = (string)$sheetData->getCell('Q'.$i)->getValue();
                                    $info->mabytmau21 = (string)$sheetData->getCell('R'.$i)->getValue();
                                    $info->loaipttt = (string)$sheetData->getCell('S'.$i)->getValue();
                                    $info->matt37 = (string)$sheetData->getCell('T'.$i)->getValue();
                                    $info->matt4350 = (string)$sheetData->getCell('U'.$i)->getValue();
                                    $info->chuyenkhoaid = (string)$sheetData->getCell('V'.$i)->getValue();
                                    $info->tendichvubhyt = (string)$sheetData->getCell('W'.$i)->getValue();
                                    $info->khoa = (string)$sheetData->getCell('X'.$i)->getValue();
                                    $info->ghichu = (string)$sheetData->getCell('Y'.$i)->getValue();

                                }
                                if (!$info->validate() || !$info->saveThongTinGia()) {
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
                    return Json::encode(array('status' => true, 'title' => 'Import danh sách dịch vụ kỹ thuật', 'data' => $this->renderAjax('_import', ['model' => $model, 'phienban' => $hiswork->phienban])));
                }
                else{
                    return Json::encode(array('status' => false, 'hideModal' => true, 'message' => 'Phiếu chuyển đổi giá DVKT của bạn đã được chốt dữ liệu.'));
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
            $model = new FileDvktForm();
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

    public function actionCreate($id)
    {
        if(isset($_SERVER['HTTP_REFERER']))
        {
            if (Yii::$app->user->can('convert/create', ['id_hiswork' => $id])) {
                $hiswork = HisWork::findOne($id);
                if($hiswork && $hiswork->status == 0){
                    $model = new ListDvkt();
                    $model->id_phieu = $id;
                    if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
                        $transaction = \Yii::$app->db->beginTransaction();          
                        try {
                            if ($model->validate() && $model->saveThongTinGia()) {   
                                $transaction->commit(); 
                                return Json::encode(array('status' => true, 'message' => 'Đã thêm thành công.'));
                            } else {
                                $transaction->rollBack();
                                return Json::encode(array('status' => false, 'hideModal' => false, 'message' => 'Thêm không thành công! Vui lòng kiểm tra lại dữ liệu hoặc kết nối.'));
                            }
                        } catch (Exception $ex) {
                            $transaction->rollBack();
                            return Json::encode(array('status' => false, 'hideModal' => true, 'message' => 'Thêm không thành công! Vui lòng kiểm tra lại dữ liệu hoặc kết nối.'));
                        }
                    }
                    else{
                        return Json::encode(array('status' => true, 'title' => 'Thêm dữ liệu (Mã phiếu: p'.$id.')', 'data' => $this->renderAjax('_create', ['model' => $model, 'id_phieu' => $id])));
                    }
                }
                else{
                    return Json::encode(array('status' => false, 'hideModal' => true, 'message' => 'Phiếu chuyển đổi giá DVKT của bạn đã được chốt dữ liệu.'));
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

    public function actionUpdate($id=0)
    {
        if(isset($_SERVER['HTTP_REFERER']))
        {
            $model = ListDvkt::findOne(['id' => $id]);
            if($model && $model->hiswork->status == 0){
                if (Yii::$app->user->can('convert/update', ['id_hiswork' => $model->id_phieu])) {
                    if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
                        $transaction = \Yii::$app->db->beginTransaction();          
                        try {
                            if ($model->validate() && $model->saveThongTinGia()) {   
                                $transaction->commit(); 
                                return Json::encode(array('status' => true, 'message' => 'Đã cập nhật thành công.'));
                            } else {
                                $transaction->rollBack();
                                return Json::encode(array('status' => false, 'hideModal' => false, 'message' => 'Cập nhật không thành công! Vui lòng kiểm tra lại dữ liệu hoặc kết nối.'));
                            }
                        } catch (Exception $ex) {
                            $transaction->rollBack();
                            return Json::encode(array('status' => false, 'hideModal' => true, 'message' => 'Cập nhật không thành công! Vui lòng kiểm tra lại dữ liệu hoặc kết nối.'));
                        }
                    }
                    else{
                        return Json::encode(array('status' => true, 'title' => 'Sửa chi tiết dịch vụ chuyển đổi giá', 'data' => $this->renderAjax('_update', ['model' => $model])));
                    }
                }
                else{
                    return Json::encode(array('status' => false, 'hideModal' => false, 'message' => 'Bạn không có quyền sử dụng tính năng này hay can thiệp vào phiếu của người dùng khác! Liên hệ người quản trị để cấp quyền cho bạn hoặc đăng nhập với tài khoản khác!'));
                }
            }
            else{
                return Json::encode(array('status' => false, 'hideModal' => true, 'message' => 'Thật xin lỗi! Chi tiết dịch vụ hoặc phiếu chuyển đổi giá của bạn đã không còn tồn tại hoặc đã được chốt dữ liệu!'));
            }
        }
        else{
            $this->layout= "main_user";
            return $this->render('error',['message' => 'Thật xin lỗi! Thông tin mà bạn đang tìm kiếm hiện tại không tồn tại!']);
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

    public function actionFormeditvalidate($id) {
        if(isset($_SERVER['HTTP_REFERER']))
        {
            $model = ListDvkt::findOne($id);
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

    public function actionUpdategia() {
        if (Yii::$app->request->post('hasEditable')) {
            Yii::$app->response->format = Response::FORMAT_JSON;
  
            $id_listdvkt = Yii::$app->request->post('editableKey');
            $out = [
                'output'    => '',
                'message'   => '',
            ];
            $listdvkt = ListDvkt::findOne($id_listdvkt);
            if($listdvkt && $listdvkt->hiswork->status == 0){
                if (Yii::$app->user->can('convert/updategia', ['id_hiswork' => $listdvkt->id_phieu])) {
                    $posted = current($_POST['ListDvkt']);
                    $post['ListDvkt'] = $posted;
                    if(array_key_exists("gia_bhyt_new",$post['ListDvkt'])){
                        if($listdvkt->hiswork->bhyt_new != 0){
                            if($listdvkt->gia_bhyt_new != $post['ListDvkt']["gia_bhyt_new"]){
                                $listdvkt->gia_bhyt_new = $post['ListDvkt']["gia_bhyt_new"];
                                $listdvkt->tooltip_bhyt = 0;
                                $listdvkt->status = ThuVien::setStatusListDvkt($listdvkt->tooltip_bhyt, $listdvkt->tooltip_vp);
                            }
                            if($listdvkt->save()){
                                $output = Yii::$app->formatter->asDecimal($listdvkt->gia_bhyt_new, 0);
                                $message = '';
                            }
                            else{
                                $output = '';
                                $message = 'Có lỗi xảy ra! Vui lòng thử lại!';
                            }
                            $out = Json::encode(['output'=>$output, 'message' => $message]);
                        }
                        else{
                            $out = Json::encode(['output'=>'', 'message' => 'Phiếu này không thay đổi giá BHYT nên không thể chỉnh sửa cột giá BHYT bằng tay!']);
                        }
                    }

                    if(array_key_exists("gia_vp_new",$post['ListDvkt'])){
                        if($listdvkt->hiswork->vp_new != 0){
                            if($listdvkt->gia_vp_new != $post['ListDvkt']["gia_vp_new"]){
                                $listdvkt->gia_vp_new = $post['ListDvkt']["gia_vp_new"];
                                $listdvkt->tooltip_vp = 0;
                                $listdvkt->status = ThuVien::setStatusListDvkt($listdvkt->tooltip_bhyt, $listdvkt->tooltip_vp);
                            }
                            if($listdvkt->save()){
                                $output = Yii::$app->formatter->asDecimal($listdvkt->gia_vp_new, 0);
                                $message = '';
                            }
                            else{
                                $output = '';
                                $message = 'Có lỗi xảy ra! Vui lòng thử lại!';
                            }
                            $out = Json::encode(['output'=>$output, 'message' => $message]);
                        }
                        else{
                            $out = Json::encode(['output'=>'', 'message' => 'Phiếu này không thay đổi giá viện phí nên không thể chỉnh sửa cột giá viện phí bằng tay!']);
                        }
                    }
                }
                else{
                    $out = Json::encode(['output'=>'', 'message' => 'Bạn không có quyền sử dụng tính năng này hay can thiệp vào phiếu của người dùng khác! Liên hệ người quản trị để cấp quyền cho bạn hoặc đăng nhập với tài khoản khác!']);
                }
            }
            else{
                $out = Json::encode(['output'=>'', 'message' => 'Thật xin lỗi! Chi tiết dịch vụ hoặc phiếu chuyển đổi giá của bạn đã không còn tồn tại hoặc đã được chốt dữ liệu!']);
            }
            echo $out;

            return;
        }
        else{
            return Json::encode(['output'=>'', 'message'=>'Lỗi yêu cầu điều chỉnh!']);
        }
    }

    public function actionReload($id=0)
    {
        if(isset($_SERVER['HTTP_REFERER']))
        {
            $model = ListDvkt::findOne(['id' => $id]);
            if($model && $model->hiswork->status == 0){
                if (Yii::$app->user->can('convert/reload', ['id_hiswork' => $model->id_phieu])) {
                    if (Yii::$app->request->isAjax) {
                        $transaction = \Yii::$app->db->beginTransaction();          
                        try {
                            if ($model->validate() && $model->saveThongTinGia()) {   
                                $transaction->commit(); 
                                return Json::encode(array('status' => true, 'message' => 'Đã cập nhật thành công.'));
                            } else {
                                $transaction->rollBack();
                                return Json::encode(array('status' => false, 'hideModal' => false, 'message' => 'Cập nhật không thành công! Vui lòng kiểm tra lại dữ liệu hoặc kết nối.'));
                            }
                        } catch (Exception $ex) {
                            $transaction->rollBack();
                            return Json::encode(array('status' => false, 'hideModal' => true, 'message' => 'Cập nhật không thành công! Vui lòng kiểm tra lại dữ liệu hoặc kết nối.'));
                        }
                    }
                }
                else{
                    return Json::encode(array('status' => false, 'hideModal' => false, 'message' => 'Bạn không có quyền sử dụng tính năng này hay can thiệp vào phiếu của người dùng khác! Liên hệ người quản trị để cấp quyền cho bạn hoặc đăng nhập với tài khoản khác!'));
                }
            }
            else{
                return Json::encode(array('status' => false, 'hideModal' => false, 'message' => 'Thật xin lỗi! Chi tiết dịch vụ hoặc phiếu chuyển đổi giá của bạn đã không còn tồn tại hoặc đã được chốt dữ liệu!'));
            }
        }
        else{
            $this->layout= "main_user";
            return $this->render('//site/error',['message' => 'Thật xin lỗi! Thông tin mà bạn đang tìm kiếm hiện tại không tồn tại!']);
        }
    }

    public function actionReloadall($id=0)
    {
        if(isset($_SERVER['HTTP_REFERER']))
        {
            if (Yii::$app->user->can('convert/reloadall', ['id_hiswork' => $id])) {
                if (Yii::$app->request->isAjax) {
                    $value = Yii::$app->request->post()['value'];
                    $value = array_map('intval', $value);
                    $transaction = \Yii::$app->db->beginTransaction();          
                    try {
                        $flag = true;
                        $dem = 0;
                        foreach ($value as $item) {
                            $listdvkt = ListDvkt::findOne(['id' => $item, 'id_phieu' => $id]);
                            if($listdvkt && (!$listdvkt->hiswork->status == 0 || !Yii::$app->user->can('convert/reloadall', ['id_hiswork' => $listdvkt->id_phieu]) || !$listdvkt->validate() || !$listdvkt->saveThongTinGia())){
                                $flag = false;
                                break;
                            }
                            else if(!$listdvkt){
                                $dem++;
                            }
                        }
                        if($flag){
                            $transaction->commit(); 
                            if($dem>0){
                                return Json::encode(array('status' => true, 'message' => 'Đã cập nhật thành công. Trong đó có '.$dem.' dòng dữ liệu không tồn tại.'));
                            }
                            else{
                                return Json::encode(array('status' => true, 'message' => 'Đã cập nhật thành công.'));
                            }
                        }
                        else{
                            $transaction->rollBack();
                            return Json::encode(array('status' => false, 'hideModal' => false, 'message' => 'Thật xin lỗi! Tồn tại chi tiết dịch vụ bạn không thể cập nhật (phiếu đã chốt dữ liệu, dữ liệu không tồn tại hoặc bạn không có quyền can thiệp)'));
                        }
                    } catch (Exception $ex) {
                        $transaction->rollBack();
                        return Json::encode(array('status' => false, 'hideModal' => true, 'message' => 'Cập nhật không thành công! Vui lòng kiểm tra lại dữ liệu hoặc kết nối.'));
                    }
                }
            }
            else{
                return Json::encode(array('status' => false, 'hideModal' => false, 'message' => 'Bạn không có quyền sử dụng tính năng này hay can thiệp vào phiếu của người dùng khác! Liên hệ người quản trị để cấp quyền cho bạn hoặc đăng nhập với tài khoản khác!'));
            }
        }
        else{
            $this->layout= "main_user";
            return $this->render('//site/error',['message' => 'Thật xin lỗi! Thông tin mà bạn đang tìm kiếm hiện tại không tồn tại!']);
        }
    }

    public function actionDelete($id=0)
    {
        if(isset($_SERVER['HTTP_REFERER']))
        {
            $model = ListDvkt::findOne(['id' => $id]);
            if($model && $model->hiswork->status == 0){
                if (Yii::$app->user->can('convert/delete', ['id_hiswork' => $model->id_phieu])) {
                    if (Yii::$app->request->isAjax) {
                        $transaction = \Yii::$app->db->beginTransaction();          
                        try {
                            if ($model->delete()) {   
                                $transaction->commit(); 
                                return Json::encode(array('status' => true, 'message' => 'Đã xóa thành công.'));
                            } else {
                                $transaction->rollBack();
                                return Json::encode(array('status' => false, 'hideModal' => false, 'message' => 'Xóa không thành công! Vui lòng kiểm tra lại dữ liệu hoặc kết nối.'));
                            }
                        } catch (Exception $ex) {
                            $transaction->rollBack();
                            return Json::encode(array('status' => false, 'hideModal' => true, 'message' => 'Xóa không thành công! Vui lòng kiểm tra lại dữ liệu hoặc kết nối.'));
                        }
                    }
                }
                else{
                    return Json::encode(array('status' => false, 'hideModal' => false, 'message' => 'Bạn không có quyền sử dụng tính năng này hay can thiệp vào phiếu của người dùng khác! Liên hệ người quản trị để cấp quyền cho bạn hoặc đăng nhập với tài khoản khác!'));
                }
            }
            else{
                return Json::encode(array('status' => false, 'hideModal' => false, 'message' => 'Thật xin lỗi! Chi tiết dịch vụ hoặc phiếu chuyển đổi giá của bạn đã không còn tồn tại hoặc đã được chốt dữ liệu!'));
            }
        }
        else{
            $this->layout= "main_user";
            return $this->render('//site/error',['message' => 'Thật xin lỗi! Thông tin mà bạn đang tìm kiếm hiện tại không tồn tại!']);
        }
    }

    public function actionDeleteall($id=0)
    {
        if(isset($_SERVER['HTTP_REFERER']))
        {
            if (Yii::$app->user->can('convert/deleteall', ['id_hiswork' => $id])) {
                if (Yii::$app->request->isAjax) {
                    $value = Yii::$app->request->post()['value'];
                    $value = array_map('intval', $value);
                    $transaction = \Yii::$app->db->beginTransaction();          
                    try {
                        $flag = true;
                        $dem = 0;
                        foreach ($value as $item) {
                            $listdvkt = ListDvkt::findOne(['id' => $item, 'id_phieu' => $id]);
                            if($listdvkt && (!$listdvkt->hiswork->status == 0 || !Yii::$app->user->can('convert/deleteall', ['id_hiswork' => $listdvkt->id_phieu]) || !$listdvkt->delete())){
                                $flag = false;
                                break;
                            }
                            else if(!$listdvkt){
                                $dem++;
                            }
                        }
                        if($flag){
                            $transaction->commit(); 
                            if($dem>0){
                                return Json::encode(array('status' => true, 'message' => 'Xóa thành công. Trong đó có '.$dem.' dòng dữ liệu không tồn tại.'));
                            }
                            else{
                                return Json::encode(array('status' => true, 'message' => 'Xóa thành công.'));
                            }
                        }
                        else{
                            $transaction->rollBack();
                            return Json::encode(array('status' => false, 'hideModal' => false, 'message' => 'Thật xin lỗi! Tồn tại chi tiết dịch vụ bạn không thể cập nhật (phiếu đã chốt dữ liệu, dữ liệu không tồn tại hoặc bạn không có quyền can thiệp)'));
                        }
                    } catch (Exception $ex) {
                        $transaction->rollBack();
                        return Json::encode(array('status' => false, 'hideModal' => true, 'message' => 'Cập nhật không thành công! Vui lòng kiểm tra lại dữ liệu hoặc kết nối.'));
                    }
                }
            }
            else{
                return Json::encode(array('status' => false, 'hideModal' => false, 'message' => 'Bạn không có quyền sử dụng tính năng này hay can thiệp vào phiếu của người dùng khác! Liên hệ người quản trị để cấp quyền cho bạn hoặc đăng nhập với tài khoản khác!'));
            }
        }
        else{
            $this->layout= "main_user";
            return $this->render('//site/error',['message' => 'Thật xin lỗi! Thông tin mà bạn đang tìm kiếm hiện tại không tồn tại!']);
        }
    }
}
