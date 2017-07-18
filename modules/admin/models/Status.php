<?php

namespace app\modules\admin\models;


class Status extends \yii\db\ActiveRecord
{

    public static function tableName()
    {
        return 'status';
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
            'name' => 'name',
        ];
    }

    public function Name($id){
        switch ($id){
            case 1: return 'В работе'; break;
            case 2: return 'В ожидании'; break;
            case 3: return 'Закрыт'; break;
        }
    }


}
