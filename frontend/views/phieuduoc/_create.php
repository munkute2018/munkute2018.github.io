<?php
use common\models\ThuVien;
use yii\helpers\Url;
use yii\helpers\Html;
use common\models\DonVi;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
$listdonvi = DonVi::find()->where(['status' => 1])->orderBy(['madonvi' => SORT_ASC])->all();
?>
<section class="section">
    <?php $form = ActiveForm::begin(['id' => 'form-modal', 'enableClientValidation' => false, 'enableAjaxValidation' => true, 'validationUrl' => Yii::$app->urlManager->createUrl(['phieuduoc/formvalidate'])]); ?>
    <div class="row">
      <div class="col-xs-12 col-sm-6 col-md-6">
        <div class="card card-block">
          <div class="form-group">
            <label class="control-label">Phần mềm HIS</label>
            <?php
              echo $form->field($model, 'phienban')->widget(Select2::classname(), [
                  'data' => [0 => 'HIS L3', 1 => 'HIS L2'],
                  'options' => [$model->phienban => ['Selected'=>'selected']],
                  'class' => 'form-control',
                ])->label(false);
            ?>
          </div>
        </div>
      </div>
      <div class="col-xs-12 col-sm-6 col-md-6">
        <div class="card card-block">
          <div class="form-group">
            <label class="control-label">Ghi chú</label>
            <?php
              echo $form->field($model, 'ghichu')->textInput(['class' => 'form-control underlined', 'maxlength' => 255, 'autofocus' => 'autofocus', 'placeholder' => 'Nhập ghi chú...',])->label(false); 
            ?>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-xs-12 col-sm-6 col-md-6">
        <div class="card card-block">
          <div class="form-group">
            <label class="control-label">Đơn vị</label>
            <?php
              echo $form->field($model, 'id_donvi')->widget(Select2::classname(), [
                    'data' => ArrayHelper::map($listdonvi,'madonvi', function($data) {
                        return $data['madonvi'].' - '.$data['tendonvi'];
                    }),
                    'options' => [$model->id_donvi => ['Selected'=>'selected']],
                    'class' => 'form-control',
                  ])->label(false);
            ?>
          </div>
        </div>
      </div>
      <div class="col-xs-12 col-sm-6 col-md-6">
        <div class="card card-block">
          <div class="form-group">
            <label class="control-label">Nhân bản danh mục</label>
            <?php
              echo $form->field($model, 'saochep')->widget(Select2::classname(), [
                  'data' => [0 => 'Từ chối', 1 => 'Đồng ý'],
                  'options' => [$model->saochep => ['Selected'=>'selected']],
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