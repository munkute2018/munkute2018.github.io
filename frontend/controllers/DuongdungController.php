<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\DuongDung;
use common\models\DuongDungSearch;
use common\models\FileSingleForm;
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
class DuongdungController extends Controller
{
    public function init()
    {
        parent::init();
        \Yii::$app->language = 'vi';
    }

    public function actionIndex(){
        if (Yii::$app->user->can('duongdung/index')) {
            $searchModel = new DuongDungSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
            
            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        }
        else{
            return $this->render('//site/errorrole',['message' => 'Liên hệ người quản trị để cấp quyền cho bạn hoặc đăng nhập với tài khoản khác!']);
        }
    }

    public function actionImport()
    {
        if(isset($_SERVER['HTTP_REFERER']))
        {
            if (Yii::$app->user->can('duongdung/import')) {
                $model = new FileSingleForm();
                if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {  
                    $model->file = UploadedFile::getInstance($model, 'file');           
                    if ($model->file && $model->validate()) {     
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
                        for($i = 2; $i <= $Totalrow; $i++){
                            $info = new DuongDung();
                            $info->id = (string)$sheetData->getCell('A'.$i)->getValue();
                            $info->mota = (string)$sheetData->getCell('B'.$i)->getValue();
                            $info->status = 1;
                            if (!$info->validate() || !$info->save()) {
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
                return Json::encode(array('status' => true, 'title' => 'Import đường dùng', 'data' => $this->renderAjax('_import', ['model' => $model])));
            }
            else{
                return Json::encode(array('status' => false, 'hideModal' => false, 'message' => 'Bạn không có quyền sử dụng tính năng này! Liên hệ người quản trị để cấp quyền cho bạn hoặc đăng nhập với tài khoản khác!'));
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
            $model = new FileSingleForm();
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

    public function actionCreate()
    {
        if(isset($_SERVER['HTTP_REFERER']))
        {
            if (Yii::$app->user->can('duongdung/create')) {
                $model = new DuongDung();
                if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
                    $transaction = \Yii::$app->db->beginTransaction();          
                    try {
                        if ($model->validate() && $model->save()) {   
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
                    return Json::encode(array('status' => true, 'title' => 'Thêm đường dùng', 'data' => $this->renderAjax('_create', ['model' => $model])));
                }
            }
            else{
                return Json::encode(array('status' => false, 'hideModal' => false, 'message' => 'Bạn không có quyền sử dụng tính năng này! Liên hệ người quản trị để cấp quyền cho bạn hoặc đăng nhập với tài khoản khác!'));
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
            if (Yii::$app->user->can('duongdung/update')) {
                $model = DuongDung::findOne($id);
                if($model){
                    if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
                        $transaction = \Yii::$app->db->beginTransaction();          
                        try {
                            if ($model->validate() && $model->save()) { 
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
                        return Json::encode(array('status' => true, 'title' => 'Sửa đường dùng', 'data' => $this->renderAjax('_update', ['model' => $model])));
                    }
                }
                else{
                    return Json::encode(array('status' => false, 'hideModal' => true, 'message' => 'Thật xin lỗi! Đường dùng này không còn tồn tại!'));
                }
            }
            else{
                return Json::encode(array('status' => false, 'hideModal' => false, 'message' => 'Bạn không có quyền sử dụng tính năng này! Liên hệ người quản trị để cấp quyền cho bạn hoặc đăng nhập với tài khoản khác!'));
            }
        }
        else{
            $this->layout= "main_user";
            return $this->render('error',['message' => 'Thật xin lỗi! Thông tin mà bạn đang tìm kiếm hiện tại không tồn tại!']);
        }
    }

    public function actionFormvalidate() {
        if(isset($_SERVER['HTTP_REFERER']))
        {
            $model = new DuongDung();
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
            $model = DuongDung::findOne($id);
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

    public function actionDelete($id=0)
    {
        if(isset($_SERVER['HTTP_REFERER']))
        {
            if (Yii::$app->user->can('duongdung/delete')) {
                $model = DuongDung::findOne($id);
                if($model){
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
                    return Json::encode(array('status' => false, 'hideModal' => true, 'message' => 'Thật xin lỗi! Đường dùng này không còn tồn tại!'));
                }
            }
            else{
                return Json::encode(array('status' => false, 'hideModal' => false, 'message' => 'Bạn không có quyền sử dụng tính năng này! Liên hệ người quản trị để cấp quyền cho bạn hoặc đăng nhập với tài khoản khác!'));
            }
        }
        else{
            $this->layout= "main_user";
            return $this->render('//site/error',['message' => 'Thật xin lỗi! Thông tin mà bạn đang tìm kiếm hiện tại không tồn tại!']);
        }
    }
}
