<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
use kartik\select2\Select2;
?>

<?php
$js = <<<JS
    $("#pjaxFilter").on("pjax:end", function() {
        $.pjax.reload({container:"#theDatatable"});  //Reload GridView
    });
JS;
$this->registerJs($js, yii\web\View::POS_READY);
?>

<div class="orders-search">
    <?php
        Pjax::begin([
            'id'=>'pjaxFilter',
        ]);
    ?>
    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => true,
        ],
    ]); ?>

    <?= $form->field($model, 'phienban')->widget(Select2::classname(), [
        'data' => [0 => 'HIS L3', 1 => 'HIS L2'],
        'options' => [$model->phienban => ['Selected'=>'selected']],
        'class' => 'form-control',
    ])->label(false);?>


    <div class="form-group">
        <?= Html::submitButton('Искать', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Сбросить', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

    <?php
        Pjax::end();
    ?>
</div>