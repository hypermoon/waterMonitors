<?php

//namespace app\models;
namespace res\waterMonitors\common\models;
use Yii;

/**
 * This is the model class for table "waterstation".
 *
 * @property integer $sitenumber
 * @property string $stationame
 * @property integer $fatherpoint
 * @property string $desciber
 * @property string $bakup
 */
//class Waterstation extends \yii\db\ActiveRecord
class Waterstation extends \frontend\core\BaseActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'waterstation';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sitenumber'], 'required'],
            [['sitenumber', 'fatherpoint'], 'integer'],
            [['bakup'], 'string'],
            [['stationame'], 'string', 'max' => 32],
            [['desciber'], 'string', 'max' => 256],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'sitenumber' => 'RTU编号',
            'stationame' => '站点名称',
            'fatherpoint' => '手机号',
            'desciber' => '描述',
            'bakup' => '预警水位',
        ];
    }
}
