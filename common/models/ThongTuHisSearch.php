<?php
namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use DateTime;

/**
 * Login form
 */
class ThongTuHisSearch extends Dmthongtuhis
{
    public $id;
    public $name;
    public $bhxh;
    public $type;
    public $status;
    public $posted_at;
    public $created_at;
    public $updated_at;
    public $bhxh_filter;
    public $type_filter;
    public $status_filter;
    public $posted_at_range;
    public $myPageSize;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'bhxh', 'status', 'posted_at', 'created_at', 'updated_at'], 'integer'],
            [['name', 'bhxh_filter', 'status_filter', 'posted_at_range', 'myPageSize'], 'safe'],
        ];
    }


    public function search($params) {
        $query = Dmthongtuhis::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['posted_at'=>SORT_DESC],
                'attributes' => ['name', 'created_at', 'posted_at', 'id']
            ],
            'pagination' => [ 'defaultPageSize' => 10 ],
        ]);

        $dataProvider->pagination->pageSize = ($this->myPageSize !== NULL) ? $this->myPageSize : 10;

        $dataProvider->sort->attributes['bhxh_filter'] = [
            'asc' => ['bhxh' => SORT_ASC],
            'desc' => ['bhxh' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['status_filter'] = [
            'asc' => ['dmthongtuhis.status' => SORT_ASC],
            'desc' => ['dmthongtuhis.status' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['posted_at_range'] = [
            'asc' => ['dmthongtuhis.posted_at' => SORT_ASC],
            'desc' => ['dmthongtuhis.posted_at' => SORT_DESC],
        ];
        
        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'like', 'id', $this->id
        ]);

        if ($this->bhxh_filter != null && count ($this->bhxh_filter)>0) {
            $query->andFilterWhere(['in', 'bhxh', $this->bhxh_filter]);
        }

        if ($this->status_filter != null && count ($this->status_filter)>0) {
            $query->andFilterWhere(['in', 'dmthongtuhis.status', $this->status_filter]);
        }

        if(!empty($this->posted_at_range) && strpos($this->posted_at_range, '-') !== false) {
            list($start_date, $end_date) = explode(' - ', $this->posted_at_range);
            $start_date = DateTime::createFromFormat('d/m/Y', $start_date);
            $start_date = $start_date->format('m/d/Y');
            $end_date = DateTime::createFromFormat('d/m/Y', $end_date);
            $end_date = $end_date->format('m/d/Y');
            $query->andFilterWhere(['between', 'dmthongtuhis.posted_at', strtotime($start_date), strtotime($end_date)]);
        }

        $query->andFilterWhere(['like', 'name', $this->name])
                ->andFilterWhere(['like', 'created_at', $this->created_at])
                ->andFilterWhere(['like', 'updated_at', $this->updated_at]);
        return $dataProvider;
    }
}
