<?php
if($model)
{
?>
<div class="box-body table-responsive">
  <table class="table table-striped table-hover">
    <tbody>
      <tr class="success">
         <td class="col-2 col-sm-2">NHOMDICHVUID</td>
         <td class="col-4 col-sm-4"><?=$model->nhomdichvuid;?></td>
         <td class="col-2 col-sm-2">MANHOMDICHVU</td>
         <td class="col-4 col-sm-4"><?=$model->manhomdichvu;?></td>
      </tr>
      <tr class="danger">
         <td class="col-2 col-sm-2">NHOM_MABHYT_ID</td>
         <td class="col-4 col-sm-4"><?=$model->nhom_mabhyt_id;?></td>
         <td class="col-2 col-sm-2">MADMBYT</td>
         <td class="col-4 col-sm-4"><?=$model->madmbyt;?></td>
      </tr>
      <tr class="success">
         <td class="col-2 col-sm-2">KHOANMUCID</td>
         <td class="col-4 col-sm-4"><?=$model->khoanmucid;?></td>
         <td class="col-2 col-sm-2">NHOMKETOANID</td>
         <td class="col-4 col-sm-4"><?=$model->nhomketoanid;?></td>
      </tr>
      <tr class="danger">
         <td class="col-2 col-sm-2">DONVI</td>
         <td class="col-4 col-sm-4"><?=$model->donvi;?></td>
         <td class="col-2 col-sm-2">GIADICHVU</td>
         <td class="col-4 col-sm-4"><?=$model->giadichvu;?></td>
      </tr>
      <tr class="success">
         <td class="col-2 col-sm-2">GIANUOCNGOAI</td>
         <td class="col-4 col-sm-4"><?=$model->gianuocngoai;?></td>
         <td class="col-2 col-sm-2">QUYETDINH</td>
         <td class="col-4 col-sm-4"><?=$model->quyetdinh;?></td>
      </tr>
      <tr class="danger">
         <td class="col-2 col-sm-2">NGAYCONGBO</td>
         <td class="col-4 col-sm-4"><?=$model->ngaycongbo;?></td>
         <td class="col-2 col-sm-2">STTMAU21</td>
         <td class="col-4 col-sm-4"><?=$model->sttmau21;?></td>
      </tr>
      <tr class="success">
         <td class="col-2 col-sm-2">MABYTMAU21</td>
         <td class="col-4 col-sm-4"><?=$model->mabytmau21;?></td>
         <td class="col-2 col-sm-2">LOAIPTTT</td>
         <td class="col-4 col-sm-4"><?=$model->loaipttt;?></td>
      </tr>
      <tr class="danger">
         <td class="col-2 col-sm-2">MATT37</td>
         <td class="col-4 col-sm-4"><?=$model->matt37;?></td>
         <td class="col-2 col-sm-2">MATT4350</td>
         <td class="col-4 col-sm-4"><?=$model->matt4350;?></td>
      </tr>
      <tr class="success">
         <td class="col-2 col-sm-2">CHUYENKHOAID</td>
         <td class="col-4 col-sm-4"><?=$model->chuyenkhoaid;?></td>
         <td class="col-2 col-sm-2">TENDICHVUBHYT</td>
         <td class="col-4 col-sm-4"><?=$model->tendichvubhyt;?></td>
      </tr>
      <tr class="danger">
         <td class="col-2 col-sm-2">KHOA</td>
         <td class="col-4 col-sm-4"><?=$model->khoa;?></td>
         <td class="col-2 col-sm-2">GHICHU</td>
         <td class="col-4 col-sm-4"><?=$model->ghichu;?></td>
      </tr>
    </tbody>
  </table>
</div>
<?php
}
?>