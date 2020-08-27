<?php
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use common\models\ThuVien;
use common\models\Dmthamso;
use kartik\grid\GridView;
use kartik\export\ExportMenu;
use yii\widgets\LinkPager;
use yii\widgets\Pjax;
use kartik\editable\Editable;

$list = Dmthamso::find()->where(['flag' => 1, 'status' => 1])->orderBy(['id' => SORT_ASC])->all();
$gridColumns = [
    [
        'class' => 'kartik\grid\CheckboxColumn',
        'headerOptions' => ['class' => 'kartik-sheet-style'],
    ],
    [
        'class'=>'kartik\grid\SerialColumn',
        'contentOptions'=>['class'=>'kartik-sheet-style'],
        'pageSummary'=>'Total',
        'header'=>'STT',
        'headerOptions'=>['class'=>'kartik-sheet-style'],
        'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
    ],
    [
        'attribute' => 'madonvi',
        'label' => 'Mã ĐV',
        'vAlign' => 'middle',
        'hAlign' => 'center',
        'width' => '60px',
        'filterInputOptions' => ['maxlength' => 5, 'class' => 'form-control'],
        'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
        'headerOptions' => ['class' => 'kartik-sheet-style sort-number'],
    ],
    [
        'attribute' => 'tendonvi',
        'label' => 'Tên ĐV',
        'vAlign' => 'middle',
        'hAlign' => 'left',
        'width' => '250px',
        'filterInputOptions' => ['maxlength' => 255, 'class' => 'form-control'],
        'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
        'headerOptions' => ['class' => 'kartik-sheet-style'],
    ],
    [
        'attribute' => 'id_parent',
        'label' => 'ĐV quản lý',
        'vAlign' => 'middle',
        'hAlign' => 'center',
        'width' => '60px',
        'filterInputOptions' => ['maxlength' => 5, 'class' => 'form-control'],
        'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
        'headerOptions' => ['class' => 'kartik-sheet-style sort-number'],
    ],
    [
        'attribute' => 'tuyen_filter',
        'label' => 'Tuyến',
        'vAlign' => 'middle',
        'hAlign' => 'center',
        'width' => '120px',
        'value' => function($model) {
            switch ($model->tuyen) {
                case 1:
                    return 'Tuyến 1';
                break;
                case 2:
                    return 'Tuyến 2';
                break;
                case 3:
                    return 'Tuyến 3';
                break;
                case 4:
                    return 'Tuyến 4';
                break;
                case 5:
                    return 'Chưa phân tuyến';
                break;
            }
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => array(1 => 'Tuyến 1', 2 => 'Tuyến 2', 3 => 'Tuyến 3', 4 => 'Tuyến 4', 5 => 'Chưa phân tuyến'), 
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true, 'placeholder' => 'Chọn...'],
        ],
        'filterInputOptions' => ['multiple' => true],
        'format' => 'raw',
        'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
        'headerOptions' => ['class' => 'kartik-sheet-style'],
    ],
    [
        'attribute' => 'giatri',
        'label' => $searchModel->id_thamso ? $searchModel->id_thamso : 'Giá trị',
        'vAlign' => 'middle',
        'hAlign' => 'center',
        'width' => '150px',
        'format' => 'raw',
        'filterInputOptions' => ['maxlength' => 255, 'class' => 'form-control'],
        'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
        'headerOptions' => ['class' => 'kartik-sheet-style'],
        'value' => function($model, $key, $index, $column) use ($searchModel) {
            return Editable::widget([
                'name'=>'giatri', 
                'asPopover' => true,
                'placement' => 'left',
                'pjaxContainerId' => 'theDatatable',
                'value' => ThuVien::layGiaTriThamSoDonVi($model->madonvi, $searchModel->id_thamso),
                'header' => 'giá trị tham số',
                'afterInput'=>function($form, $widget) use ($model, $searchModel) {
                    echo $form->field($model, 'madonvi')->hiddenInput()->label(false);
                    echo $form->field($searchModel, 'id_thamso')->hiddenInput()->label(false);
                },
                'size'=>'sm',
                'options' => ['class'=>'form-control', 'placeholder'=>'Nhập giá trị...', 'autofocus' => true, 'autocomplete' => 'off']
            ]);
        },
    ],
];
?>
<div class="content-wrapper">
    <div id="addModal" class="modal fade" data-backdrop="static">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header box-gridview">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"></h4>
                </div>
                <div class="modal-body">
                </div>
            </div>

        </div>
    </div>
    <div id="confirmModal" class="modal fade" data-backdrop="static">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header box-gridview">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"></h4>
                </div>
                <div class="modal-body">
                </div>
                <div class="modal-footer">
                </div>
            </div>

        </div>
    </div>
    <section class="content-header">
        <p class="breadcrumb box-gridview">Cấu hình các đơn vị theo tham số</p>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-xs-12 col-md-12">
                            <?php
                                echo Select2::widget([
                                    'model' => $searchModel,
                                    'attribute' => 'id_thamso',
                                    'data' => ArrayHelper::map($list,'id',function($data) {
                                        return '['.$data['id'].'] --- '.$data['mota'];
                                    }),
                                    'options' => ['placeholder' => 'Chọn tham số cấu hình...', 'value' => $searchModel->id_thamso, 'id'=>'id_thamso', 'selected' => true],
                                ]);
                            ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-body">
                        <?php
                        echo GridView::widget([
                            'id' => 'mygrid',
                            'dataProvider' => $dataProvider,
                            'filterModel' => $searchModel,
                            'filterSelector' => '#myPageSize, #id_thamso',
                            'autoXlFormat'=>true,
                            'columns' => $gridColumns,
                            'pjax' => true,
                            'pjaxSettings' => [
                                'neverTimeout'=>true,
                                'options' => [
                                    'enablePushState' => false,
                                    'id' => 'theDatatable',
                                ]
                            ],
                            //'floatHeader' => true,
                            //'floatOverflowContainer' => true,
                            'headerRowOptions' => ['class' => 'kartik-sheet-style'],
                            'filterRowOptions' => ['class' => 'kartik-sheet-style'],
                            'toolbar' =>  [
                                [
                                    'content' =>
                                        Html::button('<i class="fa fa-pencil-square-o"></i>', [
                                            'class' => 'btn btn-skin btn-update-grid',
                                            'data-url' => Url::to(['cauhinh/updateall']),
                                            'data-thamso' => $searchModel->id_thamso,
                                            'title' => 'Cập nhật hàng loạt',
                                        ]) . ' '.
                                        Html::a('<i class="fa fa-undo"></i>', Url::to(['cauhinh/index']), [
                                            'class' => 'btn btn-default',
                                            'title'=>'Làm mới dữ liệu',
                                            'data-pjax' => 0, 
                                        ]) . ' '.
                                        Html::activeDropDownList($searchModel, 'myPageSize', 
                                            [10 => 10, 20 => 20, 50 => 50],
                                            [
                                                'id'=>'myPageSize', 
                                                'class' => 'btn btn-skin',
                                                'style' => 'height:auto',
                                                'title'=>'Số dòng hiển thị trên trang',
                                            ]
                                        ), 
                                    'options' => ['class' => 'btn-group mr-2']
                                ],
                                '{export}',
                                '{toggleData}',
                            ],
                            'toggleDataContainer' => ['class' => 'btn-group mr-2'],
                            'export' => [
                                'fontAwesome' => true,
                            ],
                            'bordered' => true,
                            'striped' => false,
                            'condensed' => true,
                            'responsive' => true,
                            'responsiveWrap' => false,
                            'hover' => true,
                            'panel' => [
                                'type' => GridView::TYPE_PRIMARY,
                                'heading' => '<i class="fa fa-list-alt"></i>',
                                'after' => false,
                                'headingOptions' => ['class'=>'box-gridview'],
                            ],
                            'persistResize' => false,
                            'toggleDataOptions' => ['minCount' => 10],
                            'itemLabelSingle' => 'dữ liệu',
                            'itemLabelPlural' => 'dữ liệu',
                            'pager' => [
                                'options'=>['class'=>'pagination'], 
                                'prevPageLabel' => '<i class="fa fa-chevron-left" aria-hidden="true"></i>',
                                'nextPageLabel' => '<i class="fa fa-chevron-right" aria-hidden="true"></i>', 
                                'firstPageLabel'=>'<i class="fa fa-fast-backward" aria-hidden="true"></i>', 
                                'lastPageLabel'=>'<i class="fa fa-fast-forward" aria-hidden="true"></i>', 
                                'nextPageCssClass'=>'next',
                                'prevPageCssClass'=>'prev',
                                'firstPageCssClass'=>'first',
                                'lastPageCssClass'=>'last',
                                'maxButtonCount'=>4,
                            ],
                        ]);
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<?php $this->registerJs( '$(document).on("pjax:end", function() { var btns = $("[data-toggle=\'tooltip\']"); if (btns.length) { btns.tooltip(); } }); '); ?>