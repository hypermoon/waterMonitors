<?php

//namespace app\models;
namespace res\waterMonitors\common\models;

use Yii;

/**
 * This is the model class for table "rtuwarning".
 *
 * @property integer $id
 * @property integer $rtuno
 * @property string $date
 * @property double $waterlv
 * @property integer $rainfall
 * @property double $volte
 * @property string $originstr
 * @property double $bakup
 */
class Rtuwarning extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'rtuwarning';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['rtuno', 'rainfall'], 'integer'],
            [['date'], 'safe'],
            [['waterlv', 'volte', 'bakup'], 'number'],
            [['originstr'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
<<<<<<< HEAD
            'rtuno' => 'Rtu编号',
            'date' => '日期',
            'waterlv' => '报警水位',
            'rainfall' => '报警雨量',
            'volte' => '报警电压',
            'originstr' => 'Originstr',
            'bakup' => '手机号',
=======
            'rtuno' => 'Rtuno',
            'date' => 'Date',
            'waterlv' => 'Waterlv',
            'rainfall' => 'Rainfall',
            'volte' => 'Volte',
            'originstr' => 'Originstr',
            'bakup' => 'Bakup',
>>>>>>> 9c4aeb56d7c2ba56f7e0b2a557161843ec46d9db
        ];
    }
}
