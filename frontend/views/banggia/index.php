<?php
use yii\helpers\Url;
use yii\helpers\Html;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use common\models\ThuVien;
use common\models\Dmthongtuhis;
use kartik\grid\GridView;
use kartik\export\ExportMenu;
use yii\widgets\LinkPager;
use yii\widgets\Pjax;
$info = Dmthongtuhis::findOne($id_thongtu);
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
        'attribute' => 'stt',
        'label' => 'Mã TT37',
        'width' => '100px',
        'vAlign' => 'middle', 
        'hAlign' => 'center',
        'filterInputOptions' => ['maxlength' => 255, 'class' => 'form-control'],
        'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
        'headerOptions'=>['class'=>'kartik-sheet-style sort-number'],
    ],
    [
        'attribute' => 'type_filter',
        'label' => 'Loại',
        'vAlign' => 'middle',
        'hAlign' => 'center',
        'width' => '140px',
        'value' => function($model) {
            switch ($model->type) {
                case 1:
                    return 'Công khám';
                break;
                case 2:
                    return 'PTTT-VLTL';
                break;
                case 3:
                    return 'Tiền giường';
                break;
            }
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => array(1 => 'Công khám', 2 => 'PTTT-VLTL', 3 => 'Tiền giường'), 
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true, 'placeholder' => 'Chọn...'],
        ],
        'filterInputOptions' => ['multiple' => true],
        'format' => 'raw',
        'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
        'headerOptions' => ['class' => 'kartik-sheet-style'],
    ],
    [
        'attribute' => 'name',
        'label' => 'Nội dung',
        'vAlign' => 'middle', 
        'filterInputOptions' => ['maxlength' => 255, 'class' => 'form-control'],
        'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
        'headerOptions' => ['class' => 'kartik-sheet-style'],
    ],
    [
        'attribute' => 'dongia',
        'label' => 'Đơn giá',
        'vAlign' => 'middle', 
        'hAlign' => 'right',
        'width' => '100px',
        'filterInputOptions' => ['maxlength' => 20, 'class' => 'form-control'],
        'format'=>['decimal',0],
        'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
        'headerOptions'=>['class'=>'kartik-sheet-style sort-number'],
    ],
    [
        'attribute' => 'ghichu',
        'label' => 'Ghi chú',
        'vAlign' => 'middle', 
        'hAlign' => 'center',
        'width' => '100px',
        'filterInputOptions' => ['maxlength' => 255, 'class' => 'form-control'],
        'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
        'headerOptions' => ['class' => 'kartik-sheet-style'],
    ],
    [
        'attribute' => 'status_filter',
        'label' => 'Trạng thái',
        'vAlign' => 'middle',
        'hAlign' => 'center',
        'width' => '140px',
        'value' => function($model) {
            switch ($model->status) {
                case 1:
                    return Html::tag('span', 'Hoạt động', ['class'=>'label label-primary']);
                break;
                case 0:
                    return Html::tag('span', 'Tạm ngưng', ['class'=>'label label-warning']);
                break;
            }
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => array(1 => "Hoạt động", 0 => 'Tạm ngưng'), 
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true, 'placeholder' => 'Chọn...'],
        ],
        'filterInputOptions' => ['multiple' => true],
        'format' => 'raw',
        'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
        'headerOptions' => ['class' => 'kartik-sheet-style'],
    ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'dropdownOptions' => ['class' => 'float-right'],
        'vAlign' => 'middle',
        'header' => '',
        'hAlign' => 'center',
        'mergeHeader' => false,
        'template' => '{update}',
        'buttons' => [
            'update' => function ($url, $model, $key) {
                return Html::a('<span class="glyphicon glyphicon-pencil"></span>',false, [
                    'class' => 'activity-edit icon-skin',
                    'title' => 'Chỉnh sửa dữ liệu', 
                    'data-toggle' => 'tooltip',
                    'data-url' => $url,
                ]);
            },
        ],
        'headerOptions' => ['class' => 'kartik-sheet-style', 'style' => 'border-bottom : 1px solid transparent; min-width:50px; max-width:50px;'],
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
    <section class="content-header">
        <p class="breadcrumb box-gridview">Danh mục quyết định (thông tư) về thay đổi giá dịch vụ kỹ thuật HIS<?=$info ? ' - '.$info->name : '';?></p>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <?php
                        echo ExportMenu::widget([
                            'dataProvider' => $dataProvider,
                            'autoXlFormat'=>true,
                            'columns' => $gridColumns,
                            'initProvider' => true,
                            'columnSelectorOptions'=>[
                                'label' => 'Chọn...',
                            ],
                            'hiddenColumns' => [0],
                            'dropdownOptions' => [
                                'label' => 'Xuất tất cả',
                                'class' => 'btn btn-default',

                            ],
                            'exportConfig' => [
                                ExportMenu::FORMAT_PDF => ['filename' => 'grid-export_'.date('d_m_Y')],
                                ExportMenu::FORMAT_TEXT => ['filename' => 'grid-export_'.date('d_m_Y')],
                                ExportMenu::FORMAT_HTML => ['filename' => 'grid-export_'.date('d_m_Y')],
                                ExportMenu::FORMAT_CSV => ['filename' => 'grid-export_'.date('d_m_Y')],
                                ExportMenu::FORMAT_EXCEL => ['filename' => 'grid-export_'.date('d_m_Y')],
                            ],
                            'filename' => 'grid-export_'.date('d_m_Y')

                        ]);
                        ?>
                    </div>
                    <div class="box-body">
                        <?php
                        echo GridView::widget([
                            'id' => 'mygrid',
                            'dataProvider' => $dataProvider,
                            'filterModel' => $searchModel,
                            'filterSelector' => '#myPageSize',
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
                                        Html::button('<i class="fa fa-plus"></i>', [
                                            'class' => 'btn btn-skin btn-add-grid',
                                            'data-url' => Url::to(['banggia/create', 'id' => $id_thongtu]),
                                            'title' => 'Thêm mới',
                                        ]) . ' '.
                                        Html::a('<i class="fa fa-undo"></i>', Url::to(['banggia/index', 'id' => $id_thongtu]), [
                                            'class' => 'btn btn-default',
                                            'title'=>'Làm mới dữ liệu',
                                            'data-pjax' => 0, 
                                        ]) . ' '.
                                        Html::button('<i class="fa fa-upload"></i>', [
                                            'class' => 'btn btn-skin btn-import-grid',
                                            'data-url' => Url::to(['banggia/import', 'id' => $id_thongtu]),
                                            'title' => 'Import dữ liệu (Excel)',
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