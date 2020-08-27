<?php
return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
    ],
    'modules' => [
	   'gridview' =>  [
	        'class' => '\kartik\grid\Module'
	        // enter optional module parameters below - only if you need to  
	        // use your own export download action or custom translation 
	        // message source
	        // 'downloadAction' => 'gridview/export/download',
	        // 'i18n' => []
	    ],
	    'treemanager' =>  [
	        'class' => '\kartik\tree\Module',
	        // enter other module properties if needed
	        // for advanced/personalized configuration
	        // (refer module properties available below)
	    ]
	],
];
