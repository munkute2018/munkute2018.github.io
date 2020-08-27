<?php
if($model)
{
?>
<div class="box-body table-responsive">
  <table class="table table-striped table-hover">
  	<tbody>
      <tr class="success">
         <td class="col-2 col-sm-2">Tuyến đơn vị</td>
         <td class="col-4 col-sm-4"><?=$model->tuyen == 5 ? 'Chưa phân tuyến' : $model->tuyen;?></td>
         <td class="col-2 col-sm-2">Hạng đơn vị</td>
         <td class="col-4 col-sm-4"><?=$model->tuyen == 5 ? 'Chưa xếp hạng' : ($model->tuyen == 0 ? 'Hạng đặc biệt' : $model->tuyen);?></td>
      </tr>
      <tr class="danger">
         <td class="col-2 col-sm-2">Số điện thoại</td>
         <td class="col-4 col-sm-4"><?=$model->phone;?></td>
         <td class="col-2 col-sm-2"></td>
         <td class="col-4 col-sm-4"></td>
      </tr>
   	</tbody>
  </table>
</div>
<?php
}
?>