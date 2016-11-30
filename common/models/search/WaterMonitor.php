<?php

namespace res\waterMonitors\common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use res\waterMonitors\common\models\WaterMonitor as WaterMonitorModel;

/**
 * WaterMonitor represents the model behind the search form about `res\waterMonitor\common\models\WaterMonitor`.
 */
class WaterMonitor extends WaterMonitorModel
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['site', 'individual_monitoring', 'phone', 'current_site', 'current_level', 'current_temp', 'rainfall', 'img1', 'img2'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = WaterMonitorModel::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
        ]);

        $query->andFilterWhere(['like', 'site', $this->site])
            ->andFilterWhere(['like', 'individual_monitoring', $this->individual_monitoring])
            ->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'current_site', $this->current_site])
            ->andFilterWhere(['like', 'current_level', $this->current_level])
            ->andFilterWhere(['like', 'current_temp', $this->current_temp])
            ->andFilterWhere(['like', 'rainfall', $this->rainfall])
            ->andFilterWhere(['like', 'img1', $this->img1])
            ->andFilterWhere(['like', 'img2', $this->img2]);

        return $dataProvider;
    }
}
