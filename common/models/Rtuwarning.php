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
            'rtuno' => 'Rtuno',
            'date' => 'Date',
            'waterlv' => 'Waterlv',
            'rainfall' => 'Rainfall',
            'volte' => 'Volte',
            'originstr' => 'Originstr',
            'bakup' => 'Bakup',
        ];
    }
}
