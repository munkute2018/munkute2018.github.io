<?php
use \yii\web\Request;
$request = new Request();
$baseUrl = str_replace('/frontend/web', '', $request->baseUrl);
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'as globalAccess'=>[
        'class'=>'\common\behaviors\GlobalAccessBehavior',
        
        'rules'=>[
            [
                'controllers'=>['donvi', 'dmduoc', 'huyen', 'quyetdinh', 'banggia', 'doigia', 'convert', 'his', 'menu', 'nhasx', 'nuocsx', 'duongdung', 'cauhinh', 'thamso', 'phanquyen', 'duoc', 'phieuduoc', 'treemanager/node', 'gridview/export'],
                'allow' => true,
                'roles' => ['@'],
            ],
            [
                'controllers'=>['site'],
                'allow' => true,
                'roles' => ['@'],
                'actions'=>['index', 'errorrole', 'logout']
            ],
            [
                'controllers'=>['site'],
                'allow' => true,
                'roles' => ['?', '@'],
                'actions'=>['error']
            ],
            [
                'controllers'=>['site'],
                'actions'=>['login'],
                'allow' =>TRUE,
                'roles'=>['?'],
            ]
                
        ]
    ],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-frontend',
            'enableCsrfValidation'=>false,
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'authTimeout' => 18000,
            'identityCookie' => ['name' => '_identity-frontend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the frontend
            'name' => 'advanced-frontend',
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
        ],
        
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],

        'request' => [
            'baseUrl' => $baseUrl,
        ],
        
        'urlManager' => [
            'baseUrl' => $baseUrl,
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                '' => 'site/index',
                'dang-nhap' => 'site/login',
                'dang-xuat' => 'site/logout',
                'danh-sach-menu' => 'menu/index',

                'dm-don-vi' => 'donvi/index',
                'dm-don-vi/them-du-lieu' => 'donvi/create',
                'dm-don-vi/sua-du-lieu/don-vi-<id:\d+>' => 'donvi/update',
                'dm-don-vi/import' => 'donvi/import',
                'dm-don-vi/formvalidate' => 'donvi/formvalidate',
                'dm-don-vi/formeditvalidate/don-vi-<id:\d+>' => 'donvi/formeditvalidate',
                'dm-don-vi/importvalidate' => 'donvi/importvalidate',

                'dm-huyen' => 'huyen/index',
                'dm-huyen/them-du-lieu' => 'huyen/create',
                'dm-huyen/sua-du-lieu/huyen-<id:\d+>' => 'huyen/update',
                'dm-huyen/import' => 'huyen/import',
                'dm-huyen/formvalidate' => 'huyen/formvalidate',
                'dm-huyen/formeditvalidate/huyen-<id:\d+>' => 'huyen/formeditvalidate',
                'dm-huyen/importvalidate' => 'huyen/importvalidate',

                'danh-muc-quyet-dinh' => 'quyetdinh/index',
                'danh-muc-quyet-dinh/them-du-lieu' => 'quyetdinh/create',
                'danh-muc-quyet-dinh/sua-du-lieu/qd-<id:\d+>' => 'quyetdinh/update',
                'danh-muc-quyet-dinh/import' => 'quyetdinh/import',
                'danh-muc-quyet-dinh/formvalidate' => 'quyetdinh/formvalidate',
                'danh-muc-quyet-dinh/formeditvalidate/qd-<id:\d+>' => 'quyetdinh/formeditvalidate',
                'danh-muc-quyet-dinh/importvalidate' => 'quyetdinh/importvalidate',

                'chi-tiet-quyet-dinh/bang-gia-qd<id:\d+>' => 'banggia/index',
                'chi-tiet-quyet-dinh/them-du-lieu/bang-gia-qd<id:\d+>' => 'banggia/create',
                'chi-tiet-quyet-dinh/sua-du-lieu/chi-tiet-<id:\d+>' => 'banggia/update',
                'chi-tiet-quyet-dinh/import/bang-gia-qd<id:\d+>' => 'banggia/import',
                'chi-tiet-quyet-dinh/formvalidate/bang-gia-qd<id:\d+>' => 'banggia/formvalidate',
                'chi-tiet-quyet-dinh/formeditvalidate/chi-tiet-<id:\d+>' => 'banggia/formeditvalidate',
                'chi-tiet-quyet-dinh/importvalidate' => 'banggia/importvalidate',

                'phieu-chuyen-doi-gia' => 'doigia/index',
                'phieu-chuyen-doi-gia/them-du-lieu' => 'doigia/create',
                'phieu-chuyen-doi-gia/load-child-cmb-bhyt' => 'doigia/loadchildbhyt',
                'phieu-chuyen-doi-gia/load-child-cmb-vp' => 'doigia/loadchildvp',
                'phieu-chuyen-doi-gia/formvalidate' => 'doigia/formvalidate',
                'phieu-chuyen-doi-gia/khoa-du-lieu/ma-phieu-p<id:\d+>' => 'doigia/lock',

                'chi-tiet-chuyen-doi-gia/ma-phieu-p<id:\d+>' => 'convert/index',
                'chi-tiet-chuyen-doi-gia/them-du-lieu/ma-phieu-p<id:\d+>' => 'convert/create',
                'chi-tiet-chuyen-doi-gia/sua-du-lieu/chi-tiet-<id:\d+>' => 'convert/update',
                'chi-tiet-chuyen-doi-gia/tinh-don-gia/chi-tiet-<id:\d+>' => 'convert/reload',
                'chi-tiet-chuyen-doi-gia/tinh-don-gia-tat-ca/ma-phieu-<id:\d+>' => 'convert/reloadall',
                'chi-tiet-chuyen-doi-gia/xoa-du-lieu/chi-tiet-<id:\d+>' => 'convert/delete',
                'chi-tiet-chuyen-doi-gia/xoa-du-lieu-tat-ca/ma-phieu-<id:\d+>' => 'convert/deleteall',
                'chi-tiet-chuyen-doi-gia/import/ma-phieu-p<id:\d+>' => 'convert/import',
                'chi-tiet-chuyen-doi-gia/formvalidate/ma-phieu-p<id:\d+>' => 'convert/formvalidate',
                'chi-tiet-chuyen-doi-gia/importvalidate' => 'convert/importvalidate',
                'chi-tiet-chuyen-doi-gia/formeditvalidate/chi-tiet-<id:\d+>' => 'convert/formeditvalidate',

                'phan-quyen-menu' => 'phanquyen/index',
                'load-phan-quyen' => 'phanquyen/loadquyen',
                'save-phan-quyen' => 'phanquyen/savequyen',

                'dm-nha-san-xuat' => 'nhasx/index',
                'dm-nha-san-xuat/them-du-lieu' => 'nhasx/create',
                'dm-nha-san-xuat/sua-du-lieu/ma-nsx-<id:\d+>' => 'nhasx/update',
                'dm-nha-san-xuat/import' => 'nhasx/import',
                'dm-nha-san-xuat/formvalidate' => 'nhasx/formvalidate',
                'dm-nha-san-xuat/formeditvalidate/ma-nsx-<id:\d+>' => 'nhasx/formeditvalidate',
                'dm-nha-san-xuat/importvalidate' => 'nhasx/importvalidate',
                'dm-nha-san-xuat/xoa-du-lieu/chi-tiet-<id:\d+>' => 'nhasx/delete',

                'dm-nuoc-san-xuat' => 'nuocsx/index',
                'dm-nuoc-san-xuat/them-du-lieu' => 'nuocsx/create',
                'dm-nuoc-san-xuat/sua-du-lieu/ma-nsx-<id:\d+>' => 'nuocsx/update',
                'dm-nuoc-san-xuat/import' => 'nuocsx/import',
                'dm-nuoc-san-xuat/formvalidate' => 'nuocsx/formvalidate',
                'dm-nuoc-san-xuat/formeditvalidate/ma-nsx-<id:\d+>' => 'nuocsx/formeditvalidate',
                'dm-nuoc-san-xuat/importvalidate' => 'nuocsx/importvalidate',
                'dm-nuoc-san-xuat/xoa-du-lieu/chi-tiet-<id:\d+>' => 'nuocsx/delete',

                'dm-duong-dung' => 'duongdung/index',
                'dm-duong-dung/them-du-lieu' => 'duongdung/create',
                'dm-duong-dung/sua-du-lieu/ma-dd-<id:\w+>' => 'duongdung/update',
                'dm-duong-dung/import' => 'duongdung/import',
                'dm-duong-dung/formvalidate' => 'duongdung/formvalidate',
                'dm-duong-dung/formeditvalidate/ma-dd-<id:\w+>' => 'duongdung/formeditvalidate',
                'dm-duong-dung/importvalidate' => 'duongdung/importvalidate',
                'dm-duong-dung/xoa-du-lieu/chi-tiet-<id:\w+>' => 'duongdung/delete',

                'cau-hinh-tham-so-don-vi' => 'cauhinh/index',
                'cau-hinh-tham-so-don-vi/cap-nhat-hang-loat' => 'cauhinh/updateall',

                'phieu-xu-ly-dm-duoc' => 'phieuduoc/index',
                'phieu-xu-ly-dm-duoc/phienban-<id:\d+>' => 'phieuduoc/index',
                'phieu-xu-ly-dm-duoc/them-du-lieu' => 'phieuduoc/create',
                'phieu-xu-ly-dm-duoc/formvalidate' => 'phieuduoc/formvalidate',
                'phieu-xu-ly-dm-duoc/khoa-du-lieu/ma-phieu-p<id:\d+>' => 'phieuduoc/lock',

                'chi-tiet-danh-muc-duoc/ma-phieu-p<id:\d+>' => 'dmduoc/index',
                'chi-tiet-danh-muc-duoc/them-du-lieu/ma-phieu-p<id:\d+>' => 'dmduoc/create',
                'chi-tiet-danh-muc-duoc/sua-du-lieu/chi-tiet-<id:\d+>' => 'dmduoc/update',
                'chi-tiet-danh-muc-duoc/xoa-du-lieu/chi-tiet-<id:\d+>' => 'dmduoc/delete',
                'chi-tiet-danh-muc-duoc/xoa-du-lieu-tat-ca/ma-phieu-<id:\d+>' => 'dmduoc/deleteall',
                'chi-tiet-danh-muc-duoc/import/ma-phieu-p<id:\d+>' => 'dmduoc/import',
                'chi-tiet-danh-muc-duoc/formvalidate/ma-phieu-p<id:\d+>' => 'dmduoc/formvalidate',
                'chi-tiet-danh-muc-duoc/importvalidate' => 'dmduoc/importvalidate',
                'chi-tiet-danh-muc-duoc/formeditvalidate/chi-tiet-<id:\d+>' => 'dmduoc/formeditvalidate',
                'chi-tiet-danh-muc-duoc/load-cmb-phan-loai' => 'doigia/loadphanloai',
                'chi-tiet-danh-muc-duoc/load-cmb-vi-tri/<vitri:\w+>' => 'doigia/loadvitri',
                'chi-tiet-danh-muc-duoc/load-cmb-vi-tri/<vitri:\w+>' => 'doigia/loadvitri',
                'chi-tiet-danh-muc-duoc/nha-san-xuat-bo-sung/chi-tiet-<id:\d+>' => 'dmduoc/shownhasx',
                'chi-tiet-danh-muc-duoc/update-nha-san-xuat' => 'dmduoc/updatenhasx',
                'chi-tiet-danh-muc-duoc/nuoc-san-xuat-bo-sung/chi-tiet-<id:\d+>' => 'dmduoc/shownuocsx',
                'chi-tiet-danh-muc-duoc/update-nuoc-san-xuat' => 'dmduoc/updatenuocsx',
            ],
        ],
    ],
    'params' => $params,
    'timeZone' => 'Asia/Ho_Chi_Minh',
];
