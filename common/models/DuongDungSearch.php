<?php
namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use DateTime;

/**
 * Login form
 */
class DuongDungSearch extends DuongDung
{
    public $id;
    public $mota;
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
            [['status', 'created_at', 'updated_at'], 'integer'],
            [['id', 'mota', 'status_filter', 'myPageSize'], 'safe'],
        ];
    }


    public function search($params) {
        $query = DuongDung::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['id' => SORT_ASC],
                'attributes' => ['id', 'mota', 'created_at', 'posted_at', 'status']
            ],
            'pagination' => [ 'defaultPageSize' => 10 ],
        ]);

        $dataProvider->pagination->pageSize = ($this->myPageSize !== NULL) ? $this->myPageSize : 10;

        $dataProvider->sort->attributes['status_filter'] = [
            'asc' => ['status' => SORT_ASC],
            'desc' => ['status' => SORT_DESC],
        ];

        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'like', 'id', $this->id
        ]);

        if ($this->status_filter != null && count ($this->status_filter)>0) {
            $query->andFilterWhere(['in', 'status', $this->status_filter]);
        }

        $query->andFilterWhere(['like', 'mota', $this->mota])
                ->andFilterWhere(['like', 'created_at', $this->created_at])
                ->andFilterWhere(['like', 'updated_at', $this->updated_at]);
        return $dataProvider;
    }
}
