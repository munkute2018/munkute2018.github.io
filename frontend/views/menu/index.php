<?php
use yii\helpers\Url;
use kartik\tree\TreeView;
use common\models\Menu;
?>
<div class="content-wrapper">
    <section class="content-header">
        <p class="breadcrumb box-gridview">Danh sách menu chức năng</p>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-body">
                        <?php
                        echo TreeView::widget([
                            'query' => Menu::find()->addOrderBy('root, lft'), 
                            'headingOptions' => ['label' => 'Menu'],
                            'fontAwesome' => true,
                            'isAdmin' => true,
                            'displayValue' => 1,
                            'softDelete' => true,
                            'cacheSettings' => [        
                                'enableCache' => false
                            ],
                            'nodeAddlViews' => [
                                \kartik\tree\Module::VIEW_PART_2 => '@frontend/views/menu/_form',
                            ],
                        ]);
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>