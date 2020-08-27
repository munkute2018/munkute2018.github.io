<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\file\FileInput;
?>
<section class="section">
    <?php $form = ActiveForm::begin(['options'=>['enctype'=>'multipart/form-data', 'id' => 'form-themdanhmuc', 'enableClientValidation' => false, 'enableAjaxValidation' => true, 'validationUrl' => Yii::$app->urlManager->createUrl(['nhasx/importvalidate'])]]); ?>
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
    <?php ActiveForm::end(); ?>
    <div class="row">
      <div class="col-md-12">
        <div class="card card-block">
          <div class="form-group">
            <center><?php echo Html::Button('File Import mẫu', ['class' => 'btn btn-skin', 'id' => 'btn-fileImp']) ?></center>
          </div>
        </div>
      </div>
    </div>
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
      window.location.href = 'downloads/import_nsx.xls';
    });
  });  
JS;
$this->registerJs($script);
?>