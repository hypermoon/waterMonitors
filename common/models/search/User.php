<?php

namespace res\waterMonitors\common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use res\waterMonitors\common\models\User as UserModel;

/**
 * User represents the model behind the search form about `res\waterMonitor\common\models\User`.
 */
class User extends UserModel
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'created_at', 'updated_at', 'role', 'sorting', 'status', 'grow', 'studentpay'], 'integer'],
            [['account', 'password', 'auth_key', 'password_hash', 'password_reset_token', 'username', 'jpin', 'qpin', 'nickname', 'sex', 'idcard', 'stdid', 'nation', 'birthday', 'mobile', 'qqnum', 'wechat', 'homephone', 'email', 'image', 'address', 'orgin', 'father', 'fathphone', 'guardian', 'gdphone', 'midschool', 'mather', 'mathphone', 'university', 'specialty', 'level', 'workunit', 'quarters', 'position', 'nexus', 'workphone', 'remark', 'schfrom', 'midtid', 'trasaction_id', 'alternativephone', 'signature'], 'safe'],
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
        $query = UserModel::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'role' => $this->role,
            'birthday' => $this->birthday,
            'sorting' => $this->sorting,
            'status' => $this->status,
            'grow' => $this->grow,
            'studentpay' => $this->studentpay,
        ]);

        $query->andFilterWhere(['like', 'account', $this->account])
            ->andFilterWhere(['like', 'password', $this->password])
            ->andFilterWhere(['like', 'auth_key', $this->auth_key])
            ->andFilterWhere(['like', 'password_hash', $this->password_hash])
            ->andFilterWhere(['like', 'password_reset_token', $this->password_reset_token])
            ->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'jpin', $this->jpin])
            ->andFilterWhere(['like', 'qpin', $this->qpin])
            ->andFilterWhere(['like', 'nickname', $this->nickname])
            ->andFilterWhere(['like', 'sex', $this->sex])
            ->andFilterWhere(['like', 'idcard', $this->idcard])
            ->andFilterWhere(['like', 'stdid', $this->stdid])
            ->andFilterWhere(['like', 'nation', $this->nation])
            ->andFilterWhere(['like', 'mobile', $this->mobile])
            ->andFilterWhere(['like', 'qqnum', $this->qqnum])
            ->andFilterWhere(['like', 'wechat', $this->wechat])
            ->andFilterWhere(['like', 'homephone', $this->homephone])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'image', $this->image])
            ->andFilterWhere(['like', 'address', $this->address])
            ->andFilterWhere(['like', 'orgin', $this->orgin])
            ->andFilterWhere(['like', 'father', $this->father])
            ->andFilterWhere(['like', 'fathphone', $this->fathphone])
            ->andFilterWhere(['like', 'guardian', $this->guardian])
            ->andFilterWhere(['like', 'gdphone', $this->gdphone])
            ->andFilterWhere(['like', 'midschool', $this->midschool])
            ->andFilterWhere(['like', 'mather', $this->mather])
            ->andFilterWhere(['like', 'mathphone', $this->mathphone])
            ->andFilterWhere(['like', 'university', $this->university])
            ->andFilterWhere(['like', 'specialty', $this->specialty])
            ->andFilterWhere(['like', 'level', $this->level])
            ->andFilterWhere(['like', 'workunit', $this->workunit])
            ->andFilterWhere(['like', 'quarters', $this->quarters])
            ->andFilterWhere(['like', 'position', $this->position])
            ->andFilterWhere(['like', 'nexus', $this->nexus])
            ->andFilterWhere(['like', 'workphone', $this->workphone])
            ->andFilterWhere(['like', 'remark', $this->remark])
            ->andFilterWhere(['like', 'schfrom', $this->schfrom])
            ->andFilterWhere(['like', 'midtid', $this->midtid])
            ->andFilterWhere(['like', 'trasaction_id', $this->trasaction_id])
            ->andFilterWhere(['like', 'alternativephone', $this->alternativephone])
            ->andFilterWhere(['like', 'signature', $this->signature]);

        return $dataProvider;
    }
}
