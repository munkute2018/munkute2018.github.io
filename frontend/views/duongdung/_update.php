<?php
use common\models\ThuVien;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\number\NumberControl;
use kartik\select2\Select2;

$dispOptions = ['class' => 'form-control kv-monospace'];
 
$saveOptions = [
    'type' => 'hidden', 
    'label'=>'', 
    'class' => 'kv-saved',
    'readonly' => true, 
    'tabindex' => 1000,
    'display' => 'none'
];
 
$saveCont = ['class' => 'kv-saved-cont'];
?>
<section class="section">
    <?php $form = ActiveForm::begin(['id' => 'form-modal', 'enableClientValidation' => false, 'enableAjaxValidation' => true, 'validationUrl' => Yii::$app->urlManager->createUrl(['duongdung/formeditvalidate', 'id' => $model->id])]); ?>
    <div class="row">
      <div class="col-xs-12 col-sm-4 col-md-4">
        <div class="card card-block">
          <div class="form-group">
            <label class="control-label">Mã đường dùng</label>
              <?php echo $form->field($model, 'id')->textInput(['class' => 'form-control underlined', 'maxlength' => 255, 'autofocus' => 'autofocus', 'placeholder' => 'Nhập mã đường dùng...', 'disabled' => true])->label(false); ?>
          </div>
        </div>
      </div>
      <div class="col-xs-12 col-sm-4 col-md-4">
        <div class="card card-block">
          <div class="form-group">
            <label class="control-label">Tên đường dùng</label>
              <?php echo $form->field($model, 'mota')->textInput(['class' => 'form-control underlined', 'maxlength' => 255, 'autofocus' => 'autofocus', 'placeholder' => 'Nhập tên đường dùng...',])->label(false); ?>
          </div>
        </div>
      </div>
      <div class="col-xs-12 col-sm-4 col-md-4">
        <div class="card card-block">
          <div class="form-group">
            <label class="control-label">Trạng thái</label>
              <?php 
                echo $form->field($model, 'status')->widget(Select2::classname(), [
                  'data' => [1 => 'Hoạt động', 0 => 'Tạm ngưng'],
                  'options' => [$model->status => ['Selected'=>'selected']],
                  'class' => 'form-control',
                ])->label(false);
              ?>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="card card-block">
          <div class="form-group">
            <center><?php echo Html::submitButton('Lưu dữ liệu', ['class' => 'btn btn-skin btn-submit']) ?></center>
          </div>
        </div>
      </div>
    </div>
    <?php ActiveForm::end(); ?>
</section>