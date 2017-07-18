<?php

namespace app\modules\admin\models;

use Yii;

class Podr extends \yii\db\ActiveRecord
{

    public static function tableName()
    {
        return 'podr';
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
