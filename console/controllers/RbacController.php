<?php
namespace console\controllers;

use Yii;
use yii\console\Controller;

class RbacController extends Controller
{
	public function actionInit()
	{
		$auth = Yii::$app->authManager;
        //$auth->removeAll();  

        $rule = new \console\controllers\PhieuduocRule;
        $auth->add($rule);
        /*
        // Add quyền
        $view_lisdvkt = $auth->createPermission('listdvkt/view');
        $view_lisdvkt->description = 'Xem danh sách DVKT chuyển đổi giá';
        $view_lisdvkt->ruleName = $rule->name;
        $auth->add($view_lisdvkt);

        // Add quyền
        $index_menu = $auth->createPermission('menu/index');
        $index_menu->description = 'Danh sách menu chức năng';
        $auth->add($index_menu);

        // Add quyền
        $index_menu2 = $auth->createPermission('menu/manage');
        $index_menu2->description = 'Danh sách menu chức năng';
        $auth->add($index_menu2);
 
        // Add nhóm quyền
        $roleRegistered = $auth->createRole('admin');  
        $roleRegistered->description = 'Quyền admin';
        $auth->add($roleRegistered);

        //Add quyền vào nhóm quyền
        $auth->addChild($roleRegistered, $view_lisdvkt);  
        $auth->addChild($roleRegistered, $index_menu); 
        $auth->addChild($roleRegistered, $index_menu2); 
        
        //Gán user vào nhóm quyền
        $auth->assign($roleRegistered, 1);*/
    }
}
?>