<?php

//namespace app\models;

namespace res\waterMonitor\common\models;
use Yii;

/**
 * This is the model class for table "water_singlertustation".
 *
 * @property integer $id
 * @property string $state
 * @property integer $statno
 * @property string $waterlv
 * @property integer $rainfall
 * @property string $watertemp
 * @property string $date
 * @property string $bakup1
 * @property string $bakup2
 */
class WaterSinglertustation extends \frontend\core\BaseActiveRecord
//class WaterSinglertustation extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'water_singlertustation';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['statno', 'rainfall'], 'integer'],
            [['waterlv', 'watertemp','volte', 'bakup1'], 'number'],
            [['date'], 'safe'],
            [['state'], 'string', 'max' => 8],
            [['bakup2'], 'string', 'max' => 256],
            [['rainfallmulti'], 'string', 'max' => 24],
            [['waterlvmulti'], 'string', 'max' => 48],
            [['waterflow'], 'string', 'max' => 48]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'state' => Yii::t('app', '站点'),
             // 'state' => '站点',
            'statno' => '站点编号',
           // 'waterlv' => '水位',
            'waterlv' =>Yii::t('app','水位'),  // '水位',
            'rainfall' => '24小时雨量',
            'watertemp' => '水温',
            'date' => '记录时间',
            'bakup1' => 'Bakup1',
            'bakup2' => 'Bakup2',
            'rainfallmulti' =>'rainfallmulti',
            'waterlvmulti' => 'waterlvmulti',
            'waterflow' => 'waterflow',
            'volte' => '电压',           
        ];
    }
}
