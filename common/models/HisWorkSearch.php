<?php
namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use DateTime;

/**
 * Login form
 */
class HisWorkSearch extends HisWork
{
    public $id;
    public $id_user;
    public $bhyt_new;
    public $bhyt_old;
    public $vp_new;
    public $vp_old;
    public $status;
    public $created_at;
    public $updated_at;
    public $status_filter;
    public $phienban_filter;
    public $created_at_range;
    public $bhyt_old_filter;
    public $bhyt_new_filter;
    public $vp_old_filter;
    public $vp_new_filter;
    public $myPageSize;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'id_user', 'bhyt_new', 'bhyt_old', 'vp_new', 'vp_old', 'status', 'phienban', 'created_at', 'updated_at'], 'integer'],
            [['myPageSize', 'created_at_range', 'status_filter', 'phienban_filter', 'bhyt_old_filter', 'bhyt_new_filter', 'vp_old_filter', 'vp_new_filter'], 'safe'],
        ];
    }
    
    public function search($params) {
        $query = HisWork::find()->where(['id_user' => Yii::$app->user->getId()])->innerJoinWith('user', true)
                ->leftJoin('dmthongtuhis tt_bhyt_old', 'tt_bhyt_old.id = hiswork.bhyt_old')
                ->leftJoin('dmthongtuhis tt_bhyt_new', 'tt_bhyt_new.id = hiswork.bhyt_new')
                ->leftJoin('dmthongtuhis tt_vp_old', 'tt_vp_old.id = hiswork.vp_old')
                ->leftJoin('dmthongtuhis tt_vp_new', 'tt_vp_new.id = hiswork.vp_new');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['created_at_range'=>SORT_DESC],
                'attributes' => ['id', 'created_at_range', 'updated_at']
            ],
            'pagination' => [ 'defaultPageSize' => 10 ],
        ]);

        $dataProvider->pagination->pageSize = ($this->myPageSize !== NULL) ? $this->myPageSize : 10;

        $dataProvider->sort->attributes['bhyt_old_filter'] = [
            'asc' => ['tt_bhyt_old.name' => SORT_ASC],
            'desc' => ['tt_bhyt_old.name' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['bhyt_new_filter'] = [
            'asc' => ['tt_bhyt_new.name' => SORT_ASC],
            'desc' => ['tt_bhyt_new.name' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['vp_old_filter'] = [
            'asc' => ['tt_vp_old.name' => SORT_ASC],
            'desc' => ['tt_vp_old.name' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['vp_new_filter'] = [
            'asc' => ['tt_vp_new.name' => SORT_ASC],
            'desc' => ['tt_vp_new.name' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['status_filter'] = [
            'asc' => ['hiswork.status' => SORT_ASC],
            'desc' => ['hiswork.status' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['phienban_filter'] = [
            'asc' => ['hiswork.phienban' => SORT_DESC],
            'desc' => ['hiswork.phienban' => SORT_ASC],
        ];

        $dataProvider->sort->attributes['created_at_range'] = [
            'asc' => ['hiswork.created_at' => SORT_ASC],
            'desc' => ['hiswork.created_at' => SORT_DESC],
        ];

        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }

        if ($this->bhyt_old_filter != null && count ($this->bhyt_old_filter)>0) {
            $query->andFilterWhere(['in', 'hiswork.bhyt_old', $this->bhyt_old_filter]);
        }

        if ($this->bhyt_new_filter != null && count ($this->bhyt_new_filter)>0) {
            $query->andFilterWhere(['in', 'hiswork.bhyt_new', $this->bhyt_new_filter]);
        }

        if ($this->vp_old_filter != null && count ($this->vp_old_filter)>0) {
            $query->andFilterWhere(['in', 'hiswork.vp_old', $this->vp_old_filter]);
        }

        if ($this->vp_new_filter != null && count ($this->vp_new_filter)>0) {
            $query->andFilterWhere(['in', 'hiswork.vp_new', $this->vp_new_filter]);
        }

        if ($this->status_filter != null && count ($this->status_filter)>0) {
            $query->andFilterWhere(['in', 'hiswork.status', $this->status_filter]);
        }

        if ($this->phienban_filter != null && count ($this->phienban_filter)>0) {
            $query->andFilterWhere(['in', 'hiswork.phienban', $this->phienban_filter]);
        }

        if(!empty($this->created_at_range) && strpos($this->created_at_range, '-') !== false) {
            list($start_date, $end_date) = explode(' - ', $this->created_at_range);
            $start_date = DateTime::createFromFormat('d/m/Y', $start_date);
            $start_date = $start_date->format('m/d/Y');
            $end_date = DateTime::createFromFormat('d/m/Y', $end_date);
            $end_date = $end_date->format('m/d/Y');
            $query->andFilterWhere(['between', 'hiswork.created_at', strtotime($start_date), strtotime($end_date)]);
        }

        $query ->andFilterWhere(['like', 'updated_at', $this->updated_at])
                ->andFilterWhere(['like', 'hiswork.id', $this->id]);
        return $dataProvider;
    }
}
