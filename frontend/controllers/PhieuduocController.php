<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\DuocTicket;
use common\models\DuocTicketSearch;
use yii\web\Response;
use yii\widgets\ActiveForm;
use yii\helpers\Json;
use common\models\ThuVien;

/**
 * Site controller
 */
class PhieuduocController extends Controller
{
    public function init()
    {
        parent::init();
        \Yii::$app->language = 'vi';
    }

    public function actionIndex(){
        if (Yii::$app->user->can('phieuduoc/index')) {
            $searchModel = new DuocTicketSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
            $searchModel->id_user = Yii::$app->user->getId();
            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        }
        else{
            return $this->render('//site/errorrole',['message' => 'Liên hệ người quản trị để cấp quyền cho bạn hoặc đăng nhập với tài khoản khác!']);
        }
    }

    public function actionCreate()
    {
        if(isset($_SERVER['HTTP_REFERER']))
        {
            if (Yii::$app->user->can('phieuduoc/create')) {
                $model = new DuocTicket();
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
                else{
                    return Json::encode(array('status' => true, 'title' => 'Thêm phiếu tạo file import DM dược', 'data' => $this->renderAjax('_create', ['model' => $model])));
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

    public function actionLock($id=0)
    {
        if(isset($_SERVER['HTTP_REFERER']))
        {
            if (Yii::$app->user->can('doigia/lock', ['id_hiswork' => $id])) {
                $model = DuocTicket::findOne(['id' => $id]);
                if (Yii::$app->request->isAjax) {
                    $model->status = Yii::$app->request->post()['status'];
                    $transaction = \Yii::$app->db->beginTransaction();          
                    try {
                        if ($model->save()) {   
                            $transaction->commit(); 
                            return Json::encode(array('status' => true, 'message' => 'Cập nhật thành công.'));
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
            $this->layout= "main_user";
            return $this->render('//site/error',['message' => 'Thật xin lỗi! Thông tin mà bạn đang tìm kiếm hiện tại không tồn tại!']);
        }
    }

    public function actionFormvalidate() {
        if(isset($_SERVER['HTTP_REFERER']))
        {
            $model = new DuocTicket();
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
            $model = DuocTicket::findOne(['id' => $id]);
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

    public function actionLoadchildvp() {
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
}
