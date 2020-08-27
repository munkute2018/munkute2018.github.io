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
    <?php $form = ActiveForm::begin(['id' => 'form-modal', 'enableClientValidation' => false, 'enableAjaxValidation' => true, 'validationUrl' => Yii::$app->urlManager->createUrl(['banggia/formeditvalidate', 'id' => $model->id])]); ?>
    <div class="row">
      <div class="col-xs-12 col-sm-6 col-md-6">
        <div class="card card-block">
          <div class="form-group">
            <label class="control-label">Mã TT37</label>
              <?php 
                echo $form->field($model, 'stt')->widget(NumberControl::classname(), [
                    'maskedInputOptions' => [
                        'groupSeparator' => '',
                        'digits' => 0,
                        'rightAlign' => false,
                        'min' => 1,
                        'max' => 9999
                    ],
                    'options' => $saveOptions,
                    'displayOptions' => $dispOptions + [
                        'placeholder' => 'Nhập mã TT37...',
                        'maxlength' => 4
                    ],
                    'saveInputContainer' => $saveCont
                ])->label(false) 
              ?>
          </div>
        </div>
      </div>
      <div class="col-xs-12 col-sm-6 col-md-6">
        <div class="card card-block">
          <div class="form-group">
            <label class="control-label">Nội dung</label>
              <?php echo $form->field($model, 'name')->textInput(['class' => 'form-control underlined', 'maxlength' => 255, 'autofocus' => 'autofocus', 'placeholder' => 'Nhập nội dung...',])->label(false) ?>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-xs-12 col-sm-6 col-md-6">
        <div class="card card-block">
          <div class="form-group">
            <label class="control-label">Loại</label>
              <?php
                echo $form->field($model, 'type')->widget(Select2::classname(), [
                  'data' => [1 => 'Công khám', 2=> "PTTT-VLTL", 3 => "Tiền giường"],
                  'options' => [$model->type => ['Selected'=>'selected']],
                  'class' => 'form-control',
                ])->label(false);
              ?>
          </div>
        </div>
      </div>
      <div class="col-xs-12 col-sm-6 col-md-6">
        <div class="card card-block">
          <div class="form-group">
            <label class="control-label">Đơn giá</label>
              <?php 
                echo $form->field($model, 'dongia')->widget(NumberControl::classname(), [
                    'maskedInputOptions' => [
                        'digits' => 0,
                        'rightAlign' => false,
                        'min' => 0,
                        'max' => 999999999
                    ],
                    'options' => $saveOptions,
                    'displayOptions' => $dispOptions + [
                        'placeholder' => 'Nhập đơn giá dịch vụ...',
                        'maxlength' => 11
                    ],
                    'saveInputContainer' => $saveCont
                ])->label(false) 
              ?>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-xs-12 col-sm-6 col-md-6">
        <div class="card card-block">
          <div class="form-group">
            <label class="control-label">Ghi chú</label>
              <?php echo $form->field($model, 'ghichu')->textInput(['class' => 'form-control underlined', 'maxlength' => 255, 'autofocus' => 'autofocus', 'placeholder' => 'Nhập ghi chú...',])->label(false) ?>
          </div>
        </div>
      </div>
      <div class="col-xs-12 col-sm-6 col-md-6">
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