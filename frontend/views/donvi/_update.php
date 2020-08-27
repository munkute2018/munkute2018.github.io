<?php
use common\models\ThuVien;
use common\models\Huyen;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\number\NumberControl;
use kartik\select2\Select2;
$listhuyen = Huyen::find()->where(['status' => 1])->orderBy(['huyen' => SORT_ASC])->all();

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
    <?php $form = ActiveForm::begin(['id' => 'form-modal', 'enableClientValidation' => false, 'enableAjaxValidation' => true, 'validationUrl' => Yii::$app->urlManager->createUrl(['donvi/formeditvalidate', 'id' => $model->madonvi])]); ?>
    <div class="row">
      <div class="col-xs-12 col-sm-4 col-md-4">
        <div class="card card-block">
          <div class="form-group">
            <label class="control-label">Mã đơn vị</label>
              <?php 
                echo NumberControl::widget([
                  'name' => 'madonvi',
                  'value' => $model->madonvi,
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
      <div class="col-xs-12 col-sm-4 col-md-4">
        <div class="card card-block">
          <div class="form-group">
            <label class="control-label">Tuyến đơn vị</label>
              <?php 
                echo $form->field($model, 'tuyen')->widget(Select2::classname(), [
                  'data' => [1 => 'Tuyến 1', 2 => 'Tuyến 2', 3 => 'Tuyến 3', 4 => 'Tuyến 4', 5 => 'Chưa phân tuyến'],
                  'options' => [$model->tuyen => ['Selected'=>'selected']],
                  'class' => 'form-control',
                ])->label(false);
              ?>
          </div>
        </div>
      </div>
      <div class="col-xs-12 col-sm-4 col-md-4">
        <div class="card card-block">
          <div class="form-group">
            <label class="control-label">Hạng đơn vị</label>
              <?php 
                echo $form->field($model, 'hang')->widget(Select2::classname(), [
                  'data' => [0 => 'Hạng đặc biệt', 1 => 'Hạng 1', 2 => 'Hạng 2', 3 => 'Hạng 3', 4 => 'Hạng 4', 5 => 'Chưa phân hạng'],
                  'options' => [$model->hang => ['Selected'=>'selected']],
                  'class' => 'form-control',
                ])->label(false);
              ?>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="card card-block">
          <div class="form-group">
            <label class="control-label">Tên đơn vị</label>
              <?php echo $form->field($model, 'tendonvi')->textInput(['class' => 'form-control underlined', 'maxlength' => 255, 'autofocus' => 'autofocus', 'placeholder' => 'Nhập tên đơn vị...',])->label(false); ?>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-xs-12 col-sm-4 col-md-4">
        <div class="card card-block">
          <div class="form-group">
            <label class="control-label">Đơn vị quản lý</label>
              <?php 
                echo $form->field($model, 'id_parent')->widget(NumberControl::classname(), [
                    'maskedInputOptions' => [
                        'groupSeparator' => '',
                        'digits' => 0,
                        'rightAlign' => false,
                        'min' => 10000,
                        'max' => 99999
                    ],
                    'options' => $saveOptions,
                    'displayOptions' => $dispOptions + [
                        'placeholder' => 'Nhập mã đơn vị quản lý...',
                        'maxlength' => 5
                    ],
                    'saveInputContainer' => $saveCont
                ])->label(false);
              ?>
          </div>
        </div>
      </div>
      <div class="col-xs-12 col-sm-4 col-md-4">
        <div class="card card-block">
          <div class="form-group">
            <label class="control-label">Huyện</label>
              <?php 
                echo $form->field($model, 'id_huyen')->widget(Select2::classname(), [
                  'data' => ArrayHelper::map($listhuyen,'id','huyen'),
                  'options' => [$model->id_huyen => ['Selected'=>'selected']],
                  'class' => 'form-control',
                ])->label(false);
              ?>
          </div>
        </div>
      </div>
      <div class="col-xs-12 col-sm-4 col-md-4">
        <div class="card card-block">
          <div class="form-group">
            <label class="control-label">Phần mềm HIS</label>
              <?php 
                echo $form->field($model, 'phienban')->widget(Select2::classname(), [
                  'data' => [0 => 'HIS L3', 1 => 'HIS L2', 2 => 'Phần mềm khác'],
                  'options' => [$model->phienban => ['Selected'=>'selected']],
                  'class' => 'form-control',
                ])->label(false);
              ?>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      
      <div class="col-xs-12 col-sm-4 col-md-4">
        <div class="card card-block">
          <div class="form-group">
            <label class="control-label">Số điện thoại</label>
              <?php 
                echo $form->field($model, 'phone')->textInput(['class' => 'form-control underlined', 'maxlength' => 11, 'autofocus' => 'autofocus', 'placeholder' => 'Nhập số điện thoại...',])->label(false);
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