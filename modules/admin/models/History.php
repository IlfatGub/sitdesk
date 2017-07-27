<?php

namespace app\modules\admin\models;

use Yii;
date_default_timezone_set('Asia/Yekaterinburg');

class History extends \yii\db\ActiveRecord
{

    public static function tableName()
    {
        return 'appHistory';
    }


    public function rules()
    {
        return [
            [['id_app', 'id_user', 'comment', 'date'], 'safe'],
        ];
    }


    public function attributeLabels()
    {
        return [
            'id_app' => 'Подр./Отдел',
            'user_ct' => 'Создатель',
            'date_cl' => 'Дата закрытия',
            'content' => 'описание',
            'id_fio' => 'ФИО',
            'ip' => 'ip',
            'phone' => 'телефон',
            'dv' => 'Посмотрен',
        ];
    }

    public function getUser()
    {
        return $this->hasOne(Login::className(),['id'=>'id_user']);
    }
    public function getUsercomment()
    {
        return $this->hasOne(Login::className(),['id'=>'comment']);
    }
    public function getHistory()
    {
        return $this->hasOne(HistoryStatus::className(),['id'=>'id_history']);
    }

    public function newHistory($id, $user, $status, $login = null){
        $history = new History();
        $history->id_app = $id;
        $history->id_user = isset($user) ? $user : App::findOne($id)->id_user;
        $history->id_history = $status;
        $history->comment = $login;
        $history->date = MyDate::getTimestamp(date('Y-m-d H:i:s'));
        $history->save();
    }
}
