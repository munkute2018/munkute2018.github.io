<?php
use common\models\ThuVien;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use common\models\Dmbangia;
use kartik\number\NumberControl;
use kartik\date\DatePicker;

$flag_phienban = ThuVien::checkIsL2ByIDPhieu($id_phieu);

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
    <?php $form = ActiveForm::begin(['id' => 'form-modal', 'enableClientValidation' => false, 'enableAjaxValidation' => true, 'validationUrl' => Yii::$app->urlManager->createUrl(['convert/formvalidate', 'id' => $id_phieu])]); ?>
    <div class="row">
      <div class="col-xs-12 col-sm-6 col-md-6">
        <div class="card card-block">
          <div class="form-group">
            <label class="control-label">Mã đơn vị</label>
              <?php 
                echo $form->field($model, 'id_donvi')->widget(NumberControl::classname(), [
                    'maskedInputOptions' => [
                        'groupSeparator' => '',
                        'digits' => 0,
                        'rightAlign' => false,
                        'min' => 10000,
                        'max' => 99999
                    ],
                    'options' => $saveOptions,
                    'displayOptions' => $dispOptions + [
                        'placeholder' => 'Nhập mã đơn vị...',
                        'maxlength' => 5
                    ],
                    'saveInputContainer' => $saveCont
                ])->label(false);
              ?>
          </div>
        </div>
      </div>
      <div class="col-xs-12 col-sm-6 col-md-6">
        <div class="card card-block">
          <div class="form-group">
            <label class="control-label">Mã dịch vụ</label>
              <?php 
                echo $form->field($model, 'id_dichvu')->widget(NumberControl::classname(), [
                    'maskedInputOptions' => [
                        'groupSeparator' => '',
                        'digits' => 0,
                        'rightAlign' => false,
                        'min' => 1,
                        'max' => 999999
                    ],
                    'options' => $saveOptions,
                    'displayOptions' => $dispOptions + [
                        'placeholder' => 'Nhập mã dịch vụ...',
                        'maxlength' => 5
                    ],
                    'saveInputContainer' => $saveCont
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
            <label class="control-label">Mã báo cáo</label>
              <?php echo $form->field($model, 'ma_dichvu')->textInput(['class' => 'form-control underlined', 'maxlength' => 15, 'autofocus' => 'autofocus', 'placeholder' => 'Nhập mã báo cáo...',])->label(false); ?>
          </div>
        </div>
      </div>
      <div class="col-xs-12 col-sm-6 col-md-6">
        <div class="card card-block">
          <div class="form-group">
            <label class="control-label">Tên dịch vụ</label>
              <?php echo $form->field($model, 'ten_dichvu')->textInput(['class' => 'form-control underlined', 'maxlength' => 255, 'autofocus' => 'autofocus', 'placeholder' => 'Nhập tên dịch vụ...',])->label(false); ?>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-xs-12 col-sm-6 col-md-6">
        <div class="card card-block">
          <div class="form-group">
            <label class="control-label">Giá BHYT</label>
              <?php 
                echo $form->field($model, 'gia_bhyt_old')->widget(NumberControl::classname(), [
                    'maskedInputOptions' => [
                        'groupSeparator' => '',
                        'digits' => 0,
                        'rightAlign' => false,
                        'min' => 0,
                        'max' => 999999999
                    ],
                    'options' => $saveOptions,
                    'displayOptions' => $dispOptions + [
                        'placeholder' => 'Nhập giá BHYT...',
                        'maxlength' => 9
                    ],
                    'saveInputContainer' => $saveCont
                ])->label(false);
              ?>
          </div>
        </div>
      </div>
      <div class="col-xs-12 col-sm-6 col-md-6">
        <div class="card card-block">
          <div class="form-group">
            <label class="control-label">Giá viện phí</label>
              <?php 
                echo $form->field($model, 'gia_vp_old')->widget(NumberControl::classname(), [
                    'maskedInputOptions' => [
                        'groupSeparator' => '',
                        'digits' => 0,
                        'rightAlign' => false,
                        'min' => 0,
                        'max' => 999999999
                    ],
                    'options' => $saveOptions,
                    'displayOptions' => $dispOptions + [
                        'placeholder' => 'Nhập giá viện phí...',
                        'maxlength' => 9
                    ],
                    'saveInputContainer' => $saveCont
                ])->label(false);
              ?>
          </div>
        </div>
      </div>
    </div>
    <?php
      if($flag_phienban){
    ?>
      <div class="row">
        <div class="col-xs-12 col-sm-6 col-md-6">
          <div class="card card-block">
            <div class="form-group">
              <label class="control-label">ID nhóm dịch vụ</label>
                <?php echo $form->field($model, 'nhomdichvuid')->textInput(['class' => 'form-control underlined', 'maxlength' => 255, 'autofocus' => 'autofocus', 'placeholder' => 'Nhập ID nhóm dịch vụ...',])->label(false); ?>
            </div>
          </div>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-6">
          <div class="card card-block">
            <div class="form-group">
              <label class="control-label">Mã nhóm dịch vụ</label>
                <?php echo $form->field($model, 'manhomdichvu')->textInput(['class' => 'form-control underlined', 'maxlength' => 255, 'autofocus' => 'autofocus', 'placeholder' => 'Nhập mã nhóm dịch vụ...',])->label(false); ?>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-xs-12 col-sm-6 col-md-6">
          <div class="card card-block">
            <div class="form-group">
              <label class="control-label">ID nhóm mã BHYT</label>
                <?php echo $form->field($model, 'nhom_mabhyt_id')->textInput(['class' => 'form-control underlined', 'maxlength' => 255, 'autofocus' => 'autofocus', 'placeholder' => 'Nhập ID nhóm mã BHYT...',])->label(false); ?>
            </div>
          </div>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-6">
          <div class="card card-block">
            <div class="form-group">
              <label class="control-label">Mã danh mục BYT</label>
                <?php echo $form->field($model, 'madmbyt')->textInput(['class' => 'form-control underlined', 'maxlength' => 255, 'autofocus' => 'autofocus', 'placeholder' => 'Nhập mã danh mục BYT...',])->label(false); ?>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-xs-12 col-sm-6 col-md-6">
          <div class="card card-block">
            <div class="form-group">
              <label class="control-label">ID khoản mức</label>
                <?php echo $form->field($model, 'khoanmucid')->textInput(['class' => 'form-control underlined', 'maxlength' => 255, 'autofocus' => 'autofocus', 'placeholder' => 'Nhập ID khoản mức...',])->label(false); ?>
            </div>
          </div>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-6">
          <div class="card card-block">
            <div class="form-group">
              <label class="control-label">ID nhóm kế toán</label>
                <?php echo $form->field($model, 'nhomketoanid')->textInput(['class' => 'form-control underlined', 'maxlength' => 255, 'autofocus' => 'autofocus', 'placeholder' => 'Nhập ID nhóm kế toán...',])->label(false); ?>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-xs-12 col-sm-6 col-md-6">
          <div class="card card-block">
            <div class="form-group">
              <label class="control-label">Đơn vị tính</label>
                <?php echo $form->field($model, 'donvi')->textInput(['class' => 'form-control underlined', 'maxlength' => 255, 'autofocus' => 'autofocus', 'placeholder' => 'Nhập đơn vị tính...',])->label(false); ?>
            </div>
          </div>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-6">
          <div class="card card-block">
            <div class="form-group">
              <label class="control-label">Giá dịch vụ</label>
                <?php 
                  echo $form->field($model, 'giadichvu')->widget(NumberControl::classname(), [
                    'maskedInputOptions' => [
                        'groupSeparator' => '',
                        'digits' => 0,
                        'rightAlign' => false,
                        'min' => 0,
                        'max' => 999999999
                    ],
                    'options' => $saveOptions,
                    'displayOptions' => $dispOptions + [
                        'placeholder' => 'Nhập giá dịch vụ...',
                        'maxlength' => 9
                    ],
                    'saveInputContainer' => $saveCont
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
              <label class="control-label">Giá nước ngoài</label>
                <?php 
                  echo $form->field($model, 'gianuocngoai')->widget(NumberControl::classname(), [
                    'maskedInputOptions' => [
                        'groupSeparator' => '',
                        'digits' => 0,
                        'rightAlign' => false,
                        'min' => 0,
                        'max' => 999999999
                    ],
                    'options' => $saveOptions,
                    'displayOptions' => $dispOptions + [
                        'placeholder' => 'Nhập giá nước ngoài...',
                        'maxlength' => 9
                    ],
                    'saveInputContainer' => $saveCont
                  ])->label(false);
                ?>
            </div>
          </div>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-6">
          <div class="card card-block">
            <div class="form-group">
              <label class="control-label">Quyết định</label>
                <?php echo $form->field($model, 'quyetdinh')->textInput(['class' => 'form-control underlined', 'maxlength' => 255, 'autofocus' => 'autofocus', 'placeholder' => 'Nhập quyết định...',])->label(false); ?>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-xs-12 col-sm-6 col-md-6">
          <div class="card card-block">
            <div class="form-group">
              <label class="control-label">Ngày công bố</label>
                <?php 
                echo $form->field($model, 'ngaycongbo')->widget(DatePicker::classname(), [
                    'options' => ['placeholder' => 'Nhập ngày công bố...'],
                    'pluginOptions' => [
                        'autoclose'=>true
                    ]
                ])->label(false);
              ?>
            </div>
          </div>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-6">
          <div class="card card-block">
            <div class="form-group">
              <label class="control-label">STT mẫu 21</label>
                <?php echo $form->field($model, 'sttmau21')->textInput(['class' => 'form-control underlined', 'maxlength' => 255, 'autofocus' => 'autofocus', 'placeholder' => 'Nhập STT mẫu 21...',])->label(false); ?>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-xs-12 col-sm-6 col-md-6">
          <div class="card card-block">
            <div class="form-group">
              <label class="control-label">Mã BYT mẫu 21</label>
                <?php echo $form->field($model, 'mabytmau21')->textInput(['class' => 'form-control underlined', 'maxlength' => 255, 'autofocus' => 'autofocus', 'placeholder' => 'Nhập mã byt mẫu 21...',])->label(false); ?>
            </div>
          </div>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-6">
          <div class="card card-block">
            <div class="form-group">
              <label class="control-label">Loại PTTT</label>
                <?php echo $form->field($model, 'loaipttt')->textInput(['class' => 'form-control underlined', 'maxlength' => 255, 'autofocus' => 'autofocus', 'placeholder' => 'Nhập loại PTTT...',])->label(false); ?>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-xs-12 col-sm-6 col-md-6">
          <div class="card card-block">
            <div class="form-group">
              <label class="control-label">Mã TT37</label>
                <?php echo $form->field($model, 'matt37')->textInput(['class' => 'form-control underlined', 'maxlength' => 255, 'autofocus' => 'autofocus', 'placeholder' => 'Nhập mã TT37...',])->label(false); ?>
            </div>
          </div>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-6">
          <div class="card card-block">
            <div class="form-group">
              <label class="control-label">Mã TT4350</label>
                <?php echo $form->field($model, 'matt4350')->textInput(['class' => 'form-control underlined', 'maxlength' => 255, 'autofocus' => 'autofocus', 'placeholder' => 'Nhập mã TT4350...',])->label(false); ?>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-xs-12 col-sm-6 col-md-6">
          <div class="card card-block">
            <div class="form-group">
              <label class="control-label">ID chuyên khoa</label>
                <?php echo $form->field($model, 'chuyenkhoaid')->textInput(['class' => 'form-control underlined', 'maxlength' => 255, 'autofocus' => 'autofocus', 'placeholder' => 'Nhập ID chuyên khoa...',])->label(false); ?>
            </div>
          </div>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-6">
          <div class="card card-block">
            <div class="form-group">
              <label class="control-label">Tên dịch vụ BHYT</label>
                <?php echo $form->field($model, 'tendichvubhyt')->textInput(['class' => 'form-control underlined', 'maxlength' => 255, 'autofocus' => 'autofocus', 'placeholder' => 'Nhập tên dịch vụ BHYT...',])->label(false); ?>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-xs-12 col-sm-6 col-md-6">
          <div class="card card-block">
            <div class="form-group">
              <label class="control-label">Mã khoa BYT</label>
                <?php echo $form->field($model, 'khoa')->textInput(['class' => 'form-control underlined', 'maxlength' => 255, 'autofocus' => 'autofocus', 'placeholder' => 'Nhập mã khoa BYT...',])->label(false); ?>
            </div>
          </div>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-6">
          <div class="card card-block">
            <div class="form-group">
              <label class="control-label">Ghi chú</label>
                <?php echo $form->field($model, 'ghichu')->textInput(['class' => 'form-control underlined', 'maxlength' => 255, 'autofocus' => 'autofocus', 'placeholder' => 'Nhập ghi chú...',])->label(false); ?>
            </div>
          </div>
        </div>
      </div>
    <?php
      }
    ?>
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