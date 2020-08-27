<?php
namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use DateTime;

/**
 * Login form
 */
class BangGiaSearch extends Dmbanggia
{
    public $id;
    public $id_thongtu;
    public $stt;
    public $type;
    public $dongia;
    public $name;
    public $ghichu;
    public $status;
    public $created_at;
    public $updated_at;
    public $type_filter;
    public $status_filter;
    public $myPageSize;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'id_thongtu', 'stt', 'type', 'dongia', 'status', 'created_at', 'updated_at'], 'integer'],
            [['name', 'ghichu', 'type_filter', 'status_filter', 'myPageSize'], 'safe'],
        ];
    }


    public function search($params) {
        $query = Dmbanggia::find()->where(['id_thongtu' => $this->id_thongtu]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['stt' => SORT_ASC, 'ghichu' => SORT_ASC],
                'attributes' => ['stt', 'ghichu', 'name', 'dongia', 'created_at', 'posted_at']
            ],
            'pagination' => [ 'defaultPageSize' => 10 ],
        ]);

        $dataProvider->pagination->pageSize = ($this->myPageSize !== NULL) ? $this->myPageSize : 10;

        $dataProvider->sort->attributes['type_filter'] = [
            'asc' => ['dmbanggia.type' => SORT_ASC],
            'desc' => ['dmbanggia.type' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['status_filter'] = [
            'asc' => ['dmbanggia.status' => SORT_ASC],
            'desc' => ['dmbanggia.status' => SORT_DESC],
        ];
        
        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
        ]);

        if ($this->type_filter != null && count ($this->type_filter)>0) {
            $query->andFilterWhere(['in', 'type', $this->type_filter]);
        }

        if ($this->status_filter != null && count ($this->status_filter)>0) {
            $query->andFilterWhere(['in', 'dmbanggia.status', $this->status_filter]);
        }

        $query->andFilterWhere(['like', 'name', $this->name])
                ->andFilterWhere(['like', 'stt', $this->stt])
                ->andFilterWhere(['like', 'dongia', $this->dongia])
                ->andFilterWhere(['like', 'ghichu', $this->ghichu])
                ->andFilterWhere(['like', 'created_at', $this->created_at])
                ->andFilterWhere(['like', 'updated_at', $this->updated_at]);
        return $dataProvider;
    }
}
