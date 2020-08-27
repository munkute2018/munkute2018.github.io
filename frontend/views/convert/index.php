<?php
use yii\helpers\Url;
use yii\helpers\Html;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use common\models\ThuVien;
use common\models\HisWork;
use common\models\Dmthongtuhis;
use kartik\grid\GridView;
use kartik\export\ExportMenu;
use yii\widgets\LinkPager;
use yii\widgets\Pjax;
use kartik\editable\Editable;

$flag_phienban = ThuVien::checkIsL2ByIDPhieu($id_phieu);
$hiswork = HisWork::findOne($id_phieu);
if($flag_phienban){ //Phiên bản L2
    $selectColumns = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30];
    $exportColumns = [
        [
            'attribute' => 'id_donvi',
            'label' => 'DVTT',
            'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
        ],
        [
            'attribute' => 'id_dichvu',
            'label' => 'DICHVUID',
            'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
        ],
        [
            'attribute' => 'ma_dichvu',
            'label' => 'MADICHVU',
            'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
        ],
        [
            'attribute' => 'ten_dichvu',
            'label' => 'TENDICHVU',
            'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
        ],
        [
            'attribute' => 'nhomdichvuid',
            'label' => 'NHOMDICHVUID',
            'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
        ],
        [
            'attribute' => 'manhomdichvu',
            'label' => 'MANHOMDICHVU',
            'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
        ],
        [
            'attribute' => 'nhom_mabhyt_id',
            'label' => 'NHOM_MABHYT_ID',
            'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
        ],
        [
            'attribute' => 'madmbyt',
            'label' => 'MADMBYT',
            'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
        ],
        [
            'attribute' => 'khoanmucid',
            'label' => 'KHOANMUCID',
            'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
        ],
        [
            'attribute' => 'nhomketoanid',
            'label' => 'NHOMKETOANID',
            'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
        ],
        [
            'attribute' => 'donvi',
            'label' => 'DONVI',
            'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
        ],
        [
            'attribute' => 'gia_bhyt_old',
            'label' => 'GIABHYT',
            'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
        ],
        [
            'attribute' => 'giadichvu',
            'label' => 'GIADICHVU',
            'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
        ],
        [
            'attribute' => 'gia_vp_old',
            'label' => 'GIANHANDAN',
            'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
        ],
        [
            'attribute' => 'gia_bhyt_new',
            'label' => 'GIABHYT_NEW',
            'visible' => $hiswork->bhyt_new == 0 ? false : true,
            'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
        ],
        [
            'attribute' => 'gia_bhyt_new',
            'label' => 'GIABHYT_NEW',
            'visible' => $hiswork->bhyt_new == 0 ? false : true,
            'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
        ],
        [
            'attribute' => 'ngayapdung_bhyt',
            'label' => 'NGAYAPDUNG_BHYT',
            'visible' => $hiswork->bhyt_new == 0 ? false : true,
            'value' => function ($model) {
                $hiswork = HisWork::findOne($model->id_phieu);
                $thongtu = Dmthongtuhis::findOne($hiswork->bhyt_new);
                if ($thongtu) {
                    return date('Y/m/d', $thongtu->posted_at);
                } else {
                    return '';
                }
            },
            'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
        ],
        [
            'attribute' => 'gia_vp_new',
            'label' => 'GIANHANDAN_NEW',
            'visible' => $hiswork->vp_new == 0 ? false : true,
            'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
        ],
        [
            'attribute' => 'ngayapdung_vp',
            'label' => 'NGAYAPDUNG_NHANDAN',
            'visible' => $hiswork->vp_new == 0 ? false : true,
            'value' => function ($model) {
                $hiswork = HisWork::findOne($model->id_phieu);
                $thongtu = Dmthongtuhis::findOne($hiswork->vp_new);
                if ($thongtu) {
                    return date('Y/m/d', $thongtu->posted_at);
                } else {
                    return '';
                }
            },
            'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
        ],
        [
            'attribute' => 'gianuocngoai',
            'label' => 'GIANUOCNGOAI',
            'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
        ],
        [
            'attribute' => 'quyetdinh',
            'label' => 'QUYETDINH',
            'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
        ],
        [
            'attribute' => 'ngaycongbo',
            'label' => 'NGAYCONGBO',
            'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
        ],
        [
            'attribute' => 'sttmau21',
            'label' => 'STTMAU21',
            'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
        ],
        [
            'attribute' => 'mabytmau21',
            'label' => 'MABYTMAU21',
            'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
        ],
        [
            'attribute' => 'loaipttt',
            'label' => 'LOAIPTTT',
            'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
        ],
        [
            'attribute' => 'matt37',
            'label' => 'MATT37',
            'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
        ],
        [
            'attribute' => 'matt4350',
            'label' => 'MATT4350',
            'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
        ],
        [
            'attribute' => 'chuyenkhoaid',
            'label' => 'CHUYENKHOAID',
            'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
        ],
        [
            'attribute' => 'tendichvubhyt',
            'label' => 'TENDICHVUBHYT',
            'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
        ],
        [
            'attribute' => 'khoa',
            'label' => 'KHOA',
            'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
        ],
        [
            'attribute' => 'ghichu',
            'label' => 'GHICHU',
            'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
        ],
        [
            'attribute' => 'status_filter',
            'label' => 'KETQUA',
            'value' => function($model) {
                switch ($model->status) {
                    case 1:
                        return 'Chuẩn';
                    break;
                    case 2:
                        return 'Không chuẩn';
                    break;
                    case 3:
                        return 'Sửa tay';
                    break;
                }
            },
            'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
        ],
    ];
}
else{ //Phiên bản L3
    $selectColumns = [0, 1, 3, 4, 5];
    $exportColumns = [
        [
            'attribute' => 'id_donvi',
            'label' => 'Mã đơn vị',
            'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
        ],
        [
            'attribute' => 'id_dichvu',
            'label' => 'Mã dịch vụ',
            'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
        ],
        [
            'attribute' => 'ma_dichvu',
            'label' => 'Mã báo cáo',
            'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
        ],
        [
            'attribute' => 'ten_dichvu',
            'label' => 'Tên dịch vụ',
            'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
        ],
        [
            'attribute' => 'gia_bhyt_new',
            'label' => 'Giá BHYT',
            'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
        ],
        [
            'attribute' => 'gia_vp_new',
            'label' => 'Giá viện phí',
            'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
        ],
        [
            'attribute' => 'status_filter',
            'label' => 'Kết quả',
            'value' => function($model) {
                switch ($model->status) {
                    case 1:
                        return 'Chuẩn';
                    break;
                    case 2:
                        return 'Không chuẩn';
                    break;
                    case 3:
                        return 'Sửa tay';
                    break;
                }
            },
            'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
        ],
    ];
}

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
            return Yii::$app->controller->renderPartial('_expand-row-convert', ['model' => $model]);
        },
        'headerOptions' => ['class' => 'kartik-sheet-style'],
        'expandOneOnly' => true,
        'visible' => $flag_phienban,
    ],
    [
        'attribute' => 'id_donvi',
        'label' => 'DVTT',
        'vAlign' => 'middle', 
        'hAlign' => 'center',
        'filterInputOptions' => ['maxlength' => 5, 'class' => 'form-control'],
        'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
        'headerOptions'=>['class'=>'kartik-sheet-style sort-number'], 
    ],
    [
        'attribute' => 'id_dichvu',
        'label' => 'Mã DV',
        'vAlign' => 'middle', 
        'hAlign' => 'center',
        'filterInputOptions' => ['maxlength' => 11, 'class' => 'form-control'],
        'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
        'headerOptions'=>['class'=>'kartik-sheet-style sort-number'],
    ],
    [
        'attribute' => 'ma_dichvu',
        'label' => 'Mã BC',
        'vAlign' => 'middle',
        'filterInputOptions' => ['maxlength' => 15, 'class' => 'form-control'],
        'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
        'headerOptions' => ['class' => 'kartik-sheet-style'],
    ],
    [
        'attribute' => 'ten_dichvu',
        'label' => 'Tên dịch vụ',
        'vAlign' => 'middle', 
        'width' => '300px',
        'filterInputOptions' => ['maxlength' => 255, 'class' => 'form-control'],
        'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
        'headerOptions' => ['class' => 'kartik-sheet-style'],
    ],
    [
        'attribute' => 'gia_bhyt_old',
        'label' => 'Giá BHYT',
        'vAlign' => 'middle', 
        'hAlign' => 'right',
        'format'=>['decimal',0],
        'filterInputOptions' => ['maxlength' => 20, 'class' => 'form-control'],
        'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
        'headerOptions'=>['class'=>'kartik-sheet-style sort-number'],
    ],
    [
        'attribute' => 'gia_vp_old',
        'label' => 'Giá VP',
        'vAlign' => 'middle', 
        'hAlign' => 'right',
        'format'=>['decimal',0],
        'filterInputOptions' => ['maxlength' => 20, 'class' => 'form-control'],
        'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
        'headerOptions'=>['class'=>'kartik-sheet-style sort-number'],
    ],
    [
        'class' => 'kartik\grid\EditableColumn',
        'attribute' => 'gia_bhyt_new',
        'label' => 'Giá BHYT mới',
        'filterInputOptions' => ['maxlength' => 20, 'class' => 'form-control'],
        'headerOptions'=>['class'=>'kartik-sheet-style sort-number'],
        'refreshGrid' => true,
        'readonly' => ($hiswork->status == 0 && $hiswork->bhyt_new != 0) ? false : true,
        'editableOptions' => [
            'header' => 'giá BHYT mới',
            'placement' => 'left',
            'inputType' => \kartik\editable\Editable::INPUT_SPIN,
            'formOptions'   => [
                'action'    => [
                    '/convert/updategia'
                ],
            ],
            'options' => [
                'pluginOptions' => ['min' => 0, 'max' => 999999999]
            ]
        ],
        'vAlign' => 'middle', 
        'hAlign' => 'right',
        'format'=>['decimal',0],
        'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
    ],
    [
        'class' => 'kartik\grid\EditableColumn',
        'attribute' => 'gia_vp_new',
        'label' => 'Giá VP mới',
        'refreshGrid' => true,
        'filterInputOptions' => ['maxlength' => 20, 'class' => 'form-control'],
        'readonly' => ($hiswork->status == 0 && $hiswork->vp_new != 0) ? false : true,
        'editableOptions' => [
            'header' => 'giá viện phí mới',
            'placement' => 'left',
            'inputType' => \kartik\editable\Editable::INPUT_SPIN,
            'formOptions'   => [
                'action'    => [
                    '/convert/updategia'
                ],
            ],
            'options' => [
                'pluginOptions' => ['min' => 0, 'max' => 999999999]
            ]
        ],
        'vAlign' => 'middle', 
        'hAlign' => 'right',
        'format'=>['decimal',0],
        'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
        'headerOptions'=>['class'=>'kartik-sheet-style sort-number'],
    ],
    [
        'attribute' => 'status_filter',
        'label' => 'Kết quả',
        'vAlign' => 'middle',
        'hAlign' => 'center',
        'width' => '120px',
        'value' => function($model) {
            switch ($model->status) {
                case 1:
                    return Html::tag('span', 'Chuẩn', [
                                        'class'=>'label label-success', 
                                        'title' => '', 
                                        'data-toggle' => 'tooltip',
                                    ]);
                break;
                case 2:
                    return Html::tag('span', 'Không chuẩn', [
                                        'class'=>'label label-warning', 
                                        'title' => ThuVien::showToolTipConvert($model->tooltip_bhyt, $model->tooltip_vp), 
                                        'data-toggle' => 'tooltip',
                                    ]);
                break;
                case 3:
                    return Html::tag('span', 'Sửa tay', [
                                        'class'=>'label label-primary', 
                                        'title' => ThuVien::showToolTipConvert($model->tooltip_bhyt, $model->tooltip_vp), 
                                        'data-toggle' => 'tooltip',
                                    ]);
                break;
            }
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => array(1 => 'Chuẩn', 2 => 'Lệch', 3 => 'Sửa tay'), 
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true, 'placeholder' => 'Chọn...'],
        ],
        'filterInputOptions' => ['placeholder' => 'Tùy chọn...', 'multiple' => true],
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
        'template' => '{reload} {update} {delete}',
        'buttons' => [
            'update' => function ($url, $model, $key) {
                return Html::a('<span class="glyphicon glyphicon-pencil"></span>',false, [
                    'class' => 'activity-edit icon-skin',
                    'title' => 'Chỉnh sửa dữ liệu', 
                    'data-toggle' => 'tooltip',
                    'data-url' => $url,
                ]);
            },
            'reload' => function ($url, $model, $key) {
                return Html::a('<span class="glyphicon glyphicon-play"></span>',false, [
                    'class' => 'activity-reload icon-skin',
                    'title' => 'Cho phép tính lại đơn giá', 
                    'data-toggle' => 'tooltip',
                    'data-url' => $url,
                    'data-dvtt' => $model->id_donvi,
                    'data-madv' => $model->id_dichvu,
                ]);
            },
            'delete' => function ($url, $model, $key) {
                return Html::a('<span class="glyphicon glyphicon-trash"></span>',false, [
                    'class' => 'activity-delete icon-skin',
                    'title' => 'Xóa dữ liệu', 
                    'data-toggle' => 'tooltip',
                    'data-url' => $url,
                    'data-confirm' => 'DVTT là <b>'.$model->id_donvi.'</b> và mã dịch vụ là <b>'.$model->id_dichvu.'</b>',
                ]);
            },
        ],
        'headerOptions' => ['class' => 'kartik-sheet-style', 'style' => 'border-bottom : 1px solid transparent; min-width:70px; max-width:70px;'],
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
        <p class="breadcrumb box-gridview">Chi tiết phiếu chuyển giá - p<?=$id_phieu;?> [<?=ThuVien::getTextCovertDVKT($id_phieu);?>]</p>
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
                            'selectedColumns' => $selectColumns,
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
                                        Html::button('<i class="fa fa-play"></i>', [
                                            'class' => 'btn btn-skin btn-reload-grid',
                                            'data-url' => Url::to(['convert/reloadall', 'id' => $id_phieu]),
                                            'title' => 'Tính lại đơn giá các dòng dữ liệu bạn chọn',
                                        ]) . ' '.
                                        Html::button('<i class="fa fa-trash"></i>', [
                                            'class' => 'btn btn-default btn-delete-grid',
                                            'data-url' => Url::to(['convert/deleteall', 'id' => $id_phieu]),
                                            'title'=>'Xóa các dòng dữ liệu bạn chọn',
                                        ]) . ' '.
                                        Html::button('<i class="fa fa-plus"></i>', [
                                            'class' => 'btn btn-skin btn-add-grid',
                                            'data-url' => Url::to(['convert/create', 'id' => $id_phieu]),
                                            'title' => 'Thêm mới',
                                        ]) . ' '.
                                        Html::a('<i class="fa fa-undo"></i>', Url::to(['convert/index', 'id' => $id_phieu]), [
                                            'class' => 'btn btn-default',
                                            'title'=>'Làm mới dữ liệu',
                                            'data-pjax' => 0, 
                                        ]) . ' '.
                                        Html::button('<i class="fa fa-upload"></i>', [
                                            'class' => 'btn btn-skin btn-import-grid',
                                            'data-url' => Url::to(['convert/import', 'id' => $id_phieu]),
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