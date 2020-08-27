<header class="main-header">
  <!-- Logo -->
  <a href="<?=Yii::$app->homeUrl;?>" class="logo">
    <!-- mini logo for sidebar mini 50x50 pixels -->
    <span class="logo-mini"><b>H</b>IS</span>
    <!-- logo for regular state and mobile devices -->
    <span class="logo-lg"><b>VNPT</b> HIS</span>
  </a>
  <!-- Header Navbar: style can be found in header.less -->
  <nav class="navbar navbar-static-top">
    <!-- Sidebar toggle button-->
    <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
      <span class="sr-only">Toggle navigation</span>
    </a>

    <div class="navbar-custom-menu">
      <ul class="nav navbar-nav">
        <?php //echo $this->render("_message"); ?>
        <?php //echo $this->render("_notifications"); ?>
        <?php //echo $this->render("_task"); ?>
        <?php echo $this->render("_user_header"); ?>
        <li>
          <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
        </li>
      </ul>
    </div>
  </nav>
</header>