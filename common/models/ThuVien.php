<?php
namespace common\models;

use Yii;
use yii\db\Query;
use common\models\User;
use common\models\Dmthongtuhis;
use common\models\HisWork;
use common\models\DonVi;
use common\models\DuocTicket;
use common\models\ThamSo_DonVi;
use common\models\Avatar;
use common\models\Menu;
use common\models\DmDuoc;
use common\models\Dmbanggia;
use common\models\Dmthamso;
use yii\data\SqlDataProvider;
class ThuVien
{
    public function getAvatarUserByID($id=0){
        $info = Avatar::findAvatar($id);
        if($info){
            return $info->link;
        }
        else{
            return "default.png";
        }
    }

    public function getThongTuByID($id=0, $filter = false){
        if($id == 0){
            if($filter)
                return "[-Không đổi-]";
            else
                return "";
        }
        else{
            $info = Dmthongtuhis::findOne(['id' => $id]);
            if($info){
                return $info->name;
            }
            else{
                if($filter)
                    return "[-Rỗng-]";
                else
                    return "";
            }
        }
    }

    public function getTextCovertDVKT($id){
        $info = HisWork::findOne(['id' => $id]);
        $bhyt = "Giá bảo hiểm: ";
        $vp = "Giá viện phí: ";
        $phienban = "Phiên bản: ";
        if($info){
            if($info->bhyt_new==0){
                $bhyt.="Không thay đổi";
            }
            else{
                $info1 = Dmthongtuhis::findOne(['id' => $info->bhyt_old]);
                $info2 = Dmthongtuhis::findOne(['id' => $info->bhyt_new]);
                if($info1 && $info2){
                    $bhyt.=$info1->name.' <i class="fa fa-arrow-right" aria-hidden="true"></i> '.$info2->name;
                }
            }
            if($info->vp_new==0){
                $vp.="Không thay đổi";
            }
            else{
                $info3 = Dmthongtuhis::findOne(['id' => $info->vp_old]);
                $info4 = Dmthongtuhis::findOne(['id' => $info->vp_new]);
                if($info3 && $info4){
                    $bhyt.=$info3->name.' <i class="fa fa-arrow-right" aria-hidden="true"></i> '.$info4->name;
                }
            }
            if($info->phienban==0){
                $phienban.="L3";
            }
            else{
                $phienban.="L2";
            }
            return $bhyt.' | '.$vp.' | '.$phienban;
        }
        else{
            return "";
        }
    }

    public function covertDonGia($ttmoi, $ttcu, $donvi, $mabaocao, $tendv, $giacu){
        //Tỷ lệ thanh toán của các đơn vị
        $tyle = 1;
        if($info_dv = ThamSo_DonVi::findOne(['id_thamso' => 'HIS_DONVI_TYLE_DVKT']))
            $tyle = $info_dv->giatri/100;
        //Lấy text lần 2 quy định trong tham số đơn vị
        $text_two = '';
        if($info_dmthamso = Dmthamso::findOne(['id' => 'HIS_DOIGIA_DVKT_LAN2']))
            $text_two = strtolower($info_dmthamso->giatri);
        $solan = 1;
        $arr_texttwo = explode('|', $text_two);
        foreach ($arr_texttwo as $item) {
            if(strpos($tendv, $item)){
                $solan = 2;
                break;
            }
        }
        if($ttmoi == 0){
            return ['dongia' => $giacu, 'tooltip' => 1];
        }
        else{
            $ma_tt37 = (int)substr($mabaocao, -4); 
            $model = Dmbanggia::find()
                ->innerJoin('dmbanggia ttcu', 'dmbanggia.stt = ttcu.stt AND dmbanggia.ghichu = ttcu.ghichu')
                ->andWhere(['ttcu.status' => 1, 'dmbanggia.status' => 1, 'dmbanggia.id_thongtu' => $ttmoi, 'ttcu.id_thongtu' => $ttcu, 'dmbanggia.stt' => $ma_tt37])
                ->andWhere("(CASE ttcu.type WHEN 2 THEN ttcu.dongia = ".$giacu*$solan/$tyle." ELSE ttcu.dongia = ".$giacu*$solan." END)")
                ->one();
            if($model){
                if($model->type == 2)
                    return ['dongia' => ($model->dongia*$tyle)/$solan, 'tooltip' => 1];
                else{
                    return ['dongia' => ($model->dongia)/$solan, 'tooltip' => 1];
                }
            }
            else{
                $model_tt_new = Dmbanggia::find()->where(['id_thongtu' => $ttmoi, 'stt' => $ma_tt37, 'status' => 1])->orderBy(['dongia' => SORT_ASC])->one();
                if(!$model_tt_new){
                    return ['dongia' => $giacu, 'tooltip' => 2];
                }
                else{
                    $model_tt_old = Dmbanggia::find()->where(['id_thongtu' => $ttcu, 'stt' => $ma_tt37, 'status' => 1])->exists();
                    if(!$model_tt_old){
                        if($model_tt_new->type == 2){
                            return ['dongia' => ($model_tt_new->dongia*$tyle)/$solan, 'tooltip' => 3];
                        }
                        else{
                            return ['dongia' => ($model_tt_new->dongia)/$solan, 'tooltip' => 3];
                        }
                    }
                    else{
                        $model_tt_old_value = Dmbanggia::find()->where(['id_thongtu' => $ttcu, 'stt' => $ma_tt37, 'status' => 1])->andWhere("(CASE type WHEN 2 THEN dongia = ".$giacu*$solan/$tyle." ELSE dongia = ".$giacu*$solan." END)")->exists();
                        if($model_tt_old_value){
                            if($model_tt_new->type == 2){
                                return ['dongia' => ($model_tt_new->dongia*$tyle)/$solan, 'tooltip' => 4];
                            }
                            else{
                                return ['dongia' => ($model_tt_new->dongia)/$solan, 'tooltip' => 4];
                            }
                        }
                        else{
                            return ['dongia' => $giacu, 'tooltip' => 5];
                        }
                    }
                }
            }
        }
    }

    public function getListMenu($id_user=0){
        return Menu::find()->select('tbl_menu.*')->where(['<>','tbl_menu.lvl',0])
                ->innerJoin('tbl_menu m', 'tbl_menu.lft <= m.lft AND tbl_menu.rgt >= m.rgt AND m.rgt-m.lft = 1 AND m.visible = 1')
                ->innerJoin('auth_item_child au', 'au.child = m.link AND au.flag = 1')
                ->innerJoin('auth_assignment auth', 'au.parent = auth.item_name AND auth.user_id = "'.$id_user.'"')
                ->groupBy(['tbl_menu.id'])
                ->orderBy(['tbl_menu.lft' => SORT_ASC])
                ->all();
    }

    public function getListMenuActive($link){
        return Menu::find()->where(['<>','tbl_menu.lvl',0])
                ->innerJoin('tbl_menu m', 'tbl_menu.lft <= m.lft AND tbl_menu.rgt >= m.rgt AND m.rgt-m.lft = 1 AND m.visible = 1')
                ->leftJoin('auth_item_child au', 'm.link = au.parent AND au.flag = 0')
                ->where('m.link = "'.$link.'" OR au.child = "'.$link.'"')
                ->groupBy(['tbl_menu.id'])
                ->orderBy(['tbl_menu.lft' => SORT_ASC])
                ->select(['tbl_menu.id'])->column();
    }

    public function cutShortText($text, $dodai = 150) {
        if(strlen($text) > $dodai){
            $tmp = $text;
            $text = substr($text,0,$dodai);  
            $text = substr($text,0,strrpos($text,' ')); 
            return $text.'...';  
        }
        else{
            return $text;
        }
    }

    public function showToolTipConvert($bhyt, $vp){
        $showtext = '';
        if($bhyt != 1){
            $showtext .= 'BHYT: '.self::convertToolTip($bhyt);
        }
        if($vp != 1){
            if($showtext != ''){
                $showtext .= ' | ';
            }
            $showtext .= 'Viện phí: '.self::convertToolTip($vp);
        }
        return $showtext;
    }

    public function convertToolTip($ma){
        switch ($ma) {
            case 2:
                return 'Giữ nguyên giá do mã TT37 không quy định tại thông tư mới';
                break;

            case 3:
                return 'Lấy theo đơn giá thấp nhất quy định tại thông tư mới do thông tư cũ không quy định';
                break;
                
            case 4:
                return 'Lấy theo đơn giá thấp nhất quy định tại thông tư mới do không thể phân biệt trùng mã (theo cột ghi chú ở bảng giá)';
                break;

            case 5:
                return 'Giữ nguyên giá cũ do CSYT không áp dụng quy định giá theo thông tư cũ';
                break;

            case 0:
                return 'Người dùng tự điều chỉnh giá';
                break;
            
            default:
                return '';
                break;
        }
    }

    public function setStatusListDvkt($bhyt, $vp){
        if($bhyt == 1 && $vp == 1){
            return 1;
        }
        else{
            if($bhyt == 0 || $vp == 0){
                return 3;
            }
            else{
                return 2;
            }
        }
    }

    public function checkIsL2ByIDPhieu($id_phieu){
        $hiswork = HisWork::findOne($id_phieu);
        if($hiswork && $hiswork->phienban == 1){
            return true;
        }
        else{
            return false;
        }
    }

    public function checkIsL2ByIDPhieuDuoc($id_phieu){
        $phieuduoc = DuocTicket::findOne($id_phieu);
        if($phieuduoc && $phieuduoc->phienban == 1){
            return true;
        }
        else{
            return false;
        }
    }

    public function getInfoThamSo($thamso, $default = 0, $status = 1){
        if($info_dmthamso = Dmthamso::findOne(['id' => $thamso, 'status' => $status]))
            return $info_dmthamso->giatri;
        else
            return $default;
    }

    public function layGiaTriThamSoDonVi($donvi, $thamso){
        if($info_dmthamso = ThamSo_DonVi::findOne(['id_donvi' => $donvi, 'id_thamso' => $thamso]))
            return $info_dmthamso->giatri;
        else
            return '';
    }

    public function layTrangThaiThamSoDonVi($donvi, $thamso){
        if($info_dmthamso = ThamSo_DonVi::findOne(['id_donvi' => $donvi, 'id_thamso' => $thamso]))
            return $info_dmthamso->status;
        else
            return 0;
    }

    public function layDanhSachDanhMucDuocExport($id_phieu){
        $exProvider = new SqlDataProvider([
            'sql' => 'SELECT dm.*, 
                    CASE
                        WHEN tsdv.id_donvi IS NOT NULL THEN tsdv.id_donvi
                        WHEN ts.id_donvi IS NOT NULL THEN ts.id_donvi
                        ELSE dt.id_donvi
                    END AS madonvi,
                    nhsx.id AS manhasanxuat,
                    nsx.id AS manuocsanxuat
                FROM dmduoc dm 
                LEFT JOIN duoc_ticket dt ON dt.id = dm.id_phieu
                LEFT JOIN nhasanxuat nhsx ON lower(nhsx.ten_nsx) = BINARY lower(dm.nhasanxuat) AND dt.phienban = 0
                LEFT JOIN nuocsanxuat nsx ON lower(nsx.ten_nsx) = BINARY lower(dm.nuocsanxuat) AND dt.phienban = 0
                LEFT JOIN thamso_donvi ts ON dt.id_donvi = ts.id_donvi AND ts.id_thamso =:thamso AND ts.giatri <> "" AND ts.giatri IS NOT NULL
                LEFT JOIN thamso_donvi tsdv ON ((tsdv.giatri = ts.giatri AND tsdv.giatri <> "") OR (tsdv.id_donvi = ts.id_donvi AND tsdv.giatri = "")) AND ts.id_thamso = :thamso AND dt.phienban = 0
            WHERE dm.id_phieu =:id_phieu 
            GROUP BY dm.id, madonvi
            ORDER BY madonvi ASC, nhsx.id ASC',
            'params' => [':thamso' => 'HIS_LT_DM_DUOC', ':id_phieu' => $id_phieu],
        ]);
        return $exProvider;
    }

    public function laySLNhaSanXuatThieuMa($id_phieu){
        $info = DmDuoc::find()->where(['id_phieu' => $id_phieu])
                    ->leftJoin('nhasanxuat nsx', 'lower(nsx.ten_nsx) = BINARY lower(dmduoc.nhasanxuat)')
                    ->where('dmduoc.id_phieu = '.$id_phieu.' AND nsx.id IS NULL AND dmduoc.nhasanxuat <> ""')
                    ->groupBy('lower(dmduoc.nhasanxuat)')->count();
        return $info;
    }

    public function laySLNuocSanXuatThieuMa($id_phieu){
        $info = DmDuoc::find()->where(['id_phieu' => $id_phieu])
                    ->leftJoin('nuocsanxuat nsx', 'lower(nsx.ten_nsx) = BINARY lower(dmduoc.nuocsanxuat)')
                    ->where('dmduoc.id_phieu = '.$id_phieu.' AND nsx.id IS NULL AND dmduoc.nuocsanxuat <> ""')
                    ->groupBy('lower(dmduoc.nuocsanxuat)')->count();
        return $info;
    }

    public function layDSNhaSanXuatThieuMa($id_phieu){
        $exProvider = new SqlDataProvider([
            'sql' => 'SELECT nsx.id as mansx, dm.nhasanxuat as tennsx, dm.id_phieu as idphieu from dmduoc dm left join nhasanxuat nsx ON LOWER(nsx.ten_nsx) = BINARY LOWER(dm.nhasanxuat)
WHERE dm.id_phieu =:id_phieu AND nsx.id IS NULL AND dm.nhasanxuat <> "" GROUP BY LOWER(dm.nhasanxuat) ORDER BY dm.nhasanxuat ASC',
            'params' => [':id_phieu' => $id_phieu],
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);
        return $exProvider;
    }

    public function layDSNuocSanXuatThieuMa($id_phieu){
        $exProvider = new SqlDataProvider([
            'sql' => 'SELECT nsx.id as mansx, dm.nuocsanxuat as tennsx, dm.id_phieu as idphieu from dmduoc dm left join nuocsanxuat nsx ON LOWER(nsx.ten_nsx) = BINARY LOWER(dm.nuocsanxuat)
WHERE dm.id_phieu =:id_phieu AND nsx.id IS NULL AND dm.nuocsanxuat <> "" GROUP BY LOWER(dm.nuocsanxuat) ORDER BY dm.nuocsanxuat ASC',
            'params' => [':id_phieu' => $id_phieu],
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);
        return $exProvider;
    }
}