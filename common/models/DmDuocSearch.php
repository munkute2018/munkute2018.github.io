<?php
namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use DateTime;

/**
 * Login form
 */
class DmDuocSearch extends DmDuoc
{
    public $id;
    public $id_phieu;
    public $mavt;
    public $manhom;
    public $ma_ax;
    public $hoatchat_ax;
    public $ma_duongdung_ax;
    public $hamluong_ax;
    public $ten_ax;
    public $sodangky_ax;
    public $quycach;
    public $donvitinh;
    public $dongia;
    public $nhasanxuat;
    public $nuocsanxuat;
    public $nhathau;
    public $quyetdinh;
    public $congbo;
    public $loaithuoc;
    public $loaithuoc_filter;
    public $ma_duongdung_ax_filter;
    public $goithau;
    public $nhomthau;
    public $tyle;
    public $created_at;
    public $updated_at;
    public $myPageSize;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'dongia', 'loaithuoc', 'tyle', 'created_at', 'updated_at'], 'integer'],
            [['mavt', 'manhom', 'quyetdinh', 'donvitinh', 'goithau', 'nhomthau', 'congbo', 'ma_duongdung_ax', 'ma_ax', 'sodangky_ax', 'hoatchat_ax', 'hamluong_ax', 'ten_ax', 'quycach', 'nhasanxuat', 'nuocsanxuat', 'nhathau', 'myPageSize', 'loaithuoc_filter', 'ma_duongdung_ax_filter'], 'safe'],
        ];
    }

    public function search($params) {
        $query = DmDuoc::find()->where(['id_phieu' => $this->id_phieu])
                    ->leftJoin('duongdung dd', 'dd.id = dmduoc.ma_duongdung_ax');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['mavt' => SORT_ASC, 'ten_ax' => SORT_ASC],
                'attributes' => ['mavt', 'manhom', 'ten_ax', 'id', 'id_phieu', 'dongia', 'loaithuoc', 'created_at', 'updated_at', 'quyetdinh', 'donvitinh', 'goithau', 'nhomthau', 'congbo', 'ma_duongdung_ax', 'ma_ax', 'sodangky_ax', 'hoatchat_ax', 'hamluong_ax', 'quycach', 'nhasanxuat', 'nuocsanxuat', 'nhathau', 'tyle']
            ],
            'pagination' => [ 'defaultPageSize' => 10 ],
        ]);

        $dataProvider->pagination->pageSize = ($this->myPageSize !== NULL) ? $this->myPageSize : 10;

        $dataProvider->sort->attributes['loaithuoc_filter'] = [
            'asc' => ['loaithuoc' => SORT_ASC],
            'desc' => ['loaithuoc' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['ma_duongdung_ax_filter'] = [
            'asc' => ['dd.mota' => SORT_ASC],
            'desc' => ['dd.mota' => SORT_DESC],
        ];

        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
        ]);

        if ($this->loaithuoc_filter != null && count ($this->loaithuoc_filter)>0) {
            $query->andFilterWhere(['in', 'loaithuoc', $this->loaithuoc_filter]);
        }

        if ($this->ma_duongdung_ax_filter != null && count ($this->ma_duongdung_ax_filter)>0) {
            $query->andFilterWhere(['in', 'ma_duongdung_ax', $this->ma_duongdung_ax_filter]);
        }

        $query->andFilterWhere(['like', 'mavt', $this->mavt])
            ->andFilterWhere(['like', 'manhom', $this->manhom])
            ->andFilterWhere(['like', 'ma_ax', $this->ma_ax])
            ->andFilterWhere(['like', 'ten_ax', $this->ten_ax])
            ->andFilterWhere(['like', 'hoatchat_ax', $this->hoatchat_ax])
            ->andFilterWhere(['like', 'hamluong_ax', $this->hamluong_ax])
            ->andFilterWhere(['like', 'donvitinh', $this->donvitinh])
            ->andFilterWhere(['like', 'dongia', $this->dongia])
            ->andFilterWhere(['like', 'quycach', $this->quycach])
            ->andFilterWhere(['like', 'sodangky_ax', $this->sodangky_ax])
            ->andFilterWhere(['like', 'nhasanxuat', $this->nhasanxuat])
            ->andFilterWhere(['like', 'nuocsanxuat', $this->nuocsanxuat])
            ->andFilterWhere(['like', 'nhathau', $this->nhathau])
            ->andFilterWhere(['like', 'quyetdinh', $this->quyetdinh])
            ->andFilterWhere(['like', 'congbo', $this->congbo])
            ->andFilterWhere(['like', 'goithau', $this->goithau])
            ->andFilterWhere(['like', 'nhomthau', $this->nhomthau])
            ->andFilterWhere(['like', 'tyle', $this->tyle]);
        return $dataProvider;
    }
}
