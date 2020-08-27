<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\ThongTuHisSearch;
use common\models\HisWorkSearch;
use common\models\DmthongtuhisForm;
use common\models\Dmthongtuhis;
use common\models\HisWork;
use common\models\BangGiaSearch;
use common\models\ListDvkt;
use common\models\ListDvktSearch;
use common\models\DmbanggiaForm;
use common\models\FileSingleForm;
use common\models\FileDvktForm;
use common\models\Dmbanggia;
use common\models\OptionUser;
use yii\web\Response;
use yii\widgets\ActiveForm;
use yii\helpers\Json;
use yii\web\UploadedFile;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use common\models\ThuVien;

/**
 * Site controller
 */
class HisController extends Controller
{
    public function init()
    {
        parent::init();
        \Yii::$app->language = 'vi';
    }
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function beforeAction($action)
    {            
        if ($action->id == 'dmthongtuhisvalidate' || $action->id == 'themdmthongtu' || $action->id == 'dmbanggiavalidate' || $action->id == 'themdmbanggia' || $action->id == 'importdmbanggiavalidate' || $action->id == 'importbanggia' || $action->id == 'dmhisworkvalidate' || $action->id == 'themdmhiswork' || $action->id == 'importlistdvktvalidate' || $action->id == 'importlistdvkt') {
            Yii::$app->controller->enableCsrfValidation = false;
        }
        return parent::beforeAction($action);
    }
    
    //Chức năng quản lý giá dịch vụ theo từng thông tư
    public function actionDmthongtu()
    {
        if(!Yii::$app->user->getIsGuest()){
            $searchModel = new ThongTuHisSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
            
            return $this->render('dmthongtu', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        }
        else{
            return $this->goHome();
        }
    }

    public function actionThemdmthongtu()
    {
        if(isset($_SERVER['HTTP_REFERER']))
        {
            if(!Yii::$app->user->getIsGuest()) { 
                $model = new Dmthongtuhis();
                $model->id_user = Yii::$app->user->getId();
                if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
                    $transaction = \Yii::$app->db->beginTransaction();          
                    try {
                        if ($model->validate() && $model->saveThongTu()) {   
                            $transaction->commit(); 
                            return Json::encode(array('status' => true, 'message' => 'Đã thêm thành công.'));
                        } 
                        else {
                            $transaction->rollBack();
                            return Json::encode(array('status' => false, 'hideModal' => false, 'message' => 'Thêm không thành công! Vui lòng kiểm tra lại dữ liệu hoặc kết nối.'));
                        }
                    } catch (Exception $ex) {
                        $transaction->rollBack();
                        return Json::encode(array('status' => false, 'hideModal' => true, 'message' => 'Thêm không thành công! Vui lòng kiểm tra lại dữ liệu hoặc kết nối.'));
                    }
                }
                return $this->renderAjax('_themdmthongtuhis', ['model' => $model]);
            }
            else{
                return $this->goHome();
            }
        }
        else{
            $this->layout= "main_error";
            return $this->render('//site/error',['message' => 'Thật xin lỗi! Thông tin mà bạn đang tìm kiếm hiện tại không tồn tại!']);
        }
    }

    public function actionDmthongtuhisvalidate() {
        if(isset($_SERVER['HTTP_REFERER']))
        {
            $model = new Dmthongtuhis();
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

    public function actionViewbanggia($id){
        if(!Yii::$app->user->getIsGuest()){
            $exist = Dmthongtuhis::find()->where(['id' => $id])->exists();
            if($exist){
                $searchModel = new BangGiaSearch();
                $searchModel->id_thongtu = $id;
                $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
                
                return $this->render('dmbanggia', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'id_thongtu' => $id,
                ]);
            }
            else{
                $this->layout= "main_error";
                return $this->render('//site/error',['message' => 'Thật xin lỗi! Thông tin mà bạn đang tìm kiếm hiện tại không tồn tại!']);
            }
        }
        else{
            return $this->goHome();
        }
    }

    public function actionThemdmbanggia($id)
    {
        $exist = Dmthongtuhis::find()->where(['id' => $id])->exists();
        if(isset($_SERVER['HTTP_REFERER']) && $exist)
        {
            if(!Yii::$app->user->getIsGuest()) { 
                $model = new Dmbanggia();
                $model->id_thongtu = $id;
                $model->id_user = Yii::$app->user->getId();
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
                return $this->renderAjax('_themdmbanggia', ['model' => $model, 'id_thongtu' => $id]);
            }
            else{
                return $this->goHome();
            }
        }
        else{
            $this->layout= "main_error";
            return $this->render('//site/error',['message' => 'Thật xin lỗi! Thông tin mà bạn đang tìm kiếm hiện tại không tồn tại!']);
        }
    }

    public function actionDmbanggiavalidate() {
        if(isset($_SERVER['HTTP_REFERER']))
        {
            $model = new Dmbanggia();
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

    public function actionImportbanggia($id)
    {
        $exist = Dmthongtuhis::find()->where(['id' => $id])->exists();
        if(isset($_SERVER['HTTP_REFERER']) && $exist)
        {
            if(!Yii::$app->user->getIsGuest()) { 
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
                            $info = new Dmbanggia();
                            $info->id_thongtu = $id;
                            $info->id_user = Yii::$app->user->getId();
                            $info->stt = $sheetData->getCell('A'.$i)->getValue();
                            $info->type = $sheetData->getCell('B'.$i)->getValue();
                            $info->name = (string)$sheetData->getCell('C'.$i)->getValue();
                            $info->dongia = $sheetData->getCell('D'.$i)->getValue();
                            $info->ghichu = (string)$sheetData->getCell('E'.$i)->getValue();
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
                return $this->renderAjax('_importdmbanggia', ['model' => $model]);
            }
            else{
                return $this->goHome();
            }
        }
        else{
            $this->layout= "main_error";
            return $this->render('//site/error',['message' => 'Thật xin lỗi! Thông tin mà bạn đang tìm kiếm hiện tại không tồn tại!']);
        }
    }

    public function actionImportdmbanggiavalidate() {
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

    //Chức năng quản lý giá dịch vụ theo từng thông tư
    public function actionHiswork()
    {
        if(!Yii::$app->user->getIsGuest()){
            $searchModel = new HisWorkSearch();
            $searchModel->id_user = Yii::$app->user->getId();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
            
            return $this->render('hiswork', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        }
        else{
            return $this->goHome();
        }
    }

    public function actionThemdmhiswork()
    {
        if(isset($_SERVER['HTTP_REFERER']))
        {
            if(!Yii::$app->user->getIsGuest()) { 
                $model = new HisWork();
                $model->id_user = Yii::$app->user->getId();
                $model->status = 1;
                if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
                    $transaction = \Yii::$app->db->beginTransaction();          
                    try {
                        if ($model->validate() && $model->save()) {   
                            $transaction->commit(); 
                            return Json::encode(array('status' => true, 'message' => 'Đã thêm thành công.'));
                        } 
                        else {
                            $transaction->rollBack();
                            return Json::encode(array('status' => false, 'hideModal' => false, 'message' => 'Thêm không thành công! Vui lòng kiểm tra lại dữ liệu hoặc kết nối.'));
                        }
                    } catch (Exception $ex) {
                        $transaction->rollBack();
                        return Json::encode(array('status' => false, 'hideModal' => true, 'message' => 'Thêm không thành công! Vui lòng kiểm tra lại dữ liệu hoặc kết nối.'));
                    }
                }
                return $this->renderAjax('_themdmhiswork', ['model' => $model]);
            }
            else{
                return $this->goHome();
            }
        }
        else{
            $this->layout= "main_error";
            return $this->render('//site/error',['message' => 'Thật xin lỗi! Thông tin mà bạn đang tìm kiếm hiện tại không tồn tại!']);
        }
    }

    public function actionDmhisworkvalidate() {
        if(isset($_SERVER['HTTP_REFERER']))
        {
            $model = new HisWork();
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

    public function actionLoadchildbhyt() {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $id = end($_POST['depdrop_parents']);
            if($id != 0){
                $list = Dmthongtuhis::find()->where(['status' => 1])->andWhere(['OR', ['bhxh' => 1], ['bhxh' => 3]])->andWhere(['<>', 'id', $id])->orderBy(['posted_at' => SORT_DESC])->all();
            }
            else{
                $list = null;
            }
            $selected  = null;
            if ($id != null && count($list) > 0) {
                $selected = '';
                foreach ($list as $i => $model) {
                    $out[] = ['id' => $model['id'], 'name' => $model['name']];
                    if ($i == 0) {
                        $selected = $model['id'];
                    }
                }
                return ['output' => $out, 'selected' => $selected];
            }
        }
        return ['output' => '', 'selected' => ''];
    }

    public function actionLoadchilvp() {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $id = end($_POST['depdrop_parents']);
            if($id != 0){
                $list = Dmthongtuhis::find()->where(['status' => 1])->andWhere(['OR', ['bhxh' => 2], ['bhxh' => 3]])->andWhere(['<>', 'id', $id])->orderBy(['posted_at' => SORT_DESC])->all();
            }
            else{
                $list = null;
            }
            $selected  = null;
            if ($id != null && count($list) > 0) {
                $selected = '';
                foreach ($list as $i => $model) {
                    $out[] = ['id' => $model['id'], 'name' => $model['name']];
                    if ($i == 0) {
                        $selected = $model['id'];
                    }
                }
                return ['output' => $out, 'selected' => $selected];
            }
        }
        return ['output' => '', 'selected' => ''];
    }

    public function actionViewhiswork($id=0){
        if(!Yii::$app->user->getIsGuest()){
            $exist = HisWork::find()->where(['id' => $id, 'id_user' => Yii::$app->user->getId()])->exists();
            if($exist){
                $searchModel = new ListDvktSearch();
                $searchModel->id_phieu = $id;
                $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
                
                return $this->render('dmlistdvkt', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'id_phieu' => $id,
                ]);
            }
            else{
                $this->layout= "main_error";
                return $this->render('//site/error',['message' => 'Thật xin lỗi! Thông tin mà bạn đang tìm kiếm hiện tại không tồn tại!']);
            }
        }
        else{
            return $this->goHome();
        }
    }

    public function actionImportlistdvkt($id)
    {
        if(isset($_SERVER['HTTP_REFERER']))
        {
            if(!Yii::$app->user->getIsGuest()) { 
                $exist = HisWork::find()->where(['id' => $id, 'id_user' => Yii::$app->user->getId(), 'status' => 1])->exists();
                if($exist){
                    $model = new FileDvktForm();
                    $option = OptionUser::findOne(['status' => 1, 'id_user' => Yii::$app->user->getId()]);
                    if($option){
                        $model->dvtt = $option->col_dvtt;
                        $model->madv = $option->col_madv;
                        $model->mabc = $option->col_mabc;
                        $model->tendv = $option->col_tendv;
                        $model->giabhyt = $option->col_giabhyt;
                        $model->giavp = $option->col_giavp;
                    }
                    if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {  
                        $model->file = UploadedFile::getInstance($model, 'file');           
                        if ($model->file && $model->validate()) {
                            //Lưu tham số người dùng
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
                            for($i = 2; $i <= $Totalrow; $i++){
                                $info = new ListDvkt();
                                $info->id_phieu = $id;
                                $info->id_user = Yii::$app->user->getId();
                                $info->id_donvi = $sheetData->getCell($model->dvtt.$i)->getValue();
                                $info->id_dichvu = $sheetData->getCell($model->madv.$i)->getValue();
                                $info->ma_dichvu = (string)$sheetData->getCell($model->mabc.$i)->getValue();
                                $info->ten_dichvu = (string)$sheetData->getCell($model->tendv.$i)->getValue();
                                $info->gia_bhyt_old = $sheetData->getCell($model->giabhyt.$i)->getValue();
                                $info->gia_vp_old = $sheetData->getCell($model->giavp.$i)->getValue();
                                if (!$info->validate()) {
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
                                else{
                                    if(!$flagErr){
                                        $info_phieu = HisWork::findOne(['id' => $id, 'id_user' => Yii::$app->user->getId(), 'status' => 1]);
                                        if($info_phieu){
                                            $bhytnew = ThuVien::covertDonGia($info_phieu->bhyt_new, $info_phieu->bhyt_old, $info->id_donvi, $info->ma_dichvu, $info->ten_dichvu, $info->gia_bhyt_old);
                                            $info->gia_bhyt_new = $bhytnew["dongia"];

                                            $vpnew = ThuVien::covertDonGia($info_phieu->vp_new, $info_phieu->vp_old, $info->id_donvi, $info->ma_dichvu, $info->ten_dichvu, $info->gia_vp_old);
                                            $info->gia_vp_new = $vpnew["dongia"];
                                            $tooltip = "";
                                            if($bhytnew["status"] == 2 || $vpnew["status"] == 2){
                                                $info->status = 2;
                                                if($bhytnew["tooltip"] != ""){
                                                    $tooltip.= "BHYT: ".$bhytnew["tooltip"];
                                                }
                                                if($vpnew["tooltip"] != ""){
                                                    if($tooltip != "")
                                                        $tooltip.=" | ";
                                                    $tooltip.= "VP: ".$vpnew["tooltip"];
                                                }
                                                $info->tooltip = $tooltip;
                                            }
                                            else{
                                                $info->status = 1;
                                                $info->tooltip = "Hoàn thành";
                                            }
                                            if(!$info->save()){
                                                $flagErr = true;
                                            }
                                        }
                                        else{
                                            $flagErr = true;
                                        }
                                    }
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
                    return $this->renderAjax('_importlistdvkt', ['model' => $model]);
                }
                else{
                    $this->layout= "main_error";
                    return $this->render('//site/error',['message' => 'Thật xin lỗi! Thông tin mà bạn đang tìm kiếm hiện tại không tồn tại!']);
                }
            }
            else{
                return $this->goHome();
            }
        }
        else{
            $this->layout= "main_error";
            return $this->render('//site/error',['message' => 'Thật xin lỗi! Thông tin mà bạn đang tìm kiếm hiện tại không tồn tại!']);
        }
    }

    public function actionImportlistdvktvalidate() {
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
}
