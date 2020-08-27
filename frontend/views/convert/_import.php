<?php
use yii\helpers\Html;
use common\models\DonVi;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\file\FileInput;
use kartik\select2\Select2;
$arr =  [ 
          'A' => 'A',
          'B' => 'B',
          'C' => 'C',
          'D' => 'D',
          'E' => 'E',
          'F' => 'F',
          'G' => 'G',
          'H' => 'H',
          'I' => 'I',
          'J' => 'J',
          'K' => 'K',
          'L' => 'L',
          'M' => 'M',
          'N' => 'N',
          'O' => 'O',
          'P' => 'P',
          'Q' => 'Q',
          'R' => 'R',
          'S' => 'S',
          'T' => 'T',
          'U' => 'U',
          'V' => 'V',
          'W' => 'W',
          'X' => 'X',
          'Y' => 'Y',
          'Z' => 'Z',
          'AA' => 'AA',
          'AB' => 'AB',
          'AC' => 'AC',
          'AD' => 'AD',
          'AE' => 'AE',
          'AF' => 'AF',
          'AG' => 'AG',
          'AH' => 'AH',
          'AI' => 'AI',
          'AJ' => 'AJ',
          'AK' => 'AK',
          'AL' => 'AL',
          'AM' => 'AM',
          'AN' => 'AN',
          'AO' => 'AO',
          'AP' => 'AP',
          'AQ' => 'AQ',
          'AR' => 'AR',
          'AS' => 'AS',
          'AT' => 'AT',
          'AU' => 'AU',
          'AV' => 'AV',
          'AW' => 'AW',
          'AX' => 'AX',
          'AY' => 'AY',
          'AZ' => 'AZ',
          'BA' => 'BA',
          'BB' => 'BB',
          'BC' => 'BC',
          'BD' => 'BD',
          'BE' => 'BE',
          'BF' => 'BF',
          'BG' => 'BG',
          'BH' => 'BH',
          'BI' => 'BI',
          'BJ' => 'BJ',
          'BK' => 'BK',
          'BL' => 'BL',
          'BM' => 'BM',
          'BN' => 'BN',
          'BO' => 'BO',
          'BP' => 'BP',
          'BQ' => 'BQ',
          'BR' => 'BR',
          'BS' => 'BS',
          'BT' => 'BT',
          'BU' => 'BU',
          'BV' => 'BV',
          'BW' => 'BW',
          'BX' => 'BX',
          'BY' => 'BY',
          'BZ' => 'BZ',
          'CA' => 'CA',
          'CB' => 'CB',
          'CC' => 'CC',
          'CD' => 'CD',
          'CE' => 'CE',
          'CF' => 'CF',
          'CG' => 'CG',
          'CH' => 'CH',
          'CI' => 'CI',
          'CJ' => 'CJ',
          'CK' => 'CK',
          'CL' => 'CL',
          'CM' => 'CM',
          'CN' => 'CN',
          'CO' => 'CO',
          'CP' => 'CP',
          'CQ' => 'CQ',
          'CR' => 'CR',
          'CS' => 'CS',
          'CT' => 'CT',
          'CU' => 'CU',
          'CV' => 'CV',
          'CW' => 'CW',
          'CX' => 'CX',
          'CY' => 'CY',
          'CZ' => 'CZ',
          'DA' => 'DA',
          'DB' => 'DB',
          'DC' => 'DC',
          'DD' => 'DD',
          'DE' => 'DE',
          'DF' => 'DF',
          'DG' => 'DG',
          'DH' => 'DH',
          'DI' => 'DI',
          'DJ' => 'DJ',
          'DK' => 'DK',
          'DL' => 'DL',
          'DM' => 'DM',
          'DN' => 'DN',
          'DO' => 'DO',
          'DP' => 'DP',
          'DQ' => 'DQ',
          'DR' => 'DR',
          'DS' => 'DS',
          'DT' => 'DT',
          'DU' => 'DU',
          'DV' => 'DV',
          'DW' => 'DW',
          'DX' => 'DX',
          'DY' => 'DY',
          'DZ' => 'DZ',
        ];
?>
<section class="section">
    <?php $form = ActiveForm::begin(['options'=>['enctype'=>'multipart/form-data', 'id' => 'form-themdanhmuc', 'enableClientValidation' => false, 'enableAjaxValidation' => true, 'validationUrl' => Yii::$app->urlManager->createUrl(['donvi/importvalidate'])]]); ?>
    <div class="row">
      <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="card card-block">
          <div class="form-group">
              <?php 
                echo $form->field($model, 'file')->widget(FileInput::classname(), [
                    'options' => [
                        'accept' => '.xls, .xlsx, .csv',
                        'data-browse-on-zone-click' => 'true',
                    ],
                    'pluginOptions' => [
                        'browseClass' => 'btn btn-skin',
                        'browseIcon' => '<i class="fa fa-file-excel-o"></i> ',
                        'browseLabel' =>  'Chọn file',
                        'uploadLabel' => 'Import',
                        'msgPlaceholder' => 'Chọn tập tin...'
                    ],
                ])->label(false);
              ?>
          </div>
        </div>
      </div>
    </div>
    <div class="border border-primary">
    <?php
    if(isset($phienban) && $phienban == 0){
    ?>
      <div class="row">
        <div class="col-xs-4 col-sm-2 col-md-2">
          <div class="card card-block">
            <div class="form-group">
              <label class="control-label">DVTT</label>
                <?php
                  echo $form->field($model, 'dvtt')->widget(Select2::classname(), [
                    'data' => $arr,
                    'options' => [$model->dvtt => ['Selected'=>'selected']],
                    'class' => 'form-control',
                  ])->label(false);
                ?>
            </div>
          </div>
        </div>
        <div class="col-xs-4 col-sm-2 col-md-2">
          <div class="card card-block">
            <div class="form-group">
              <label class="control-label">Mã DV</label>
                <?php
                  echo $form->field($model, 'madv')->widget(Select2::classname(), [
                    'data' => $arr,
                    'options' => [$model->madv => ['Selected'=>'selected']],
                    'class' => 'form-control',
                  ])->label(false);
                ?>
            </div>
          </div>
        </div>
        <div class="col-xs-4 col-sm-2 col-md-2">
          <div class="card card-block">
            <div class="form-group">
              <label class="control-label">Mã BC</label>
                <?php
                  echo $form->field($model, 'mabc')->widget(Select2::classname(), [
                    'data' => $arr,
                    'options' => [$model->mabc => ['Selected'=>'selected']],
                    'class' => 'form-control',
                  ])->label(false);
                ?>
            </div>
          </div>
        </div>
        <div class="col-xs-4 col-sm-2 col-md-2">
          <div class="card card-block">
            <div class="form-group">
              <label class="control-label">Tên DV</label>
                <?php
                  echo $form->field($model, 'tendv')->widget(Select2::classname(), [
                    'data' => $arr,
                    'options' => [$model->tendv => ['Selected'=>'selected']],
                    'class' => 'form-control',
                  ])->label(false);
                ?>
            </div>
          </div>
        </div>
        <div class="col-xs-4 col-sm-2 col-md-2">
          <div class="card card-block">
            <div class="form-group">
              <label class="control-label">Giá BHYT</label>
                <?php
                  echo $form->field($model, 'giabhyt')->widget(Select2::classname(), [
                    'data' => $arr,
                    'options' => [$model->dvtt => ['Selected'=>'selected']],
                    'class' => 'form-control',
                  ])->label(false);
                ?>
            </div>
          </div>
        </div>
        <div class="col-xs-4 col-sm-2 col-md-2">
          <div class="card card-block">
            <div class="form-group">
              <label class="control-label">Giá VP</label>
                <?php
                  echo $form->field($model, 'giavp')->widget(Select2::classname(), [
                    'data' => $arr,
                    'options' => [$model->giavp => ['Selected'=>'selected']],
                    'class' => 'form-control',
                  ])->label(false);
                ?>
            </div>
          </div>
        </div>
      </div>
    <?php
    }
    else{
      $listdonvi = DonVi::find()->where(['phienban' => 1, 'status' => 1])->orderBy(['madonvi' => SORT_ASC])->all();
    ?>
      <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
          <div class="card card-block">
            <div class="form-group">
              <label class="control-label">Đơn vị chuyển đổi giá DVKT</label>
                <?php
                  echo $form->field($model, 'dvttl2')->widget(Select2::classname(), [
                    'data' => ArrayHelper::map($listdonvi,'madonvi', function($data) {
                        return $data['madonvi'].' - '.$data['tendonvi'];
                    }),
                    'options' => [$model->dvttl2 => ['Selected'=>'selected']],
                    'class' => 'form-control',
                  ])->label(false);
                ?>
            </div>
          </div>
        </div>
      </div>
    <?php
    }
    ?>
    </div>
    <?php ActiveForm::end(); ?>
</section>

<?php
$script = <<< JS
  $(document).ready(function () { 
    $('#form-themdanhmuc :input[type=submit]').on('click',function()
    {
        $(this).attr('disabled','disabled');
        $('#addModal').block({
            message: "Đang xử lý dữ liệu. Vui lòng đợi...", css: {
                border: 'none',
                padding: '10px',
                backgroundColor: '#000',
                '-webkit-border-radius': '10px',
                '-moz-border-radius': '10px',
                opacity: .5,
                color: '#fff'
            }
        });
        $('#form-themdanhmuc').submit();
    });

    $('#form-themdanhmuc').on('afterValidateAttribute', function (event, attribute, message) {
      if(message!=''){
        $('#form-themdanhmuc :input[type=submit]').attr('disabled', false);
        $('#addModal').unblock();
      }
    });

    $("#form-themdanhmuc").on('beforeSubmit', function (event) { 
        var form_data = new FormData($('#form-themdanhmuc')[0]);
        $.ajax({
           url: $("#form-themdanhmuc").attr('action'), 
           dataType: 'JSON',  
           cache: false,
           contentType: false,
           processData: false,
           data: form_data, //$(this).serialize(),                      
           type: 'post',                        
           beforeSend: function() {
           },
           success: function(response){ 
              $('#addModal').unblock();
              if(response.status){                       
                 toastr.success("",response.message); 
                 $.pjax({container: '#theDatatable'});
                 $('#addModal').modal('hide');
              }
              else{
                toastr.warning("",response.message); 
                if(response.hideModal){
                  $.pjax({container: '#theDatatable'});
                  $('#addModal').modal('hide');
                }
                if(response.file_err){
                  var link = document.createElement("a");
                  link.download = "Error_Import.xls";
                  link.href = response.file_err;
                  document.body.appendChild(link);
                  link.click();
                }
                $('#form-themdanhmuc :input[type=submit]').attr('disabled', false);
              }
           },
           complete: function() {
           },
           error: function (data) {
              $('#addModal').unblock();
              toastr.warning("","Có lỗi xảy ra! Vui lòng thử lại!"); 
              $('#form-themdanhmuc :input[type=submit]').attr('disabled', false);   
           }
        });                
        return false;
    });

    $("#btn-fileImp").on('click',function()
    {
      window.location.href = 'downloads/import_quyetdinh.xls';
    });
  });  
JS;
$this->registerJs($script);
?>