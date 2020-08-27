<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta name="description" content="">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="robots" content="all,follow">
    <meta name="googlebot" content="index,follow,snippet,archive">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="keywords" content="">
    <link rel="shortcut icon" href="/vnpt/img/core-img/favicon.ico" type="image/x-icon" />
    <title>VNPT HIS - Trung tâm CNTT</title>
    <meta name="csrf-param" content="_csrf">
    <meta name="csrf-token" content="ZkfkKaHqlAiGlT1Hdimq5U7ZywaRFJvozDAMKBEWZWcicotd5KDzV-zfdgs9f9qOGZKIa_5dxKmlfWR5W3JcIQ==">
    <link href="/vnpt/bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="/vnpt/bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet">
<link href="/vnpt/bower_components/Ionicons/css/ionicons.min.css" rel="stylesheet">
<link href="/vnpt/dist/css/AdminLTE.css" rel="stylesheet">
<link href="/vnpt/dist/css/skins/_all-skins.css" rel="stylesheet">
<link href="/vnpt/css/toastr.min.css" rel="stylesheet">
<script src="/vnpt/assets/4a6eac16/vendor.js"></script></head>

<body class="hold-transition login-page">
        <div class="login-box">
  <div class="login-logo">
    <b>VNPT</b> HIS</a>
  </div>
  <!-- /.login-logo -->
  <div class="login-box-body">
    <p class="login-box-msg">Đăng nhập vào trang quản trị</p>

    <form id="login-form" action="/vnpt/dang-nhap" method="post">
<input type="hidden" name="_csrf" value="ZkfkKaHqlAiGlT1Hdimq5U7ZywaRFJvozDAMKBEWZWcicotd5KDzV-zfdgs9f9qOGZKIa_5dxKmlfWR5W3JcIQ==">        <div class="form-group has-feedback">
            <div class="form-group field-loginform-username required">

<input type="text" id="loginform-username" class="form-control" name="LoginForm[username]" maxlength="50" placeholder="Nhập tài khoản của bạn" aria-required="true">

<p class="help-block help-block-error"></p>
</div>            <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
        </div> 
        <div class="form-group has-feedback">
            <div class="form-group field-loginform-password required">

<input type="password" id="loginform-password" class="form-control underlined" name="LoginForm[password]" maxlength="50" placeholder="Nhập mật khẩu" aria-required="true">

<p class="help-block help-block-error"></p>
</div>            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
        </div>
        <div class="row">
            <div class="col-xs-12">
              <button type="submit" class="btn btn-primary btn-block btn-flat">Đăng nhập</button>            </div>
        </div>
        
    </form>  </div>
  <!-- /.login-box-body -->
</div>
    <script src="/vnpt/assets/2f9d9950/yii.js"></script>
<script src="/vnpt/assets/2f9d9950/yii.validation.js"></script>
<script src="/vnpt/assets/2f9d9950/yii.activeForm.js"></script>
<script src="/vnpt/bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<script src="/vnpt/dist/js/adminlte.js"></script>
<script src="/vnpt/dist/js/demo.js"></script>
<script src="/vnpt/dist/js/toastr.min.js"></script>
<script src="/vnpt/dist/js/jquery.blockUI.js"></script>
<script src="/vnpt/js/action.js"></script>
<script>jQuery(function ($) {
jQuery('#login-form').yiiActiveForm([{"id":"loginform-username","name":"username","container":".field-loginform-username","input":"#loginform-username","error":".help-block.help-block-error","validate":function (attribute, value, messages, deferred, $form) {value = yii.validation.trim($form, attribute, []);yii.validation.string(value, messages, {"message":"My Login phải là chuỗi.","max":50,"tooLong":"My Login phải chứa nhiều nhất 50 ký tự.","skipOnEmpty":1});yii.validation.required(value, messages, {"message":"Tài khoản không được để trống"});}},{"id":"loginform-password","name":"password","container":".field-loginform-password","input":"#loginform-password","error":".help-block.help-block-error","validate":function (attribute, value, messages, deferred, $form) {yii.validation.required(value, messages, {"message":"Mật khẩu không được để trống"});}}], []);
});</script>    <script>
      $(function () {
        $('input').iCheck({
          checkboxClass: 'icheckbox_square-blue',
          radioClass: 'iradio_square-blue',
          increaseArea: '20%' /* optional */
        });
      });
    </script>
</body>
</html>
