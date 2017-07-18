<?php

namespace app\modules\admin\models;

use Yii;

class Problem extends \yii\db\ActiveRecord
{

    public static function tableName()
    {
        return 'problem';
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

    public function name($id){
        return Podr::findOne($id)->name;
    }

}
