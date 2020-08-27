<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\Auth_item;
use common\models\Auth_item_child;
use common\models\Menu;
use yii\web\Response;
use yii\widgets\ActiveForm;
use yii\helpers\Json;

/**
 * Site controller
 */
class PhanquyenController extends Controller
{
    public function init()
    {
        parent::init();
        \Yii::$app->language = 'vi';
    }

    public function actionIndex(){
        if (Yii::$app->user->can('phanquyen/index')) {
            return $this->render('index');
        }
        else{
            return $this->render('//site/errorrole',['message' => 'Liên hệ người quản trị để cấp quyền cho bạn hoặc đăng nhập với tài khoản khác!']);
        }
    }

    public function actionLoadquyen(){
        if(isset($_SERVER['HTTP_REFERER'])){
            if (Yii::$app->user->can('phanquyen/loadquyen')) {
                if (Yii::$app->request->isAjax) {
                    $group_role = Yii::$app->request->post()['group'];
                    $checkrole = Auth_item::find()->where(['flag' => 1, 'type' => 1, 'name' => $group_role])->one();
                    if ($checkrole) {  
                        $list = Menu::find()->where('rgt - lft = 1 AND visible = 1')->select('tbl_menu.id')
                                ->innerJoin('auth_item_child', 'auth_item_child.child = tbl_menu.link AND flag = 1 AND auth_item_child.parent = "'.$group_role.'"')->select(['id'])->column();
                        $value = implode(',', $list);
                        return Json::encode(array('status' => true, 'data' => $this->renderAjax('_viewrole', ['value' => $value])));
                    } else {
                        return Json::encode(array('status' => false, 'message' => 'Không tồn tại nhóm quyền này!'));
                    }
                }
            }
            else{
                return Json::encode(array('status' => false, 'message' => 'Bạn không có quyền sử dụng tính năng này! Liên hệ người quản trị để cấp quyền cho bạn hoặc đăng nhập với tài khoản khác!'));
            }
        }
        else{
            $this->layout= "main_error";
            return $this->render('//site/error',['message' => 'Thật xin lỗi! Thông tin mà bạn đang tìm kiếm hiện tại không tồn tại!']);
        }
    }

    public function actionSavequyen(){
        if(isset($_SERVER['HTTP_REFERER'])){
            if (Yii::$app->user->can('phanquyen/savequyen')) {
                if (Yii::$app->request->isAjax) {
                    $group_role = Yii::$app->request->post()['group'];
                    $value = Yii::$app->request->post()['value'];
                    $checkrole = Auth_item::find()->where(['flag' => 1, 'type' => 1, 'name' => $group_role])->one();
                    if ($checkrole) {  
                        $transaction = \Yii::$app->db->beginTransaction(); 
                        $flag = true; 
                        try {
                            $delete = Auth_item_child::deleteAll(['AND', ['flag' => 1], ['parent' => $group_role]]);
                            $value = array_map('intval', explode(',', $value));
                            $lst_add_menu = Menu::find()->where('rgt - lft = 1')->andwhere(['in', 'id', $value])->all();
                            foreach ($lst_add_menu as $item_add) {
                                $model = new Auth_item_child();
                                $model->parent = $group_role;
                                $model->child = $item_add->link;
                                $model->flag = 1;
                                if(!$model->save())
                                    $flag = false;
                            }
                            if($flag){
                                $transaction->commit();
                                return Json::encode(array('status' => true, 'message' => 'Cập nhật thành công!'));
                            }
                            else{
                                $transaction->rollBack();
                                return Json::encode(array('status' => false, 'message' => 'Cập nhật không thành công! Vui lòng kiểm tra lại dữ liệu hoặc kết nối.'));
                            }
                        } catch (Exception $ex) {
                            $transaction->rollBack();
                            return Json::encode(array('status' => false, 'message' => 'Cập nhật không thành công! Vui lòng kiểm tra lại dữ liệu hoặc kết nối.'));
                        }
                    } else {
                        return Json::encode(array('status' => false, 'message' => 'Không tồn tại nhóm quyền này!'));
                    }
                }
            }
            else{
                return Json::encode(array('status' => false, 'message' => 'Bạn không có quyền sử dụng tính năng này! Liên hệ người quản trị để cấp quyền cho bạn hoặc đăng nhập với tài khoản khác!'));
            }
        }
        else{
            $this->layout= "main_error";
            return $this->render('//site/error',['message' => 'Thật xin lỗi! Thông tin mà bạn đang tìm kiếm hiện tại không tồn tại!']);
        }
    }
}
