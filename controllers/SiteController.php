<?php

namespace app\controllers;

session_start();

use app\models\Sitdesk;
use app\modules\admin\models\AppComment;
use app\modules\admin\models\AppContent;
use app\modules\admin\models\Call;
use app\modules\admin\models\Fio;
use app\modules\admin\models\History;
//use Codeception\Step\Comment;
use Yii;
use yii\debug\models\search\Log;
use yii\filters\AccessControl;
use yii\helpers\Html;
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
use app\modules\admin\models\Comment;

//date_default_timezone_set('Asia/Yekaterinburg');

class SiteController extends Controller
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

    /*
     * Вывод статистики заведеных заявок
     */
    public function actionStat() {
        $logins = Login::find()->all();

        $date_to = Yii::$app->request->post() ? Yii::$app->request->post('date_to') : date('Y-m-d');
        $date_do = Yii::$app->request->post() ? Yii::$app->request->post('date_do') : date('Y-m-d');

        $calls = Call::find()
            ->andFilterWhere(['>=', 'date', MyDate::getTimestamp($date_to.' 00:00:00')])
            ->andFilterWhere(['<=', 'date', MyDate::getTimestamp($date_do.' 23:59:59')])->all();

        $countCall = 0;
        foreach ($calls as $call){
            $countCall = $countCall + $call->count;
        }

        foreach ($logins as $login) {
            $loginStat = App::find()
                ->andFilterWhere(['id_user' => $login->id])
                ->andFilterWhere(['>', 'date_ct', MyDate::getTimestamp($date_to.' 00:00:00')])
                ->andFilterWhere(['<', 'date_ct', MyDate::getTimestamp($date_do.' 23:59:59')])->count();
            $loginStat1 = App::find()
                ->andFilterWhere(['id_user' => $login->id])
                ->andFilterWhere(['>', 'date_ct', MyDate::getTimestamp($date_to.' 00:00:00')])
                ->andFilterWhere(['<', 'date_ct', MyDate::getTimestamp($date_do.' 23:59:59')])
                ->andFilterWhere(['status' => 1])->count();
            $loginStat2 = App::find()
                ->andFilterWhere(['id_user' => $login->id])
                ->andFilterWhere(['>', 'date_ct', MyDate::getTimestamp($date_to.' 00:00:00')])
                ->andFilterWhere(['<', 'date_ct', MyDate::getTimestamp($date_do.' 23:59:59')])
                ->andFilterWhere(['status' => 2])->count();
            $loginStat3 = App::find()
                ->andFilterWhere(['id_user' => $login->id])
                ->andFilterWhere(['>', 'date_ct', MyDate::getTimestamp($date_to.' 00:00:00')])
                ->andFilterWhere(['<', 'date_ct', MyDate::getTimestamp($date_do.' 23:59:59')])
                ->andFilterWhere(['status' => 3])->count();

            $user[$login->username]['username'] = $login->username;
            $user[$login->username]['count'] = $loginStat;
            $user[$login->username]['activ'] = $loginStat1;
            $user[$login->username]['pending'] = $loginStat2;
            $user[$login->username]['close'] = $loginStat3;
        }


        return $this->renderAjax('stat', [
            'date_to' => $date_to,
            'date_do' => $date_do,
            'user' => $user,
            'countCall' => $countCall
        ]);
    }

    /*
     * Вывод заявок для Диспетчера, для отметки.
     * Ометить под одному
     * Отметить все заявки
     */
    public function actionClose()
    {
        if(isset($_GET['delete'])){
            if($_GET['delete'] == 'All'){
                $content = AppContent::find()->select(['id_app'])->andWhere(['review' => 1])->all();
                $model = App::find()->andwhere(['app.id' => ArrayHelper::getColumn($content, 'id_app')])->andwhere(['status' => 3])->joinWith(['appContent'])->all();
                foreach ($model as $item) {
                    $upd = AppContent::findOne($item->appContent->id);
                    $upd->review = null;
                    $upd->save();
                }
            }else{
                $upd = AppContent::findOne($_GET['delete']);
                $upd->review = null;
                $upd->save();
            }
        }

        $count = AppContent::find()->select(['id'])->andWhere(['review' => 1])->count();
        if($count > 0){
            $content = AppContent::find()->select(['id_app'])->andWhere(['review' => 1])->all();
            $model = App::find()
                ->andWhere(['app.id' => ArrayHelper::getColumn($content, 'id_app')])
                ->andWhere(['status' => 3])
                ->joinWith(['appContent'])
                ->joinWith(['podr'])
                ->joinWith(['priority'])
                ->joinWith(['problem'])
                ->joinWith(['user'])
                ->orderBy(['status' => SORT_DESC])
                ->all();
            $comments = AppComment::find()->joinWith(['comments'])->where(['id_app' =>  ArrayHelper::getColumn($content, 'id_app')])->orderBy(['date' => SORT_DESC])->joinWith(['user'])->all();
            $status = '';
        }else{
            $status = "Нет не прочтенных";
            $model = null;$comments = null;
        }

        return $this->renderAjax('close', ['model' => $model, 'comments' => $comments, 'status' => $status]);
    }

    /*
     * Страница настройек пользователя
     */
    public function actionSettings()
    {

        $model = Login::findOne($_SESSION['User']['id']);

        if ($model->load(Yii::$app->request->post())) {
            $model->ip = str_replace(',', '.', $model->ip);
            $model->save();

            Login::userSettings($model);

            return $this->render('settings', [
                'model' => $model,
            ]);
        } else {
            return $this->render('settings', [
                'model' => $model,
            ]);
        }
    }

    /*
     * Удаляем завяку, и все что с ней связано
     */
    public function actionDelete($id, $search = null){
        App::findOne($id)->delete();
        AppContent::findOne(['id_app' => $id])->delete();
        AppComment::deleteAll(['id_app' => $id]);
        History::deleteAll(['id_app' => $id]);
        return  $this->redirect(['index', 'search' => $search]);
    }

    public function actionIndex($search = null)
    {
        $model = new App();

        if(!isset($_SESSION['User']['id'])){
            $_SESSION['User']['id']         = null;
            $_SESSION['User']['login']      = null;
            $_SESSION['User']['username']   = null;
            $_SESSION['User']['role']       = null;
            $_SESSION['User']['close']      = null;
            $_SESSION['User']['count']      = null;
            $_SESSION['User']['menu']       = null;

            return $this->redirect(Url::toRoute('site/login'));
        }


        //Меняем на просмотренный
        if(isset($_GET['id'])){
            App::appReview($_GET['id'], 1);
        }

        /* Если нет АйДи в строке запроса, приваимваем АйДи , последней активной заявки, или же последней закрытой заявки*/
        if (!isset($_GET['id'])){
            $_GET['id'] = App::getIdApp(isset($_GET['search']) ? $_GET['search'] : null);
        }

                        //вывод звонков(Справочная)

//        $model->isNewRecord = 1;
        if ($model->load(Yii::$app->request->post()) && $model->validate()){
            if($_GET){
                if(isset($_GET['app'])){
                    $app = new App();
                    $app->id_podr = $model->id_podr;
                    $app->id_problem = $model->id_problem;
                    $app->date_ct = MyDate::getTimestamp(date('Y-m-d H:i:s'));
                    $app->id_priority = $model->id_priority;
                    $app->id_user = $model->id_user;
                    $app->review = '1';
                    $app->status = '1';
                    $app->type = $model->type ? 1 : null ;
                    $app->save();

                    $lastId = App::find()->limit(1)->orderBy(['id' => SORT_DESC])->one();
                    $appContetn = new AppContent();
                    $appContetn->id_app = $lastId->id;
                    $appContetn->id_user = $_SESSION['User']['id'];
                    $appContetn->content = $model->content;
                    $appContetn->note = Html::encode($model->note);
                    $appContetn->id_fio = Fio::getId($model->fio);
                    $appContetn->ip =  $model->ip == '10.224.' ? '' : Html::encode(str_replace(',', '.', $model->ip));
                    $appContetn->phone = Html::encode($model->phone);
                    $appContetn->dv = $model->type ? Html::encode($model->type) : null ;
                    $appContetn->review = 1;
                    $appContetn->save();

                    History::newHistory($lastId->id, $_SESSION['User']['id'], 1, $model->id_user);

                    Sitdesk::appMail($lastId->id, $model->id_user);

                    return $this->redirect(['/site/index' , 'app' => '1']);

                }elseif ($_GET['id']){
                    $upd = App::findOne($_GET['id']);
                    $upd->id_podr = $model->id_podr;
                    $upd->id_problem = $model->id_problem;
                    $upd->id_priority = $model->id_priority;
                    $upd->id_user = $model->id_user;
                        /* Если после изменения исполнители отличаются, то статуст меняем на Перенаправлен */
                        if($model->id_user == App::findOne($_GET['id'])->id_user){
                            History::newHistory($_GET['id'], $_SESSION['User']['id'], 2, $model->id_user);
                        }else{
                            Sitdesk::appMail($_GET['id'],  $model->id_user, 1);
                            History::newHistory($_GET['id'], $_SESSION['User']['id'], 3, $model->id_user);
                            App::appReview($_GET['id'], 2);
                        }
//                    $upd->status = '1';
                    $upd->type = $model->type ? 1 : null ;
                    $upd->save();

                    $appContetn = AppContent::findOne(['id_app' => $_GET['id']]);
                    $appContetn->id_user = $_SESSION['User']['id'];
                    $appContetn->content = $model->content;
                    $appContetn->note = $model->note;
                    $appContetn->id_fio = Fio::getId($model->fio);
                    $appContetn->ip = str_replace(',', '.', $model->ip);
                    $appContetn->phone = $model->phone;
                    $appContetn->dv = $model->type ? $model->type : null ;
                    $appContetn->save();

                    return $this->redirect(['index', 'search' => isset($_GET['search']) ? $_GET['search'] : null, 'id' => $_GET['id']]);
                }
            }

            return $this->redirect(['index' , 'search' => isset($_GET['search']) ? $_GET['search'] : null]);

        }

        /* Если у пользователя нет ни одной заявки(Открытых/Закрытых/В ожидании)*/
        if($_GET['id'] == null){
            $model = new App();
            $comment = AppComment::commentList($_GET['id']);    //вывод коментарий заявки
            $recal = Recal::recalList();                        //вывод напоминаний
            $call = Call::getCount();                           //вывод звонков(Справочная)

            return $this->render('index',
                [
                    'model' => $model,
                    'comment'=> $comment,
                    'recal' => $recal,
                    'call' => $call
                ]);
        }

        $model = App::appList($_GET['id']);                 //вывод всей информации завяки
        $comment = AppComment::commentList($_GET['id']);    //вывод коментарий заявки
        $recal = Recal::recalList();                        //вывод напоминаний
        $call = Call::getCount();

        return $this->render('index',
            [
                'model' => $model,
                'comment'=> $comment,
                'recal' => $recal,
                'call' => $call
            ]);
    }

    /*
     * Показываем историю заявки
     */
    public function actionHistory($id = null){
        if(isset($id)){
            $history = History::find()
                ->where(['id_app' => $id])
                ->orderBy(['date' => SORT_DESC])
                ->joinWith(['usercomment' => function($q) {$q->select(['id', 'username']);}])
                ->all();
            $status = true;
        }else{
            $history = array();
            $status = false;
        }

        return $this->renderAjax('history',
            [
                'history' => $history,
                'status' => $status,
            ]
        );
    }

    /*
     * Авторизация через АД
     */
    public function actionLogin()
    {
        $model = new LoginForm();

        $countLogin = Login::find()->where(['ip' => $_SERVER['REMOTE_ADDR']])->count();
        if($countLogin > 0){
            $role = Login::findOne(['ip' => $_SERVER['REMOTE_ADDR']]);

            Login::userSettings($role);
            if($_SESSION['User']['visible'] == 1){
                Login::userSettingsUnset();
                return $this->redirect('login');
            }
            return $this->redirect(Url::to(['index']));
        }

        if(isset($_POST['LoginForm']['username'])){
            //ip адрес или название сервера ldap(AD)
            $ldaphost = "snhrs.ru";
            //Порт подключения
            $ldapport = "389";
            //Полный путь к группе которой должен принадлежать человек, что бы пройти аутентификацию.
            //"cn=allow_ppl,ou=users_IT,ou=IT,ou=Kyiv,ou=corp,dc=eddnet,dc=org" - это
            //мой пример.
            $memberof = "DC=snhrs,DC=ru";
            //Откуда начинаем искать
            $base = "DC=snhrs,DC=ru";
            //Собственно говоря фильтр по которому будем аутентифицировать пользователя
            $filter = "sAMAccountName=";
            //Ваш домен, обязательно с собакой впереди. Необходим этот параметр
            //для авторизации через AD, по другому к сожалению работать не будет.
            $domain = "@snhrs.ru";
            $login = $_POST['LoginForm']['username'].$domain;
            $password = $_POST['LoginForm']['password'];
            //подсоединяемся к LDAP серверу
            $ldap = ldap_connect($ldaphost,$ldapport) or die("Cant connect to LDAP Server");
            //Включаем LDAP протокол версии 3
            ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION, 3);


            if((ldap_bind($ldap,$login,$password))){
                // Пытаемся войти в LDAP при помощи введенных логина и пароля
                $bind = ldap_bind($ldap,$login,$password);
            }

            if (isset($bind)){
                $role = Login::find()->where(['login' => $_POST['LoginForm']['username']])->one();
                if($role->id){
                    Login::userSettings($role);
                    if($_SESSION['User']['visible'] == 1){
                        Login::userSettingsUnset();
                        return $this->redirect('login');
                    }
                }else{
                    Login::userSettingsUnset();
                }
                return $this->redirect(Url::to(['index']));
            }else{
                return $this->redirect('logins');
            }
        }
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /*
     * Очистка данных при выхода из системы
     */
    public function actionLogoutuser()
    {
        Login::userSettingsUnset();

//        unset($_SESSION['User']['id']);
//        unset($_SESSION['User']['login']);
//        unset($_SESSION['User']['username']);
//        unset($_SESSION['User']['role']);

        return $this->goHome();
    }

    /*
     * Меняем статус заявке
     */
    public function actionStatus($id, $status, $search = null)
    {
        if($status == 3){
            if(AppComment::appComment($_GET['id'])){
                App::Status($id, $status);
                History::newHistory($id, $_SESSION['User']['id'], $status+3, App::findOne($id)->id_user);
            }
            $ret = $this->redirect(['index', 'search' => $search]);
        }elseif($status == 4){
            App::Status($id, 3, 1);
            History::newHistory($id, $_SESSION['User']['id'], $status+3, App::findOne($id)->id_user);
            Comment::commentAdd($id, 'Выполнено');
            App::appReview($id, 1);
            $ret = $this->redirect(['index', 'search' => $search]);
        }else{
            App::Status($id, $status);
            History::newHistory($id, $_SESSION['User']['id'], $status+3, App::findOne($id)->id_user);
            $ret = $status == 3 ? $this->redirect(['index', 'search' => $search]) : $this->redirect(['index', 'id' => $id, 'search' => $search]);
        }
        return $ret;

    }

    /*
     * Вывод из Logs
     */
    public function actionLogs($search)
    {
        return $this->renderAjax('logs', [
            'search' => $search
        ]);
    }

    /*
     * Вывод из Phone
     */
    public function actionPhone($search)
    {
        return $this->renderAjax('phone', [
            'search' => $search
        ]);
    }

    /*
     * Справочная для Диспетчера
     */
    public function actionCall()
    {
        Call::add(); //Добовяем звонок(Справочная)

        if(isset($_GET['id'])){
            $model = App::appList($_GET['id']);                 //вывод всей информации завяки
        }else{
            $_GET['id'] = null;
            $model = new App();
        }

        $comment = AppComment::commentList($_GET['id']);    //вывод коментарий заявки
        $recal = Recal::recalList();                        //вывод напоминаний

        return $this->render('/site/index',
            [
                'model' => $model,
                'comment' => $comment,
                'recal' => $recal,
            ]);
    }

    public function actionMail(){
        return $this->render('mail');
    }

    /*
     * Информация о сайте
     */
    public function actionInfo(){
        return $this->render('info');
    }
}


