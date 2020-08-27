<?php
namespace frontend\controllers;

use Closure;
use Exception;
use kartik\tree\Module;
use kartik\tree\models\Tree;
use kartik\tree\TreeView;
use kartik\tree\TreeSecurity;
use Yii;
use yii\base\ErrorException;
use yii\base\Event;
use yii\base\InvalidCallException;
use yii\base\InvalidConfigException;
use yii\base\NotSupportedException;
use yii\db\Exception as DbException;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\Response;
use yii\web\View;

/**
 * Site controller
 */
class MenuController extends Controller
{
    public function init()
    {
        parent::init();
        \Yii::$app->language = 'vi';
    }

    public function actionIndex()
    {
        if (Yii::$app->user->can('phanquyen/index')) {
            return $this->render('index');
        }
        else{
            return $this->render('//site/errorrole',['message' => 'Liên hệ người quản trị để cấp quyền cho bạn hoặc đăng nhập với tài khoản khác!']);
        }
    }
}
