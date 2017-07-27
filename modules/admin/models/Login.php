<?php

namespace app\modules\admin\models;

use Yii;
use yii\bootstrap;

Yii::$app->session->open();

class Login extends \yii\db\ActiveRecord
{

    public static function tableName()
    {
        return 'login';
    }


    public function rules()
    {
        return [
            [['role', 'count', 'menu', 'visible',  'close', 'assist'], 'integer'],
            [['username', 'post', 'ip'], 'string', 'max' => 255],
            [['comment_list'], 'string', 'max' => 1000],
            [['login'], 'string', 'max' => 32],
            [['settings_menu', 'settings_comment', 'depart'], 'integer',]
        ];
    }


    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'ФИО',
            'login' => 'Логин',
            'role' => 'Роль',
            'close' => 'Закрывать заявку при "Выполнено"?',
            'assist' => 'Хочешь видеть невыполненные заявки коллег?',
            'settings_menu' => 'Изменить размер левого меню',
        ];
    }

    public function Username($id){
        $model = Login::findOne(['id' =>$id]);
        return $model->login;
    }

    /*
     * Присваиваем глобальные переменные для Пользователя
     */
    public function userSettings($role){
        $_SESSION['User']['id'] =                   $role->id;
        $_SESSION['User']['login'] =                $role->login;
        $_SESSION['User']['username'] =             $role->username;
        $_SESSION['User']['role'] =                 $role->role;
        $_SESSION['User']['close'] =                $role->close;
        $_SESSION['User']['count'] =                $role->count;
        $_SESSION['User']['menu'] =                 $role->menu;
        $_SESSION['User']['settings_comment'] =     $role->settings_comment;
        $_SESSION['User']['assist'] =               $role->assist;
        $_SESSION['User']['visible'] =              $role->visible;
        $_SESSION['User']['depart'] =               $role->depart;
        $_SESSION['User']['settings_menu'] =        $role->settings_menu;
        $_SESSION['User']['comment_list'] =         $role->comment_list;

//        Yii::$app->session['User']['id']                    = $role->id;
//        Yii::$app->session['User']['login']                 = $role->login;
//        Yii::$app->session['User']['username']              = $role->username;
//        Yii::$app->session['User']['role']                  = $role->role;
//        Yii::$app->session['User']['close']                 = $role->close;
//        Yii::$app->session['User']['count']                 = $role->count;
//        Yii::$app->session['User']['menu']                  = $role->menu;
//        Yii::$app->session['User']['settings_comment']      = $role->settings_comment;
//        Yii::$app->session['User']['assist']                = $role->assist;
//        Yii::$app->session['User']['visible']               = $role->visible;
//        Yii::$app->session['User']['depart']                = $role->depart;
//        Yii::$app->session['User']['settings_menu']         = $role->settings_menu;
//        Yii::$app->session['User']['comment_list']          = $role->comment_list;
    }

    public function userSettingsUnset(){
        $_SESSION['User']['id'] =               null;
        $_SESSION['User']['login'] =            null;
        $_SESSION['User']['username'] =         null;
        $_SESSION['User']['role'] =             null;
        $_SESSION['User']['close'] =            null;
        $_SESSION['User']['count'] =            null;
        $_SESSION['User']['menu'] =             null;
        $_SESSION['User']['settings_comment'] = null;
        $_SESSION['User']['settings_menu'] =    null;
        $_SESSION['User']['assist'] =           null;
        $_SESSION['User']['visible'] =          null;
        $_SESSION['User']['depart'] =           null;
        $_SESSION['User']['comment_list'] =     null;

//        Yii::$app->session['User']['id']                    = null;
//        Yii::$app->session['User']['login']                 = null;
//        Yii::$app->session['User']['username']              = null;
//        Yii::$app->session['User']['role']                  = null;
//        Yii::$app->session['User']['close']                 = null;
//        Yii::$app->session['User']['count']                 = null;
//        Yii::$app->session['User']['menu']                  = null;
//        Yii::$app->session['User']['settings_comment']      = null;
//        Yii::$app->session['User']['assist']                = null;
//        Yii::$app->session['User']['visible']               = null;
//        Yii::$app->session['User']['depart']                = null;
//        Yii::$app->session['User']['settings_menu']         = null;
//        Yii::$app->session['User']['comment_list']          = null;

    }

    /*
     * Проверка на наличие открытых заявок. Со статусом "В Работу"
     *
     * */
    public function validateLoginApp($id){
        if($id <> $_SESSION['User']['id']){
            return App::find()
                ->andWhere(['id_user' => $id])
                ->andWhere(['status' => 1])
                ->andWhere(['type' => null])
                ->count();
        }
    }
}
