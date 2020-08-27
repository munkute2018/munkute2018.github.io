<?php
/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use common\widgets\Alert;
use \yii\web\Request;
use common\models\ThuVien;

AppAsset::register($this);
$this->title = "VNPT HIS - Trung tâm CNTT"; 
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">

<head>
    <meta charset="utf-8">
    <meta name="description" content="">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="robots" content="all,follow">
    <meta name="googlebot" content="index,follow,snippet,archive">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="keywords" content="">
    <link rel="shortcut icon" href="<?=Yii::$app->homeUrl;?>img/core-img/favicon.ico" type="image/x-icon" />
    <title><?= Html::encode($this->title) ?></title>
    <?= Html::csrfMetaTags() ?>
    <?php $this->head() ?>
</head>

<body class="hold-transition skin-blue sidebar-mini fixed">
    <?php $this->beginBody() ?>
    <div class="wrapper">
        <?php echo $this->render("header"); ?>
        <?php echo $this->render("left_menu"); ?>
        <?php echo $content; ?>
        <?php echo $this->render("footer"); ?>
        <?php echo $this->render("right_option"); ?>
        <div class="control-sidebar-bg"></div>
    </div>
    <?php $this->endBody() ?>
        
</body>
</html>
<?php $this->endPage() ?>