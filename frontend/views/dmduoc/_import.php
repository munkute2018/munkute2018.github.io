<?php
use yii\helpers\Html;
use yii\helpers\Url;
use common\models\DonVi;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\file\FileInput;
use kartik\select2\Select2;
use kartik\depdrop\DepDrop;
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
                        'hiddenOptions' => ['id' => 'fileimp'],
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
      <div class="row">
        <div class="col-xs-12 col-sm-6 col-md-4">
          <div class="card card-block">
            <div class="form-group">
              <label class="control-label">Thanh toán</label>
                <?php
                  echo $form->field($model, 'thanhtoan')->widget(Select2::classname(), [
                    'data' => [1 => 'BHYT', 0 => 'Viện phí'],
                    'options' => [$model->thanhtoan => ['Selected'=>'selected'], 'id'=>'thanhtoan'],
                    'class' => 'form-control',
                  ])->label(false);
                ?>
            </div>
          </div>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-4">
          <div class="card card-block">
            <div class="form-group">
              <label class="control-label">Phân loại</label>
                <?php
                  echo $form->field($model, 'phanloai')->widget(DepDrop::classname(), [
                  'data' => [1 => 'Thuốc', 2 => 'Vật tư'],
                  'options' => [$model->phanloai => ['Selected'=>'selected'], 'id'=>'phanloai', 'placeholder' => ''],
                  'type' => DepDrop::TYPE_SELECT2,
                  'select2Options' => [],
                  'pluginOptions' => [
                      'depends' => ['thanhtoan'],
                      'url' => Url::to(['dmduoc/loadphanloai']),
                      'loadingText' => 'Tải dữ liệu ...',
                  ]
              ])->label(false);
                ?>
            </div>
          </div>
        </div>
        <div class="col-xs-4 col-sm-3 col-md-2">
          <div class="card card-block">
            <div class="form-group">
              <label class="control-label">Mã báo cáo</label>
                <?php
                  echo $form->field($model, 'mabc')->widget(DepDrop::classname(), [
                  'data' => $arr,
                  'options' => [$model->mabc => ['Selected'=>'selected'], 'id'=>'mabc', 'placeholder' => ''],
                  'type' => DepDrop::TYPE_SELECT2,
                  'select2Options' => [],
                  'pluginOptions' => [
                      'depends' => ['thanhtoan', 'phanloai'],
                      'url' => Url::to(['dmduoc/loadvitri', 'vitri' => "mabc"]),
                      'loadingText' => 'Tải dữ liệu ...',
                  ]
              ])->label(false);
                ?>
            </div>
          </div>
        </div>
        <div class="col-xs-4 col-sm-3 col-md-2">
          <div class="card card-block">
            <div class="form-group">
              <label class="control-label">Tên VT</label>
                <?php
                  echo $form->field($model, 'tenvt')->widget(DepDrop::classname(), [
                  'data' => $arr,
                  'options' => [$model->tenvt => ['Selected'=>'selected'], 'id'=>'tenvt', 'placeholder' => ''],
                  'type' => DepDrop::TYPE_SELECT2,
                  'select2Options' => [],
                  'pluginOptions' => [
                      'depends' => ['thanhtoan', 'phanloai'],
                      'url' => Url::to(['dmduoc/loadvitri', 'vitri' => "tenvt"]),
                      'loadingText' => 'Tải dữ liệu ...',
                  ]
              ])->label(false);
                ?>
            </div>
          </div>
        </div>
        <div class="col-xs-4 col-sm-3 col-md-2">
          <div class="card card-block">
            <div class="form-group">
              <label class="control-label">Hoạt chất</label>
                <?php
                  echo $form->field($model, 'hoatchat')->widget(DepDrop::classname(), [
                  'data' => $arr,
                  'options' => [$model->hoatchat => ['Selected'=>'selected'], 'id'=>'hoatchat', 'placeholder' => ''],
                  'type' => DepDrop::TYPE_SELECT2,
                  'select2Options' => [],
                  'pluginOptions' => [
                      'depends' => ['thanhtoan', 'phanloai'],
                      'url' => Url::to(['dmduoc/loadvitri', 'vitri' => "hoatchat"]),
                      'loadingText' => 'Tải dữ liệu ...',
                  ]
              ])->label(false);
                ?>
            </div>
          </div>
        </div>
        <div class="col-xs-4 col-sm-3 col-md-2">
          <div class="card card-block">
            <div class="form-group">
              <label class="control-label">Hàm lượng</label>
                <?php
                  echo $form->field($model, 'hamluong')->widget(DepDrop::classname(), [
                  'data' => $arr,
                  'options' => [$model->hamluong => ['Selected'=>'selected'], 'id'=>'hamluong', 'placeholder' => ''],
                  'type' => DepDrop::TYPE_SELECT2,
                  'select2Options' => [],
                  'pluginOptions' => [
                      'depends' => ['thanhtoan', 'phanloai'],
                      'url' => Url::to(['dmduoc/loadvitri', 'vitri' => "hamluong"]),
                      'loadingText' => 'Tải dữ liệu ...',
                  ]
              ])->label(false);
                ?>
            </div>
          </div>
        </div>
        <div class="col-xs-4 col-sm-3 col-md-2">
          <div class="card card-block">
            <div class="form-group">
              <label class="control-label">Đường dùng</label>
                <?php
                  echo $form->field($model, 'duongdung')->widget(DepDrop::classname(), [
                  'data' => $arr,
                  'options' => [$model->duongdung => ['Selected'=>'selected'], 'id'=>'duongdung', 'placeholder' => ''],
                  'type' => DepDrop::TYPE_SELECT2,
                  'select2Options' => [],
                  'pluginOptions' => [
                      'depends' => ['thanhtoan', 'phanloai'],
                      'url' => Url::to(['dmduoc/loadvitri', 'vitri' => "duongdung"]),
                      'loadingText' => 'Tải dữ liệu ...',
                  ]
              ])->label(false);
                ?>
            </div>
          </div>
        </div>
        <div class="col-xs-4 col-sm-3 col-md-2">
          <div class="card card-block">
            <div class="form-group">
              <label class="control-label">Đơn vị tính</label>
                <?php
                  echo $form->field($model, 'donvitinh')->widget(DepDrop::classname(), [
                  'data' => $arr,
                  'options' => [$model->donvitinh => ['Selected'=>'selected'], 'id'=>'donvitinh', 'placeholder' => ''],
                  'type' => DepDrop::TYPE_SELECT2,
                  'select2Options' => [],
                  'pluginOptions' => [
                      'depends' => ['thanhtoan', 'phanloai'],
                      'url' => Url::to(['dmduoc/loadvitri', 'vitri' => "donvitinh"]),
                      'loadingText' => 'Tải dữ liệu ...',
                  ]
              ])->label(false);
                ?>
            </div>
          </div>
        </div>
        <div class="col-xs-4 col-sm-3 col-md-2">
          <div class="card card-block">
            <div class="form-group">
              <label class="control-label">Đơn giá</label>
                <?php
                  echo $form->field($model, 'dongia')->widget(DepDrop::classname(), [
                  'data' => $arr,
                  'options' => [$model->dongia => ['Selected'=>'selected'], 'id'=>'dongia', 'placeholder' => ''],
                  'type' => DepDrop::TYPE_SELECT2,
                  'select2Options' => [],
                  'pluginOptions' => [
                      'depends' => ['thanhtoan', 'phanloai'],
                      'url' => Url::to(['dmduoc/loadvitri', 'vitri' => "dongia"]),
                      'loadingText' => 'Tải dữ liệu ...',
                  ]
              ])->label(false);
                ?>
            </div>
          </div>
        </div>
        <div class="col-xs-4 col-sm-3 col-md-2">
          <div class="card card-block">
            <div class="form-group">
              <label class="control-label">Quy cách</label>
                <?php
                  echo $form->field($model, 'quycach')->widget(DepDrop::classname(), [
                  'data' => $arr,
                  'options' => [$model->quycach => ['Selected'=>'selected'], 'id'=>'quycach', 'placeholder' => ''],
                  'type' => DepDrop::TYPE_SELECT2,
                  'select2Options' => [],
                  'pluginOptions' => [
                      'depends' => ['thanhtoan', 'phanloai'],
                      'url' => Url::to(['dmduoc/loadvitri', 'vitri' => "quycach"]),
                      'loadingText' => 'Tải dữ liệu ...',
                  ]
              ])->label(false);
                ?>
            </div>
          </div>
        </div>
        <div class="col-xs-4 col-sm-3 col-md-2">
          <div class="card card-block">
            <div class="form-group">
              <label class="control-label">Số đăng ký</label>
                <?php
                  echo $form->field($model, 'sodangky')->widget(DepDrop::classname(), [
                  'data' => $arr,
                  'options' => [$model->sodangky => ['Selected'=>'selected'], 'id'=>'sodangky', 'placeholder' => ''],
                  'type' => DepDrop::TYPE_SELECT2,
                  'select2Options' => [],
                  'pluginOptions' => [
                      'depends' => ['thanhtoan', 'phanloai'],
                      'url' => Url::to(['dmduoc/loadvitri', 'vitri' => "sodangky"]),
                      'loadingText' => 'Tải dữ liệu ...',
                  ]
              ])->label(false);
                ?>
            </div>
          </div>
        </div>
        <div class="col-xs-4 col-sm-3 col-md-2">
          <div class="card card-block">
            <div class="form-group">
              <label class="control-label">Nhà sản xuất</label>
                <?php
                  echo $form->field($model, 'nhasx')->widget(DepDrop::classname(), [
                  'data' => $arr,
                  'options' => [$model->nhasx => ['Selected'=>'selected'], 'id'=>'nhasx', 'placeholder' => ''],
                  'type' => DepDrop::TYPE_SELECT2,
                  'select2Options' => [],
                  'pluginOptions' => [
                      'depends' => ['thanhtoan', 'phanloai'],
                      'url' => Url::to(['dmduoc/loadvitri', 'vitri' => "nhasx"]),
                      'loadingText' => 'Tải dữ liệu ...',
                  ]
              ])->label(false);
                ?>
            </div>
          </div>
        </div>
        <div class="col-xs-4 col-sm-3 col-md-2">
          <div class="card card-block">
            <div class="form-group">
              <label class="control-label">Nước sản xuất</label>
                <?php
                  echo $form->field($model, 'nuocsx')->widget(DepDrop::classname(), [
                  'data' => $arr,
                  'options' => [$model->nuocsx => ['Selected'=>'selected'], 'id'=>'nuocsx', 'placeholder' => ''],
                  'type' => DepDrop::TYPE_SELECT2,
                  'select2Options' => [],
                  'pluginOptions' => [
                      'depends' => ['thanhtoan', 'phanloai'],
                      'url' => Url::to(['dmduoc/loadvitri', 'vitri' => "nuocsx"]),
                      'loadingText' => 'Tải dữ liệu ...',
                  ]
              ])->label(false);
                ?>
            </div>
          </div>
        </div>
        <div class="col-xs-4 col-sm-3 col-md-2">
          <div class="card card-block">
            <div class="form-group">
              <label class="control-label">Nhà thầu</label>
                <?php
                  echo $form->field($model, 'nhathau')->widget(DepDrop::classname(), [
                  'data' => $arr,
                  'options' => [$model->nhathau => ['Selected'=>'selected'], 'id'=>'nhathau', 'placeholder' => ''],
                  'type' => DepDrop::TYPE_SELECT2,
                  'select2Options' => [],
                  'pluginOptions' => [
                      'depends' => ['thanhtoan', 'phanloai'],
                      'url' => Url::to(['dmduoc/loadvitri', 'vitri' => "nhathau"]),
                      'loadingText' => 'Tải dữ liệu ...',
                  ]
              ])->label(false);
                ?>
            </div>
          </div>
        </div>
        <div class="col-xs-4 col-sm-3 col-md-2">
          <div class="card card-block">
            <div class="form-group">
              <label class="control-label">Quyết định</label>
                <?php
                  echo $form->field($model, 'quyetdinh')->widget(DepDrop::classname(), [
                  'data' => $arr,
                  'options' => [$model->quyetdinh => ['Selected'=>'selected'], 'id'=>'quyetdinh', 'placeholder' => ''],
                  'type' => DepDrop::TYPE_SELECT2,
                  'select2Options' => [],
                  'pluginOptions' => [
                      'depends' => ['thanhtoan', 'phanloai'],
                      'url' => Url::to(['dmduoc/loadvitri', 'vitri' => "quyetdinh"]),
                      'loadingText' => 'Tải dữ liệu ...',
                  ]
              ])->label(false);
                ?>
            </div>
          </div>
        </div>
        <div class="col-xs-4 col-sm-3 col-md-2">
          <div class="card card-block">
            <div class="form-group">
              <label class="control-label">Công bố</label>
                <?php
                  echo $form->field($model, 'congbo')->widget(DepDrop::classname(), [
                  'data' => $arr,
                  'options' => [$model->congbo => ['Selected'=>'selected'], 'id'=>'congbo', 'placeholder' => ''],
                  'type' => DepDrop::TYPE_SELECT2,
                  'select2Options' => [],
                  'pluginOptions' => [
                      'depends' => ['thanhtoan', 'phanloai'],
                      'url' => Url::to(['dmduoc/loadvitri', 'vitri' => "congbo"]),
                      'loadingText' => 'Tải dữ liệu ...',
                  ]
              ])->label(false);
                ?>
            </div>
          </div>
        </div>
        <div class="col-xs-4 col-sm-3 col-md-2">
          <div class="card card-block">
            <div class="form-group">
              <label class="control-label">Loại thuốc</label>
                <?php
                  echo $form->field($model, 'loaithuoc')->widget(DepDrop::classname(), [
                  'data' => $arr,
                  'options' => [$model->loaithuoc => ['Selected'=>'selected'], 'id'=>'loaithuoc', 'placeholder' => ''],
                  'type' => DepDrop::TYPE_SELECT2,
                  'select2Options' => [],
                  'pluginOptions' => [
                      'depends' => ['thanhtoan', 'phanloai'],
                      'url' => Url::to(['dmduoc/loadvitri', 'vitri' => "loaithuoc"]),
                      'loadingText' => 'Tải dữ liệu ...',
                  ]
              ])->label(false);
                ?>
            </div>
          </div>
        </div>
        <div class="col-xs-4 col-sm-3 col-md-2">
          <div class="card card-block">
            <div class="form-group">
              <label class="control-label">Gói thầu</label>
                <?php
                  echo $form->field($model, 'goithau')->widget(DepDrop::classname(), [
                  'data' => $arr,
                  'options' => [$model->goithau => ['Selected'=>'selected'], 'id'=>'goithau', 'placeholder' => ''],
                  'type' => DepDrop::TYPE_SELECT2,
                  'select2Options' => [],
                  'pluginOptions' => [
                      'depends' => ['thanhtoan', 'phanloai'],
                      'url' => Url::to(['dmduoc/loadvitri', 'vitri' => "goithau"]),
                      'loadingText' => 'Tải dữ liệu ...',
                  ]
              ])->label(false);
                ?>
            </div>
          </div>
        </div>
        <div class="col-xs-4 col-sm-3 col-md-2">
          <div class="card card-block">
            <div class="form-group">
              <label class="control-label">Nhóm thầu</label>
                <?php
                  echo $form->field($model, 'nhomthau')->widget(DepDrop::classname(), [
                  'data' => $arr,
                  'options' => [$model->nhomthau => ['Selected'=>'selected'], 'id'=>'nhomthau', 'placeholder' => ''],
                  'type' => DepDrop::TYPE_SELECT2,
                  'select2Options' => [],
                  'pluginOptions' => [
                      'depends' => ['thanhtoan', 'phanloai'],
                      'url' => Url::to(['dmduoc/loadvitri', 'vitri' => "nhomthau"]),
                      'loadingText' => 'Tải dữ liệu ...',
                  ]
              ])->label(false);
                ?>
            </div>
          </div>
        </div>
      </div>
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