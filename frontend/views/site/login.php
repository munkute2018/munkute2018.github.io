<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use common\models\LoginForm;
?>
<div class="login-box">
  <div class="login-logo">
    <b>VNPT</b> HIS</a>
  </div>
  <!-- /.login-logo -->
  <div class="login-box-body">
    <p class="login-box-msg">Đăng nhập vào trang quản trị</p>

    <?php
        $form = ActiveForm::begin([ 
            'id' => 'login-form', 
            'enableClientScript' => true
        ]); 
        ?>
        <div class="form-group has-feedback">
            <?= $form->field($model, 'username')->textInput(['class' => 'form-control', 'placeholder' => 'Nhập tài khoản của bạn', 'maxlength' => 50])->label(false) ?>
            <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
        </div> 
        <div class="form-group has-feedback">
            <?= $form->field($model, 'password')->passwordInput(['class' => 'form-control underlined', 'placeholder' => 'Nhập mật khẩu', 'maxlength' => 50])->label(false) ?>
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
        </div>
        <div class="row">
            <div class="col-xs-12">
              <?= Html::submitButton('Đăng nhập', ['class' => 'btn btn-primary btn-block btn-flat']) ?>
            </div>
        </div>
        
    <?php ActiveForm::end(); ?>
  </div>
  <!-- /.login-box-body -->
</div>
