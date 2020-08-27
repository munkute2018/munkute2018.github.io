<?php
use common\models\ThuVien;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use common\models\HisWork;
use common\models\Dmthongtuhis;
use kartik\select2\Select2;
use kartik\depdrop\DepDrop;

$lst_tt = Dmthongtuhis::find()->where(['status' => 1])->andWhere(['OR', ['bhxh' => 1], ['bhxh' => 3]])->orderBy(['posted_at' => SORT_DESC])->all();
$lst_tt_kbh = Dmthongtuhis::find()->where(['status' => 1])->andWhere(['OR', ['bhxh' => 2], ['bhxh' => 3]])->orderBy(['posted_at' => SORT_DESC])->all();

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
    <?php $form = ActiveForm::begin(['id' => 'form-modal', 'enableClientValidation' => false, 'enableAjaxValidation' => true, 'validationUrl' => Yii::$app->urlManager->createUrl(['doigia/formvalidate'])]); ?>
    <div class="row">
      <div class="col-xs-12 col-sm-6 col-md-6">
        <div class="card card-block">
          <div class="form-group">
            <label class="control-label">Thông tư giá BHYT mới</label>
            <?php
              $arr= ['Không thay đổi'];
              $arr+= ArrayHelper::map($lst_tt,'id','name');
              echo $form->field($model, 'bhyt_new')->widget(Select2::classname(), [
                  'data' => $arr,
                  'options' => [$model->bhyt_new => ['Selected'=>'selected'], 'id'=>'bhyt_new'],
                  'class' => 'form-control',
                ])->label(false);
            ?>
          </div>
        </div>
      </div>
      <div class="col-xs-12 col-sm-6 col-md-6">
        <div class="card card-block">
          <div class="form-group">
            <label class="control-label">Thông tư giá BHYT cũ</label>
            <?php
              echo $form->field($model, 'bhyt_old')->widget(DepDrop::classname(), [
                  'data' => [],
                  'options' => ['placeholder' => 'Lựa chọn....'],
                  'type' => DepDrop::TYPE_SELECT2,
                  'select2Options' => ['pluginOptions' => ['allowClear' => true]],
                  'pluginOptions' => [
                      'depends' => ['bhyt_new'],
                      'url' => Url::to(['doigia/loadchildbhyt', 'vitri' => 'mabc']),
                      'loadingText' => 'Tải dữ liệu ...',
                  ]
              ])->label(false);
            ?>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-xs-12 col-sm-6 col-md-6">
        <div class="card card-block">
          <div class="form-group">
            <label class="control-label">Thông tư giá VP mới</label>
            <?php
              $arr_kbh= ['Không thay đổi'];
              $arr_kbh+= ArrayHelper::map($lst_tt_kbh,'id','name');
              echo $form->field($model, 'vp_new')->widget(Select2::classname(), [
                  'data' => $arr_kbh,
                  'options' => [$model->vp_new => ['Selected'=>'selected'], 'id'=>'vp_new'],
                  'class' => 'form-control',
                ])->label(false);
            ?>
          </div>
        </div>
      </div>
      <div class="col-xs-12 col-sm-6 col-md-6">
        <div class="card card-block">
          <div class="form-group">
            <label class="control-label">Thông tư viện phí cũ</label>
            <?php
              echo $form->field($model, 'vp_old')->widget(DepDrop::classname(), [
                  'data' => [],
                  'options' => ['placeholder' => 'Lựa chọn....'],
                  'type' => DepDrop::TYPE_SELECT2,
                  'select2Options' => ['pluginOptions' => ['allowClear' => true]],
                  'pluginOptions' => [
                      'depends' => ['vp_new'],
                      'url' => Url::to(['doigia/loadchildvp']),
                      'loadingText' => 'Tải dữ liệu ...',
                  ]
              ])->label(false);
            ?>
          </div>
        </div>
      </div>
    </div>
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