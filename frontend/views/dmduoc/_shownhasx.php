<?php
use yii\helpers\Url;
use yii\helpers\Html;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use kartik\grid\GridView;
use kartik\export\ExportMenu;
use yii\widgets\LinkPager;
use yii\widgets\Pjax;
use kartik\editable\Editable;
$gridColumns = [
    [
        'class' => 'kartik\grid\EditableColumn',
        'attribute' => 'mansx',
        'label' => 'Mã nhà sản xuất',
        'vAlign' => 'middle', 
        'hAlign' => 'center',
        'refreshGrid' => true,
        'width' => '200px',
        'filterInputOptions' => ['maxlength' => 20, 'class' => 'form-control'],
        'editableOptions' =>function ($model, $key, $index) {
          return [
            'name' => 'mansx',
            'asPopover' => false,
            'inputType' => \kartik\editable\Editable::INPUT_SPIN,
            'resetButton' => ['class'=>'hide'],
            'formOptions'   => [
                'action'    => [
                    '/dmduoc/updatenhasx'
                ],
            ],
            'options' => [
                'pluginOptions' => ['min' => 1, 'max' => 999999999],
                'options' => ['class'=>'form-control', 'placeholder'=>'Nhập mã...', 'autofocus' => true, 'autocomplete' => 'off'],
            ],
            'value' => '',
            'afterInput'=>function($form, $widget) use ($model) {
                echo '<input type="hidden" name="tennsx" value="'.$model['tennsx'].'">';
                echo '<input type="hidden" name="idphieu" value="'.$model['idphieu'].'">';
            },
          ];
        },
        'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
        'headerOptions'=>['class'=>'kartik-sheet-style sort-number'],
    ],
    [
        'attribute' => 'tennsx',
        'label' => 'Tên nhà sản xuất',
        'vAlign' => 'middle', 
        'width' => '400px',
        'filterInputOptions' => ['maxlength' => 50, 'class' => 'form-control'],
        'contentOptions' => ['cellFormat' => DataType::TYPE_STRING],
        'headerOptions'=>['class'=>'kartik-sheet-style'],
    ],
];
?>
<section class="section">
<div class="box-body">
  <?php
    echo GridView::widget([
      'id' => 'gridnsx',
      'dataProvider' => $nhasxProvider,
      'autoXlFormat'=>true,
      'columns' => $gridColumns,
      'pjax' => true,
      'pjaxSettings' => [
          'neverTimeout'=>true,
          'options' => [
              'enablePushState' => false,
              'id' => 'shownsx',
          ]
      ],
      //'floatHeader' => true,
      //'floatOverflowContainer' => true,
      'headerRowOptions' => ['class' => 'kartik-sheet-style'],
      'filterRowOptions' => ['class' => 'kartik-sheet-style'],
      'toolbar' =>  [
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
</section>