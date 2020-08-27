<?php
use kartik\tree\TreeViewInput;
use common\models\Menu;
use yii\helpers\ArrayHelper;
?>
<div class="box">
    <div class="box-body">
<?php
        echo TreeViewInput::widget([
            // single query fetch to render the tree
            'query'             => Menu::find()->addOrderBy('root, lft'), 
            'headingOptions'    => ['label' => 'Menu'],
            'name'              => 'role-menu',    // input name
            'value'             => $value,         // values selected (comma separated for multiple select)
            'asDropdown'        => false,            // will render the tree input widget as a dropdown.
            'multiple'          => true,            // set to false if you do not need multiple selection
            'fontAwesome'       => true,            // render font awesome icons
            'rootOptions'       => [
                'label' => 'Gốc', 
                'class'=>'text-success'
            ],
            'treeOptions' => ['style' => 'height: 450px']
        ]);
?>
    </div>
</div>