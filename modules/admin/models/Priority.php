<?php

namespace app\modules\admin\models;

use Yii;

class Priority extends \yii\db\ActiveRecord
{

    public static function tableName()
    {
        return 'priority';
    }

    public function rules()
    {
        return [
            [['name'], 'safe'],
        ];
    }


    public function attributeLabels()
    {
        return [
            'name' => 'namr',
        ];
    }

}
