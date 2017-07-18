<?php

namespace app\modules\admin\models;

use phpDocumentor\Reflection\DocBlock\Tags\Return_;
use Yii;

class Recal extends \yii\db\ActiveRecord
{

    public static function tableName()
    {
        return 'recal';
    }

    public function rules()
    {
        return [
            [['id_user','text'], 'safe'],
            [['text'], 'string', 'max' => '255'],
        ];
    }


    public function attributeLabels()
    {
        return [
            'text' => 'text',
        ];
    }

    public function getUser()
    {
        return $this->hasOne(Login::className(),['id'=>'id_user']);
    }

    /*
     * Вывод напоминаний пользователя.
     */
    public function recalList(){
//        return  Recal::find()->joinWith('user')->where(['id_user' => $_SESSION['User']['id']])->orderBy(['date' => SORT_DESC])->joinWith(['user'])->all();
        return  Recal::find()->where(['id_user' => $_SESSION['User']['id']])->orderBy(['date' => SORT_DESC])->select(['text', 'id'])->all();
    }
}
