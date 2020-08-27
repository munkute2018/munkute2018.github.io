<?php
namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use DateTime;

/**
 * Login form
 */
class DuocTicketSearch extends DuocTicket
{
    public $id;
    public $id_user;
    public $id_donvi;
    public $saochep;
    public $ghichu;
    public $status;
    public $created_at;
    public $updated_at;
    public $status_filter;
    public $phienban_filter;
    public $saochep_filter;
    public $created_at_range;
    public $myPageSize;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'id_user', 'status', 'phienban', 'created_at', 'updated_at'], 'integer'],
            [['id_donvi', 'saochep', 'ghichu', 'myPageSize', 'created_at_range', 'status_filter', 'phienban_filter', 'saochep_filter'], 'safe'],
        ];
    }


    public function search($params) {
        $query = DuocTicket::find()->where(['id_user' => Yii::$app->user->getId()])->innerJoinWith('user', true);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['created_at_range'=>SORT_DESC],
                'attributes' => ['id', 'ghichu', 'created_at_range', 'updated_at', 'id_donvi', 'saochep']
            ],
            'pagination' => [ 'defaultPageSize' => 10 ],
        ]);

        $dataProvider->pagination->pageSize = ($this->myPageSize !== NULL) ? $this->myPageSize : 10;

        $dataProvider->sort->attributes['status_filter'] = [
            'asc' => ['duoc_ticket.status' => SORT_ASC],
            'desc' => ['duoc_ticket.status' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['phienban_filter'] = [
            'asc' => ['phienban' => SORT_DESC],
            'desc' => ['phienban' => SORT_ASC],
        ];

        $dataProvider->sort->attributes['created_at_range'] = [
            'asc' => ['duoc_ticket.created_at' => SORT_ASC],
            'desc' => ['duoc_ticket.created_at' => SORT_DESC],
        ];

        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }

        if ($this->status_filter != null && count ($this->status_filter)>0) {
            $query->andFilterWhere(['in', 'duoc_ticket.status', $this->status_filter]);
        }

        if ($this->phienban_filter != null && count ($this->phienban_filter)>0) {
            $query->andFilterWhere(['in', 'phienban', $this->phienban_filter]);
        }

        if ($this->saochep != null && count ($this->saochep)>0) {
            $query->andFilterWhere(['in', 'saochep', $this->saochep]);
        }

        if(!empty($this->created_at_range) && strpos($this->created_at_range, '-') !== false) {
            list($start_date, $end_date) = explode(' - ', $this->created_at_range);
            $start_date = DateTime::createFromFormat('d/m/Y', $start_date);
            $start_date = $start_date->format('m/d/Y');
            $end_date = DateTime::createFromFormat('d/m/Y', $end_date);
            $end_date = $end_date->format('m/d/Y');
            $query->andFilterWhere(['between', 'duoc_ticket.created_at', strtotime($start_date), strtotime($end_date)]);
        }

        $query ->andFilterWhere(['like', 'updated_at', $this->updated_at])
                ->andFilterWhere(['like', 'ghichu', $this->ghichu])
                ->andFilterWhere(['like', 'phienban', $this->phienban])
                ->andFilterWhere(['like', 'duoc_ticket.id', $this->id]);
        return $dataProvider;
    }
}
