<?php

namespace app\modules\admin\models;

use Yii;

class AppContent extends \yii\db\ActiveRecord
{

    public static function tableName()
    {
        return 'appContent';
    }


    public function rules()
    {
        return [
            [['id_app', 'id_user', 'date_cl', 'content', 'id_fio', 'ip', 'dv', 'phone', 'note'], 'safe'],
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

    public function getFio()
    {
        return $this->hasOne(Fio::className(),['id'=>'id_fio']);
    }
    /*
     * Добовляем, или обновляем Контент
     * $type 1 - Добавляем
     * $type 2 - Обновляем
     */
    public function AppContentRecord($id_app = null, $id_user, $content, $id_fio, $ip, $phone, $dv, $type){
        if($type == 1){
            $lastId = App::find()->limit(1)->orderBy(['id' => SORT_DESC])->one();  // Получаем АЙДИ последней заявки. Что бы присвоить его для Конетнта заявки.
            History::newHistory($lastId->id,$id_user, 1);   // Добавляем запись в историю заявки. "Создан"
            $appContetn = new AppContent();
            $appContetn->id_app = $lastId->id;
        }else{
            $appContetn = AppContent::findOne(['id_app' => $id_app]);
        }
        $appContetn->id_user = $id_user;
        $appContetn->content = $content;
        $appContetn->id_fio = Fio::getId($id_fio);
        $appContetn->ip = $ip;
        $appContetn->phone = $phone;
        $appContetn->dv = $dv ? $dv : null;
        $appContetn->save();
    }}
