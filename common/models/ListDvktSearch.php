<?php
namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use DateTime;

/**
 * Login form
 */
class ListDvktSearch extends ListDvkt
{
    public $id;
    public $id_phieu;
    public $id_donvi;
    public $id_dichvu;
    public $ten_dichvu;
    public $ma_dichvu;
    public $gia_bhyt_old;
    public $gia_vp_old;
    public $gia_bhyt_new;
    public $gia_vp_new;
    public $status;
    public $created_at;
    public $updated_at;
    public $status_filter;
    public $myPageSize;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'id_phieu', 'id_dichvu', 'gia_bhyt_old', 'gia_vp_old', 'gia_bhyt_new', 'gia_vp_new', 'status', 'created_at', 'updated_at'], 'integer'],
            [['ten_dichvu', 'id_donvi', 'ma_dichvu', 'status_filter', 'myPageSize'], 'safe'],
        ];
    }


    public function search($params) {
        $query = ListDvkt::find()->where(['id_phieu' => $this->id_phieu]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['id_donvi' => SORT_ASC, 'id_dichvu' => SORT_ASC],
                'attributes' => ['id_donvi', 'id_dichvu', 'ma_dichvu', 'gia_bhyt_old', 'gia_vp_old', 'gia_bhyt_new', 'gia_vp_new', 'ten_dichvu', 'created_at']
            ],
            'pagination' => [ 'defaultPageSize' => 10 ],
        ]);

        $dataProvider->pagination->pageSize = ($this->myPageSize !== NULL) ? $this->myPageSize : 10;

        $dataProvider->sort->attributes['status_filter'] = [
            'asc' => ['listdvkt.status' => SORT_ASC],
            'desc' => ['listdvkt.status' => SORT_DESC],
        ];

        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
        ]);

        if ($this->status_filter != null && count ($this->status_filter)>0) {
            $query->andFilterWhere(['in', 'listdvkt.status', $this->status_filter]);
        }

        $query->andFilterWhere(['like', 'id_donvi', $this->id_donvi])
                ->andFilterWhere(['like', 'ma_dichvu', $this->ma_dichvu])
                ->andFilterWhere(['like', 'id_dichvu', $this->id_dichvu])
                ->andFilterWhere(['like', 'ten_dichvu', $this->ten_dichvu])
                ->andFilterWhere(['like', 'gia_bhyt_old', $this->gia_bhyt_old])
                ->andFilterWhere(['like', 'gia_bhyt_new', $this->gia_bhyt_new])
                ->andFilterWhere(['like', 'gia_vp_old', $this->gia_vp_old])
                ->andFilterWhere(['like', 'gia_vp_new', $this->gia_vp_new]);
        return $dataProvider;
    }
}
