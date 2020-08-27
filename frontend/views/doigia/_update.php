<?php
use common\models\ThuVien;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\number\NumberControl;
use kartik\select2\Select2;
use kartik\date\DatePicker;

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
    <?php $form = ActiveForm::begin(['id' => 'form-modal', 'enableClientValidation' => false, 'enableAjaxValidation' => true, 'validationUrl' => Yii::$app->urlManager->createUrl(['quyetdinh/formeditvalidate', 'id' => $model->id])]); ?>
    <div class="row">
      <div class="col-xs-12 col-sm-4 col-md-4">
        <div class="card card-block">
          <div class="form-group">
            <label class="control-label">Mã quyết định</label>
              <?php 
                echo NumberControl::widget([
                  'name' => 'id',
                  'value' => $model->id,
                  'maskedInputOptions' => [
                    'groupSeparator' => '',
                    'digits' => 0,
                    'rightAlign' => false,
                  ],
                  'disabled' => true,
                  'displayOptions' => $dispOptions
                ]);
              ?>
          </div>
        </div>
      </div>
      <div class="col-xs-12 col-sm-8 col-md-8">
        <div class="card card-block">
          <div class="form-group">
            <label class="control-label">Tiêu đề</label>
              <?php 
                echo $form->field($model, 'name')->textInput(['class' => 'form-control underlined', 'maxlength' => 100, 'autofocus' => 'autofocus', 'placeholder' => 'Nhập tiêu đề quyết định...',])->label(false);
              ?>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-xs-12 col-sm-4 col-md-4">
        <div class="card card-block">
          <div class="form-group">
            <label class="control-label">Loại hình áp dụng</label>
              <?php
                echo $form->field($model, 'bhxh')->widget(Select2::classname(), [
                  'data' => [1 => 'Giá BHYT', 2 => 'Giá viện phí', 3 => 'Giá BHYT & Viện phí'],
                  'options' => [$model->bhxh => ['Selected'=>'selected']],
                  'class' => 'form-control',
                ])->label(false);
              ?>
          </div>
        </div>
      </div>
      <div class="col-xs-12 col-sm-4 col-md-4">
        <div class="card card-block">
          <div class="form-group">
            <label class="control-label">Ngày áp dụng</label>
              <?php echo $form->field($model, 'posted')->widget(DatePicker::classname(), [
                    'options' => ['placeholder' => 'Nhập ngày áp dụng'],
                    'pluginOptions' => [
                        'autoclose'=>true
                    ]
                ])->label(false);
              ?>
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