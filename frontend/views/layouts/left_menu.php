<?php
use yii\helpers\Url;
use common\models\ThuVien;
use common\models\Dmthamso;
?>
<aside class="main-sidebar">
  <!-- sidebar: style can be found in sidebar.less -->
  <section class="sidebar">
    <!-- Sidebar user panel -->
    <div class="user-panel">
      <div class="pull-left image">
        <img src="<?=Yii::$app->homeUrl;?>img/img_avatar/<?= ThuVien::getAvatarUserByID(\Yii::$app->user->identity->id);?>" class="img-circle" alt="User Image">
      </div>
      <div class="pull-left info">
        <p><?= \Yii::$app->user->identity->firstname;?> <?= \Yii::$app->user->identity->lastname;?></p>
        <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
      </div>
    </div>
    <!-- search form -->
    <form action="#" method="get" class="sidebar-form">
      <div class="input-group">
        <input type="text" name="q" class="form-control" placeholder="Tìm kiếm...">
        <span class="input-group-btn">
              <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
              </button>
            </span>
      </div>
    </form>
    <!-- /.search form -->
    <?php
      $menu = ThuVien::getListMenu(\Yii::$app->user->identity->id);
      $lst_active = ThuVien::getListMenuActive(Yii::$app->controller->getRoute());
    ?>
    <ul class="sidebar-menu" data-widget="tree">
    <?php
      $current_lvl = 0;
      $show = '';
      foreach ($menu as $key => $item) {
        if($item->lvl == 1){
          if($current_lvl != 0){
            $show .= str_repeat("</ul></li>", $current_lvl - $item->lvl - 1);
          }
          $show.= '<li class="header">'.$item->name.'</li>';
          $current_lvl = 2;
        }
        else{
          if($item->lvl > $current_lvl){
            $show.= '<ul class="treeview-menu">';
          }
          if($item->lvl < $current_lvl){
            $show .= str_repeat("</ul></li>", $current_lvl - $item->lvl);
          }
          $li_parent = '<li class="treeview">';
          $li_child = '<li>';
          if(in_array($item->id, $lst_active)){
            $li_parent = '<li class="treeview  menu-open active">';
            $li_child = '<li class="active">';
          }
          $classHasChild = ($item->rgt - $item->lft > 1) ? $li_parent : $li_child;
          $iconHasChild = ($item->rgt - $item->lft > 1) ? '<span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>' : '';
          $closeTag = ($item->rgt - $item->lft > 1) ? '' : '</li>';
          $iconMenu = ($item->icon == '') ? '<i class="fa fa-circle-o"></i>' : (($item->icon_type == 1) ? '<i class="fa fa-'.$item->icon.'"></i>' : $item->icon);
          $linkUrl = ($item->link == '') ? '<a href="#">' : '<a href="'.Url::to([$item->link]).'">';
          $show.= $classHasChild.$linkUrl.$iconMenu.'<span>'.$item->name.'</span>'.$iconHasChild.'</a>'.$closeTag;
          $current_lvl = $item->lvl;
        }
      }
      echo $show;
    ?>
    </ul>
  </section>
</aside>