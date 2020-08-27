<?php
use yii\helpers\Url;
use yii\helpers\Html;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use common\models\Huyen;
use common\models\ThuVien;
use kartik\grid\GridView;
use kartik\export\ExportMenu;
use yii\widgets\LinkPager;
use yii\widgets\Pjax;

$exportColumns = [
    [
        'class'=>'kartik\grid\SerialColumn',
        'header'=>'STT',
        'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
    ],
    [
        'attribute' => 'madonvi',
        'label' => 'Mã đơn vị',
        'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
    ],
    [
        'attribute' => 'tendonvi',
        'label' => 'Tên đơn vị',
        'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
    ],
    [
        'attribute' => 'tuyen_filter',
        'label' => 'Tuyến',
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
        'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
    ],
    [
        'attribute' => 'hang_filter',
        'label' => 'Hạng',
        'value' => function($model) {
            switch ($model->hang) {
                case 0:
                    return 'Hạng đặc biệt';
                break;
                case 1:
                    return 'Hạng 1';
                break;
                case 2:
                    return 'Hạng 2';
                break;
                case 3:
                    return 'Hạng 3';
                break;
                case 4:
                    return 'Hạng 4';
                break;
                case 5:
                    return 'Chưa xếp hạng';
                break;
            }
        },
        'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
    ],
    [
        'attribute' => 'huyen',
        'label' => 'Huyện',
        'value' => function($model) {
            return $model->huyen->huyen;
        },
        'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
    ],
    [
        'attribute' => 'phienban_filter',
        'label' => 'Phần mềm',
        'value' => function($model) {
            switch ($model->phienban) {
                case 2:
                    return 'Phần mềm khác';
                break;
                case 1:
                    return 'His L2';
                break;
                case 0:
                    return 'HIS L3';
                break;
            }
        },
        'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
    ],
    [
        'attribute' => 'id_parent',
        'label' => 'Đơn vị quản lý',
        'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
    ],
    [
        'attribute' => 'phone',
        'label' => 'Số điện thoại',
        'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
    ],
    [
        'attribute' => 'status_filter',
        'label' => 'Trạng thái',
        'value' => function($model) {
            switch ($model->status) {
                case 1:
                    return 'Hoạt động';
                break;
                case 0:
                    return 'Tạm ngưng';
                break;
            }
        },
        'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
    ],
];

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
        'class' => 'kartik\grid\ExpandRowColumn',
        'value' => function ($model, $key, $index, $column) {
            return GridView::ROW_COLLAPSED;
        },
        // 'detailUrl' => Url::to(['/site/book-details']),
        'detail' => function ($model, $key, $index, $column) {
            return Yii::$app->controller->renderPartial('_expand-row-donvi', ['model' => $model]);
        },
        'headerOptions' => ['class' => 'kartik-sheet-style'],
        'expandOneOnly' => true,
    ],
    [
        'attribute' => 'madonvi',
        'label' => 'Mã ĐV',
        'vAlign' => 'middle',
        'hAlign' => 'center',
        'width' => '80px',
        'filterInputOptions' => ['maxlength' => 5, 'class' => 'form-control'],
        'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
        'headerOptions' => ['class' => 'kartik-sheet-style sort-number'],
    ],
    [
        'attribute' => 'tendonvi',
        'label' => 'Tên ĐV',
        'vAlign' => 'middle', 
        'width' => '300px',
        'filterInputOptions' => ['maxlength' => 255, 'class' => 'form-control'],
        'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
        'headerOptions' => ['class' => 'kartik-sheet-style'],
    ],
    [
        'attribute' => 'huyen',
        'label' => 'Huyện',
        'vAlign' => 'middle',
        'width' => '155px',
        'value' => function($model) {
            return $model->huyen->huyen;
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => ArrayHelper::map(Huyen::find()->where(['status' => 1])->orderBy(['huyen' => SORT_ASC])->all(), 'id',
            function($data) {
                return $data->huyen;
            }
        ), 
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true, 'placeholder' => 'Chọn...'],
        ],
        'filterInputOptions' => ['multiple' => true],
        'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
        'headerOptions' => ['class' => 'kartik-sheet-style'],
    ],
    [
        'attribute' => 'phienban_filter',
        'label' => 'Phần mềm',
        'vAlign' => 'middle',
        'hAlign' => 'center',
        'width' => '120px',
        'value' => function($model) {
            switch ($model->phienban) {
                case 2:
                    return 'PM khác';
                break;
                case 1:
                    return 'His L2';
                break;
                case 0:
                    return 'HIS L3';
                break;
            }
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => array(0 => 'HIS L3', 1 => 'HIS L2', 2 => 'PM khác'), 
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true, 'placeholder' => 'Chọn...'],
        ],
        'filterInputOptions' => ['multiple' => true],
        'format' => 'raw',
        'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
        'headerOptions' => ['class' => 'kartik-sheet-style'],
    ],
    [
        'attribute' => 'id_parent',
        'label' => 'ĐV quản lý',
        'vAlign' => 'middle',
        'hAlign' => 'center',
        'width' => '100px',
        'filterInputOptions' => ['maxlength' => 5, 'class' => 'form-control'],
        'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
        'headerOptions' => ['class' => 'kartik-sheet-style sort-number'],
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
                    return Html::tag('span', 'Hoạt động', [
                                        'class'=>'label label-primary', 
                                        'title' => '', 
                                        'data-toggle' => 'tooltip',
                                    ]);
                break;
                case 0:
                    return Html::tag('span', 'Tạm ngưng', [
                                        'class'=>'label label-warning', 
                                        'title' => '', 
                                        'data-toggle' => 'tooltip',
                                    ]);
                break;
            }
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => array(0 => 'Tạm ngưng', 1 => 'Hoạt động'), 
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
        <p class="breadcrumb box-gridview">Danh mục đơn vị cơ sở khám chữa bệnh</p>
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
                            'columns' => $exportColumns,
                            'initProvider' => true,
                            'columnSelectorOptions'=>[
                                'label' => 'Chọn...',
                            ],
                            'hiddenColumns' => [],
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
                                            'data-url' => Url::to(['donvi/create']),
                                            'title' => 'Thêm mới',
                                        ]) . ' '.
                                        Html::a('<i class="fa fa-undo"></i>', Url::to(['donvi/index']), [
                                            'class' => 'btn btn-default',
                                            'title'=>'Làm mới dữ liệu',
                                            'data-pjax' => 0, 
                                        ]) . ' '.
                                        Html::button('<i class="fa fa-upload"></i>', [
                                            'class' => 'btn btn-skin btn-import-grid',
                                            'data-url' => Url::to(['donvi/import']),
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
                            'itemLabelSingle' => 'đơn vị',
                            'itemLabelPlural' => 'đơn vị',
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