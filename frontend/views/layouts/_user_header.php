<?php
use yii\helpers\Url;
use common\models\ThuVien;
?>
<!-- User Account: style can be found in dropdown.less -->
<li class="dropdown user user-menu">
  <a href="#" class="dropdown-toggle" data-toggle="dropdown">
    <img src="<?=Yii::$app->homeUrl;?>img/img_avatar/<?= ThuVien::getAvatarUserByID(\Yii::$app->user->identity->id);?>" class="user-image" alt="User Image">
    <span class="hidden-xs"><?= \Yii::$app->user->identity->firstname;?> <?= \Yii::$app->user->identity->lastname;?></span>
  </a>
  <ul class="dropdown-menu">
    <!-- User image -->
    <li class="user-header">
      <img src="<?=Yii::$app->homeUrl;?>img/img_avatar/<?= ThuVien::getAvatarUserByID(\Yii::$app->user->identity->id);?>" class="img-circle" alt="User Image">

      <p>
        <?= \Yii::$app->user->identity->firstname;?> <?= \Yii::$app->user->identity->lastname;?>
        <small>Tham gia từ ngày <?=date('d', \Yii::$app->user->identity->created_at);?> tháng <?=date('m', \Yii::$app->user->identity->created_at);?> năm <?=date('Y', \Yii::$app->user->identity->created_at);?></small>
      </p>
    </li>
    <!-- Menu Footer-->
    <li class="user-footer">
      <div class="pull-left">
        <a href="#" class="btn btn-skin">Hồ sơ cá nhân</a>
      </div>
      <div class="pull-right">
        <a href="<?=Url::to(['site/logout']);?>" class="btn btn-skin">Đăng xuất</a>
      </div>
    </li>
  </ul>
</li>