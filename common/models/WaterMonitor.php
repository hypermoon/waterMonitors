<?php

namespace res\waterMonitor\common\models;

use Yii;

/**
 * This is the model class for table "water_monitor".
 *
 * @property integer $id
 * @property string $site
 * @property string $individual_monitoring
 * @property string $phone
 * @property string $current_site
 * @property string $current_level
 * @property string $current_temp
 * @property string $rainfall
 * @property string $img1
 * @property string $img2
 */
class WaterMonitor extends \frontend\core\BaseActiveRecord
{
    /**
     * @current_temp
     */
    public static function tableName()
    {
        return 'water_monitor';
    }

    /**
     * @current_temp
     */
    public function rules()
    {
        return [
            [['site', 'individual_monitoring', 'phone', 'current_site', 'current_level', 'current_temp', 'rainfall', 'img1', 'img2'], 'string', 'max' => 30]
        ];
    }

    /**
     * @current_temp
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'site' => Yii::t('app', '站点'),
            'individual_monitoring' => Yii::t('app', '监测人'),
            'phone' => Yii::t('app', '联系电话'),
            'current_site' => Yii::t('app', '站点编号 ' ),  // '当前站点'),
            'current_level' => Yii::t('app', '当前水位'),
            'current_temp' => Yii::t('app', '当前水温'),
            'rainfall' => Yii::t('app', '24小时雨量'),
            'img1' => Yii::t('app', 'Img1'),
            'img2' => Yii::t('app', 'Img2'),
			'client_ip' => Yii::t('app', 'IP地址'),
			'datetime' => Yii::t('app', '时间'),
			'accumulator' => Yii::t('app', '蓄电池电压'),
			'sluice' => Yii::t('app', '堰水计'),
			
        ];
    }
}
