<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\DuongDung;
use common\models\ThamSo_DonVi;
use common\models\CauhinhSearch;
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
class CauhinhController extends Controller
{
    public function init()
    {
        parent::init();
        \Yii::$app->language = 'vi';
    }

    public function actionIndex(){
        if (Yii::$app->user->can('cauhinh/index')) {
            $searchModel = new CauhinhSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
            if (isset($_POST['hasEditable'])) {
                \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                $donvi = $_POST['DonVi']['madonvi'];
                $thamso = $_POST['CauhinhSearch']['id_thamso'];
                $giatri = $_POST['giatri'];
                if(!$model = ThamSo_DonVi::findOne(['id_thamso' => $thamso, 'id_donvi' => $donvi]))
                    $model = new ThamSo_DonVi;
                $model->id_donvi = $donvi;
                $model->id_thamso = $thamso;
                $model->giatri = $giatri;
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
                    return ['output'=>$model->giatri, 'message'=>''];
                }
            }
            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        }
        else{
            return $this->render('//site/errorrole',['message' => 'Liên hệ người quản trị để cấp quyền cho bạn hoặc đăng nhập với tài khoản khác!']);
        }
    }

    public function actionUpdateall()
    {
        if(isset($_SERVER['HTTP_REFERER']))
        {
            if (Yii::$app->user->can('cauhinh/updateall')) {
                if (Yii::$app->request->isAjax) {
                    $list = Yii::$app->request->post()['list'];
                    $value = array_map('intval', $list);
                    $id_thamso = Yii::$app->request->post()['id_thamso'];
                    $giatri = Yii::$app->request->post()['fgiatri'];
                    $transaction = \Yii::$app->db->beginTransaction();          
                    try {
                        $flag = true;
                        $msg = '';
                        foreach ($value as $item) {
                            if(!$model = ThamSo_DonVi::findOne(['id_thamso' => $id_thamso, 'id_donvi' => $item]))
                                $model = new ThamSo_DonVi;
                            $model->id_donvi = (string)$item;
                            $model->id_thamso = $id_thamso;
                            $model->giatri = $giatri;
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
                                $flag = false;
                                break;
                            }
                        }
                        if($flag){
                            $transaction->commit(); 
                            return Json::encode(array('status' => true, 'message' => 'Đã cập nhật thành công.'));
                        }
                        else{
                            $transaction->rollBack();
                            return Json::encode(array('status' => false, 'hideModal' => false, 'message' => 'Cập nhật không thành công! '.$msg));
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
