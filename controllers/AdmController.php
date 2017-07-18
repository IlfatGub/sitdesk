<?php

namespace app\controllers;

session_start();

use app\modules\admin\models\Podr;
use yii\helpers\Html;
use app\models\Sitdesk;
use app\modules\admin\models\AppComment;
use app\modules\admin\models\AppContent;
use app\modules\admin\models\Call;
use app\modules\admin\models\Fio;
use app\modules\admin\models\History;
use app\modules\admin\models\Problem;
use Codeception\Step\Comment;
use Yii;
use yii\debug\models\search\Log;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\modules\admin\models\Login;
use app\models\User;
use app\modules\admin\models\App;
use app\modules\admin\models\MyDate;
use app\modules\admin\models\Recal;
use yii\helpers\ArrayHelper;

//date_default_timezone_set('Asia/Yekaterinburg');

class AdmController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionProblem($delete = null)
    {
        $model =  new Problem();

        if(isset($delete)){
//            Problem::deleteAll(['id' => $delete]);
            $var = Problem::findOne($delete);
            $var->visible = 0;
            $var->save();
        }else{
            if ($model->load(Yii::$app->request->post())) {
                $model->name = Html::encode($model->name);
                $model->visible = 1;
                $model->save();
            }
        }

        $list = Problem::find()->where(['visible' => 1])->all();

        return $this->renderAjax('problem', [
            'model' => $model,
            'list' => $list
        ]);
    }

    public function  actionCreatelogin(){
        $model =  new Login();

        if ($model->load(Yii::$app->request->post())) {
                $model->login = Html::encode($model->login);
                $model->close = 0;
                $model->username = '-';
                $model->post = '-';
                $model->count = 10;
                $model->menu = 0;
                $model->settings_comment = 0;
                $model->visible = 0;
                $model->assist = 0;
                $model->role = 100;
                $model->depart = 1;
                $model->comment_list = 'Выполнено, Не берут трубку';
                $model->save();
        }

        $list = Login::find()->orderBy(['visible' => SORT_ASC, 'role' => SORT_DESC])->all();

        return $this->renderAjax('login', [
            'model' => $model,
            'list' => $list
        ]);
    }

    public function actionLogin($id=null, $vis=null, $text=null, $type=null)
    {
        $model =  new Login();

            if(isset($text)){
                $login = Login::findOne($id);
                if($type==1){
                    $login->login = $text;
                }elseif ($type==2){
                    $login->username = $text;
                }elseif ($type==3){
                    $login->post = $text;
                }elseif ($type==4){
                    $login->role = $text;
                }elseif ($type==5){
                    $login->depart = $text;
                }
                $login->save();
            }

            if(isset($vis)){
                $login = Login::findOne($id);
                $login->visible = $vis;
                $login->save();
            }

        $list = Login::find()->orderBy(['visible' => SORT_ASC, 'depart' => SORT_ASC])->andWhere(['<>', 'visible', '100'])->all();

        return $this->renderAjax('login', [
            'model' => $model,
            'list' => $list
        ]);
    }

    public function actionPodr($delete = null)
    {
        $model =  new Podr();

        if(isset($delete)){
//            Podr::deleteAll(['id' => $delete]);
            $var = Podr::findOne($delete);
            $var->visible = 0;
            $var->save();
        }else{
            if ($model->load(Yii::$app->request->post())) {
                $model->name = Html::encode($model->name);
                $model->visible = 1;
                $model->save();
            }
        }

        $list = Podr::find()->where(['visible' => 1])->all();

        return $this->renderAjax('podr', [
            'model' => $model,
            'list' => $list
        ]);
    }

}


