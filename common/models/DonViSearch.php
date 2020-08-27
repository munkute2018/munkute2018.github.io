<?php
namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use DateTime;

/**
 * Login form
 */
class DonViSearch extends DonVi
{
    public $madonvi;
    public $tendonvi;
    public $tuyen;
    public $hang;
    public $id_parent;
    public $id_huyen;
    public $huyen;
    public $phone;
    public $phienban;
    public $status;
    public $created_at;
    public $updated_at;
    public $status_filter;
    public $tuyen_filter;
    public $hang_filter;
    public $phienban_filter;
    public $myPageSize;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['madonvi', 'id_parent', 'tuyen', 'hang', 'id_huyen', 'phienban', 'status', 'created_at', 'updated_at'], 'integer'],
            [['tendonvi', 'huyen', 'phone', 'status_filter', 'tuyen_filter', 'hang_filter', 'phienban_filter', 'myPageSize'], 'safe'],
        ];
    }


    public function search($params) {
        $query = DonVi::find()->innerJoinWith('huyen', true);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['madonvi' => SORT_ASC],
                'attributes' => ['madonvi', 'tendonvi', 'created_at', 'posted_at', 'id_parent']
            ],
            'pagination' => [ 'defaultPageSize' => 10 ],
        ]);

        $dataProvider->pagination->pageSize = ($this->myPageSize !== NULL) ? $this->myPageSize : 10;

        $dataProvider->sort->attributes['status_filter'] = [
            'asc' => ['dmdonvi.status' => SORT_ASC],
            'desc' => ['dmdonvi.status' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['phienban_filter'] = [
            'asc' => ['phienban' => SORT_ASC],
            'desc' => ['phienban' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['huyen'] = [
            'asc' => ['huyen.huyen' => SORT_ASC],
            'desc' => ['huyen.huyen' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['tuyen_filter'] = [
            'asc' => ['tuyen' => SORT_ASC],
            'desc' => ['tuyen' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['hang_filter'] = [
            'asc' => ['hang' => SORT_ASC],
            'desc' => ['hang' => SORT_DESC],
        ];

        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'like', 'madonvi', $this->madonvi
        ]);

        if ($this->status_filter != null && count ($this->status_filter)>0) {
            $query->andFilterWhere(['in', 'dmdonvi.status', $this->status_filter]);
        }

        if ($this->huyen != null && count ($this->huyen)>0) {
            $query->andFilterWhere(['in', 'dmdonvi.id_huyen', $this->huyen]);
        }

        if ($this->tuyen_filter != null && count ($this->tuyen_filter)>0) {
            $query->andFilterWhere(['in', 'tuyen', $this->tuyen_filter]);
        }

        if ($this->hang_filter != null && count ($this->hang_filter)>0) {
            $query->andFilterWhere(['in', 'hang', $this->hang_filter]);
        }

        if ($this->phienban_filter != null && count ($this->phienban_filter)>0) {
            $query->andFilterWhere(['in', 'phienban', $this->phienban_filter]);
        }

        $query->andFilterWhere(['like', 'tendonvi', $this->tendonvi])
                ->andFilterWhere(['like', 'id_parent', $this->id_parent])
                ->andFilterWhere(['like', 'created_at', $this->created_at])
                ->andFilterWhere(['like', 'updated_at', $this->updated_at]);
        return $dataProvider;
    }
}
