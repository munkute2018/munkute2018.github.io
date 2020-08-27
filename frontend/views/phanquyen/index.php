<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use kartik\tree\TreeViewInput;
use common\models\Menu;
use common\models\Auth_item;
use kartik\select2\Select2;
$listquyen = Auth_item::find()->where(['flag' => 1, 'type' => 1])->orderBy(['description' => SORT_ASC])->all();
?>
<div class="content-wrapper">
    <section class="content-header">
        <p class="breadcrumb box-gridview">Phân quyền chức năng menu</p>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-xs-8 col-md-10">
                                <?php
                                    echo Select2::widget([
                                        'name' => 'group_user',
                                        'id' => 'group_user',
                                        'data' => ArrayHelper::map($listquyen,'name','description'),
                                        'options' => ['placeholder' => 'Chọn nhóm người dùng...'],
                                    ]);
                                ?>
                            </div>
                            <div class="col-xs-4 col-md-2">
                                <center>
                                    <?php echo Html::Button('<i class="glyphicon glyphicon-repeat"></i>', ['title' => 'Reset', 'class' => 'btn btn-default btn-reset-role']) ?>
                                    <?php echo Html::Button('<i class="glyphicon glyphicon-floppy-disk"></i>', ['title' => 'Cập nhật', 'class' => 'btn btn-skin btn-submit-role']) ?>
                                </center>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 role-zone">
                        
            </div>
        </div>
    </section>
</div>