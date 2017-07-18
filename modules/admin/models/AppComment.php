<?php

namespace app\modules\admin\models;

use Yii;
date_default_timezone_set('Asia/Yekaterinburg');

class AppComment extends \yii\db\ActiveRecord
{

    public static function tableName()
    {
        return 'appComment';
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

    public function getFio()
    {
        return $this->hasOne(Fio::className(),['id'=>'id_fio']);
    }
    public function getUser()
    {
        return $this->hasOne(Login::className(),['id'=>'id_user']);
    }
    public function getComments()
    {
        return $this->hasOne(Comment::className(),['id'=>'comment']);
    }
    /*
     * Проверка заявки на коментарии
     */
    public function appComment($id)
    {
        return AppComment::find()->where(['id_app' => $id])->count();
    }
    /*
     * Вывод все комментарии заявки.
     */
    public function commentList($id){
        return AppComment::find()->where(['id_app' => $id])->orderBy(['date' => SORT_DESC])->joinWith(['comments','user' => function($q) {$q->select(['id', 'login', 'username']);}])->all();
    }

    /*
     * вывод автора записи(коментарий/напоминиание)
     */
    public function getAuthor($date, $username, $login, $comment){
         if(isset($_SESSION['User']['settings_comment'])){
            echo "<td class='comment-main'>
                <small style='background: #E4E4E4; width:200px !important;'>
                    <div style='display: inline-block' class='comment-date'>".MyDate::getDate($date)."</div>
                    <strong class='comment-username'>".$username."</strong>
                </small>";
                echo nl2br($comment);
            echo "</td>";
         }else{
            echo "<td><small style='background: #E4E4E4;'>".MyDate::getDate($date)."."."<strong>".$login."</strong></small>".nl2br($comment)."</td>";
         }
    }

}
