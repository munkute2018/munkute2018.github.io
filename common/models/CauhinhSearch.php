<?php
namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use DateTime;

/**
 * Login form
 */
class CauhinhSearch extends DonVi
{
    public $id;
    public $id_thamso;
    public $madonvi;
    public $giatri;
    public $tendonvi;
    public $id_parent;
    public $tuyen_filter;
    public $myPageSize;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'madonvi', 'id_parent'], 'integer'],
            [['id_thamso', 'giatri', 'tendonvi', 'tuyen_filter', 'myPageSize'], 'safe'],
        ];
    }

    public function search($params) {
        $this->load($params);
        $query = DonVi::find()
                ->leftjoin('thamso_donvi ts', 'dmdonvi.madonvi = ts.id_donvi AND ts.id_thamso = "'.$this->id_thamso.'"')
                ->where('EXISTS (SELECT id FROM dmthamso dmts WHERE dmts.id = "'.$this->id_thamso.'" AND dmts.flag = 1)');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['id_parent' => SORT_ASC, 'madonvi' => SORT_ASC],
                'attributes' => ['id','madonvi','tendonvi','id_parent', 'tuyen_filter']
            ],
            'pagination' => [ 'defaultPageSize' => 10 ],
        ]);

        $dataProvider->pagination->pageSize = ($this->myPageSize !== NULL) ? $this->myPageSize : 10;

        $dataProvider->sort->attributes['tuyen_filter'] = [
            'asc' => ['tuyen' => SORT_ASC],
            'desc' => ['tuyen' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['giatri'] = [
            'asc' => ['ts.giatri' => SORT_ASC],
            'desc' => ['ts.giatri' => SORT_DESC],
        ];

        if (!$this->validate()) {
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'like', 'id', $this->id
        ]);

        if ($this->tuyen_filter != null && count ($this->tuyen_filter)>0) {
            $query->andFilterWhere(['in', 'tuyen', $this->tuyen_filter]);
        }

        $query->andFilterWhere(['like', 'madonvi', $this->madonvi]);
        $query->andFilterWhere(['like', 'id_parent', $this->id_parent]);
        $query->andFilterWhere(['like', 'ts.giatri', $this->giatri]);
        $query->andFilterWhere(['like', 'tendonvi', $this->tendonvi]);
        return $dataProvider;
    }
}
