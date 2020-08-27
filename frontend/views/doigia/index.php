<?php
use yii\helpers\Url;
use yii\helpers\Html;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use common\models\ThuVien;
use common\models\HisWork;
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
        'attribute' => 'id',
        'label' => 'Mã phiếu',
        'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
    ],
    [
        'attribute' => 'bhyt_old',
        'label' => 'Quyết định BHYT cũ',
        'value' => function($model) {
            return ThuVien::getThongTuByID($model->bhyt_old);
        },
        'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
    ],
    [
        'attribute' => 'bhyt_new',
        'label' => 'Quyết định BHYT mới',
        'value' => function($model) {
            return ThuVien::getThongTuByID($model->bhyt_new);
        },
        'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
    ],
    [
        'attribute' => 'vp_old',
        'label' => 'Quyết định VP cũ',
        'value' => function($model) {
            return ThuVien::getThongTuByID($model->vp_old);
        },
        'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
    ],
    [
        'attribute' => 'vp_new',
        'label' => 'Quyết định VP mới',
        'value' => function($model) {
            return ThuVien::getThongTuByID($model->vp_new);
        },
        'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
    ],
    [
        'attribute' => 'phienban',
        'label' => 'Phần mềm HIS',
        'value' => function($model) {
            switch ($model->phienban) {
                case 0:
                    return 'HIS L3';
                break;
                case 1:
                    return 'HIS L2';
                break;
            }
        },
        'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
    ],
    [
        'attribute' => 'ghichu',
        'label' => 'Ghi chú',
        'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
    ],
    [
        'attribute' => 'created_at_range',
        'label' => 'Ngày tạo',
        'value' => function ($model) {
            if (extension_loaded('intl')) {
                return Yii::t('app', '{0, date, MMMM dd, YYYY HH:mm}', [$model->created_at]);
            } else {
                return date('d/m/Y', $model->created_at);
            }
        },
        'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
    ],
    [
        'attribute' => 'status_filter',
        'label' => 'Trạng thái',
        'value' => function($model) {
            switch ($model->status) {
                case 1:
                    return 'Xác nhận';
                break;
                case 0:
                    return 'Tạo mới';
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
        'attribute' => 'id',
        'label' => 'Mã',
        'vAlign' => 'middle',
        'hAlign' => 'center',
        'width' => '70px',
        'filterInputOptions' => ['maxlength' => 11, 'class' => 'form-control'],
        'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
        'headerOptions' => ['class' => 'kartik-sheet-style sort-number'],
    ],
    [
        'attribute' => 'bhyt_old_filter',
        'label' => 'QĐ BHYT cũ',
        'vAlign' => 'middle',
        'width' => '150px',
        'value' => function($model) {
            return ThuVien::getThongTuByID($model->bhyt_old);
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => ArrayHelper::map(HisWork::find(['id_user' => Yii::$app->user->getId()])->orderBy(['bhyt_old' => SORT_DESC])->select(['bhyt_old'])->distinct()->all(), 'bhyt_old',
            function($data) {
                return ThuVien::getThongTuByID($data->bhyt_old, true);
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
        'attribute' => 'bhyt_new_filter',
        'label' => 'QĐ BHYT mới',
        'vAlign' => 'middle',
        'width' => '150px',
        'value' => function($model) {
            return ThuVien::getThongTuByID($model->bhyt_new);
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => ArrayHelper::map(HisWork::find(['id_user' => Yii::$app->user->getId()])->orderBy(['bhyt_new' => SORT_DESC])->select(['bhyt_new'])->distinct()->all(), 'bhyt_new',
            function($data) {
                return ThuVien::getThongTuByID($data->bhyt_new, true);
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
        'attribute' => 'vp_old_filter',
        'label' => 'QĐ VP cũ',
        'vAlign' => 'middle',
        'width' => '150px',
        'value' => function($model) {
            return ThuVien::getThongTuByID($model->vp_old);
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => ArrayHelper::map(HisWork::find(['id_user' => Yii::$app->user->getId()])->orderBy(['vp_old' => SORT_DESC])->select(['vp_old'])->distinct()->all(), 'vp_old',
            function($data) {
                return ThuVien::getThongTuByID($data->vp_old, true);
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
        'attribute' => 'vp_new_filter',
        'label' => 'QĐ VP mới',
        'vAlign' => 'middle',
        'width' => '150px',
        'value' => function($model) {
            return ThuVien::getThongTuByID($model->vp_new);
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => ArrayHelper::map(HisWork::find(['id_user' => Yii::$app->user->getId()])->orderBy(['vp_new' => SORT_DESC])->select(['vp_new'])->distinct()->all(), 'vp_new',
            function($data) {
                return ThuVien::getThongTuByID($data->vp_new, true);
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
        'label' => 'HIS',
        'vAlign' => 'middle',
        'hAlign' => 'center',
        'width' => '120px',
        'value' => function($model) {
            switch ($model->phienban) {
                case 0:
                    return 'His L3';
                break;
                case 1:
                    return 'His L2';
                break;
            }
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => array(0 => 'His L3', 1 => 'His L2'), 
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true, 'placeholder' => 'Chọn...'],
        ],
        'filterInputOptions' => ['multiple' => true],
        'format' => 'raw',
        'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
        'headerOptions' => ['class' => 'kartik-sheet-style'],
    ],
    [
        'attribute' => 'status_filter',
        'label' => 'Trạng thái',
        'vAlign' => 'middle',
        'hAlign' => 'center',
        'width' => '120px',
        'value' => function($model) {
            switch ($model->status) {
                case 0:
                    return Html::tag('span', 'Tạo mới', ['class'=>'label label-warning']);
                break;
                case 1:
                    return Html::tag('span', 'Xác nhận', ['class'=>'label label-success']);
                break;
            }
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => array(0 => 'Tạo mới', 1 => 'Xác nhận'), 
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true, 'placeholder' => 'Chọn...'],
        ],
        'filterInputOptions' => ['multiple' => true],
        'format' => 'raw',
        'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
        'headerOptions' => ['class' => 'kartik-sheet-style'],
    ],
    [
        'attribute' => 'created_at_range',
        'label' => 'Ngày tạo',
        'vAlign' => 'middle',
        'hAlign' => 'center',
        'width' => '188px',
        'value' => function ($model) {
            if (extension_loaded('intl')) {
                return Yii::t('app', '{0, date, MMMM dd, YYYY HH:mm}', [$model->created_at]);
            } else {
                return date('d/m/Y', $model->created_at);
            }
        },
        'filterType' => GridView::FILTER_DATE_RANGE,
        'filterWidgetOptions' => ([ 
            'attribute' => 'created_at_range',
            'presetDropdown' => false,
            'convertFormat' => false,
            'pluginOptions' => [
              'separator' => ' - ',
              'format' => 'DD/MM/YYYY',
              'locale' => [
                    'format' => 'DD/MM/YYYY'
                ],
            ],
            'pluginEvents' => [
              "apply.daterangepicker" => "function() { apply_filter('only_date') }",
            ],
        ]),
        'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
        'headerOptions'=>['class'=>'kartik-sheet-style sort-number'],
    ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'dropdownOptions' => ['class' => 'float-right'],
        'vAlign' => 'middle',
        'header' => '',
        'hAlign' => 'center',
        'mergeHeader' => false,
        'template' => '{view} {lock}',
        'buttons' => [
            'lock' => function ($url, $model, $key) {
                if($model->status == 0){
                    return Html::a('<span class="glyphicon glyphicon-lock"></span>',false, [
                        'class' => 'activity-lock icon-skin',
                        'title' => 'Xác nhận phiếu hoàn thành', 
                        'data-toggle' => 'tooltip',
                        'data-url' => $url,
                        'data-phieu' => $model->id,
                    ]);
                }
                else{
                    return Html::a('<span class="glyphicon glyphicon-scissors"></span>',false, [
                        'class' => 'activity-unlock icon-skin',
                        'title' => 'Mở khóa để điều chỉnh phiếu', 
                        'data-toggle' => 'tooltip',
                        'data-url' => $url,
                        'data-phieu' => $model->id,
                    ]);
                }
            },
            'view' => function ($url, $model, $key) {
                return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', Url::to(['convert/index', 'id' => $model->id]), [
                    'class' => 'activity-image icon-skin',
                    'title' => 'Xem chi tiết phiếu',  
                    'data-toggle' => 'tooltip',
                    'data-pjax' => '0',

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
        <p class="breadcrumb box-gridview">Danh sách phiếu thực hiện việc chuyển đổi giá dịch vụ kỹ thuật HIS </p>
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
                                            'data-url' => Url::to(['doigia/create']),
                                            'title' => 'Thêm mới',
                                        ]) . ' '.
                                        Html::a('<i class="fa fa-undo"></i>', Url::to(['doigia/index']), [
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
                            'itemLabelSingle' => 'phiếu',
                            'itemLabelPlural' => 'phiếu',
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