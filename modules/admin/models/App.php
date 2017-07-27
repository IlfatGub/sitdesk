<?php

namespace app\modules\admin\models;

use Yii;

class App extends \yii\db\ActiveRecord
{

    public static function tableName()
    {
        return 'app';
    }

    public $fio;
    public $ip;
    public $phone;
    public $content;
    public $comment;
    public $text;
    public $note;

    public function rules()
    {
        return [
            [['id_user', 'id_podr', 'id_problem'], 'required', 'message'=>''],
            [['id_podr', 'id_problem', 'date_ct', 'type', 'id_priority', 'id_user', 'status', 'review', 'note'], 'safe'],
            [['content', 'id_fio', 'ip', 'phone', 'dv' ], 'safe'],
            [['fio'], 'string'],
        ];
    }


    public function attributeLabels()
    {
        return [
            'id_podr' => 'Подр./Отдел',
            'id_problem' => 'Тип проблемы',
            'date_ct' => 'Дата создания',
            'id_priority' => 'Приоритет',
            'id_user' => 'Исполнитель',
            'status' => 'Статус',
            'review' => 'Посмотрен',
            'fio' => 'ФИО',
            'phone' => 'Телефон',
            'content' => 'Описание',
            'type' => '№ служебки',
            'note' => 'Примечание',
        ];
    }

    public function getPodr()
    {
        return $this->hasOne(Podr::className(),['id'=>'id_podr']);
    }
    public function getProblem()
    {
        return $this->hasOne(Problem::className(),['id'=>'id_problem']);
    }
    public function getPriority()
    {
        return $this->hasOne(Priority::className(),['id'=>'id_priority']);
    }
    public function getUser()
    {
        return $this->hasOne(Login::className(),['id'=>'id_user']);
    }
    public function getAppContent()
    {
        return $this->hasOne(AppContent::className(),['id_app'=>'id']);
    }
    public function getAppStatus()
    {
        return $this->hasOne(Status::className(),['id'=>'status']);
    }
    /*
     * Изменяем статус заявки
     * id - АйДи заявки
     */
    public function Status($id, $status, $comment = null){
        $model = App::findOne($id);
        $model->status = $status;
        $model->save();
        if($comment <> 1){
            if($status == 3){
                History::newHistory($id, $_SESSION['User']['id'], 6, $model->id_user);
            }
        }
    }

    /*
     * Получаем АйДи заявки которую нужно открыть после переход на сайт, или удаление заявки.
     * когда переменная АйДи в строке запроса пуста
     * Если в строке запроса есть поисковая строка SEARCH, то АйДи ищет с дополнительным поисковым параметром
     */
    public function getIdApp($search = null)
    {
        if (isset($search)) {
            $act = App::find()->joinWith(['podr', 'problem', 'user', 'priority'])->orderBy(['app.date_ct' => SORT_DESC])
                ->joinWith(['appContent' => function ($q) {
                    $q->joinwith(['fio']);
                }])
                ->orWhere(['like', 'fio.name', $_GET['search']])->orWhere(['like', 'appContent.content', $_GET['search']])->orWhere(['like', 'username', $_GET['search']])->orWhere(['like', 'appContent.dv', $_GET['search']])
                ->andFilterWhere(['=', 'status', 1]);
            $countActive = $act->count();
            if ($countActive > 0) {
                $id = $act->limit(1)->one();
                return $id->id;
            } else {
                $pen = App::find()->joinWith(['podr', 'problem', 'user', 'priority'])->orderBy(['app.date_ct' => SORT_DESC])
                    ->joinWith(['appContent' => function ($q) {
                        $q->joinwith(['fio']);
                    }])
                    ->orWhere(['like', 'fio.name', $_GET['search']])->orWhere(['like', 'appContent.content', $_GET['search']])->orWhere(['like', 'username', $_GET['search']])->orWhere(['like', 'appContent.dv', $_GET['search']])
                    ->andFilterWhere(['=', 'status', 2]);
                $countPending = $pen->count();
                if ($countPending > 0) {
                    $id = $pen->limit(1)->one();
                    return $id->id;
                } else {
                    $id = App::find()->joinWith(['podr', 'problem', 'user', 'priority'])->orderBy(['app.date_ct' => SORT_DESC])
                        ->joinWith(['appContent' => function ($q) {
                            $q->joinwith(['fio']);
                        }])
                        ->orWhere(['like', 'fio.name', $_GET['search']])->orWhere(['like', 'appContent.content', $_GET['search']])->orWhere(['like', 'username', $_GET['search']])->orWhere(['like', 'appContent.dv', $_GET['search']])
                        ->andFilterWhere(['=', 'status', 3])->limit($_SESSION['User']['count'])->limit(1)->one();
                    return isset($id->id) ? $id->id : null;
                }
            }
        } else {
            if ($_SESSION['User']['role'] == '105') {
                $act = App::find()->joinWith(['podr', 'problem', 'user', 'priority'])->orderBy(['app.date_ct' => SORT_DESC])->andFilterWhere(['=', 'status', 1]);
                $countActive = $act->count();
                if ($countActive > 0) {
                    $id = $act->limit(1)->one();
                    return $id->id;
                } else {
                    $pen = App::find()->joinWith(['podr', 'problem', 'user', 'priority'])->orderBy(['app.date_ct' => SORT_DESC])->andFilterWhere(['=', 'status', 2]);
                    $countPending = $pen->count();
                    if ($countPending > 0) {
                        $id = $pen->limit(1)->one();
                        return $id->id;
                    } else {
                        $id = App::find()->joinWith(['podr', 'problem', 'user', 'priority'])->orderBy(['app.date_ct' => SORT_DESC])->andFilterWhere(['=', 'status', 3])->limit($_SESSION['User']['count'])->limit(1)->one();
                        return isset($id->id) ? $id->id : null;
                    }
                }
            }else{
                $act = App::find()->where(['=', 'id_user', $_SESSION['User']['id']])->joinWith(['podr', 'problem', 'user', 'priority'])->orderBy(['app.date_ct' => SORT_DESC])->andFilterWhere(['=', 'status', 1]);
                $countActive = $act->count();
                if ($countActive > 0) {
                    $id = $act->limit(1)->one();
                    return $id->id;
                } else {
                    $pen = App::find()->where(['=', 'id_user', $_SESSION['User']['id']])->joinWith(['podr', 'problem', 'user', 'priority'])->orderBy(['app.date_ct' => SORT_DESC])->andFilterWhere(['=', 'status', 2]);
                    $countPending = $pen->count();
                    if ($countPending > 0) {
                        $id = $pen->limit(1)->one();
                        return $id->id;
                    } else {
                        $id = App::find()->where(['=', 'id_user', $_SESSION['User']['id']])->joinWith(['podr', 'problem', 'user', 'priority'])->orderBy(['app.date_ct' => SORT_DESC])->andFilterWhere(['=', 'status', 3])->limit($_SESSION['User']['count'])->limit(1)->one();
                        return isset($id->id) ? $id->id : null;
                    }
                }
            }
        }
    }

    /*
     * Вывод всей информации заявки.
     */
    public function appList($id){
        return App::find()->where(['app.id' => $id])->joinWith(['appContent' => function($q) {$q->joinwith(['fio']); }])->one();
    }

    /*
     * Выводим заявки(в работу/в ожидании/закрыто)
     * Если пользователь вошел под "Диспетчер" то выводим все завяки, всех пользователей
     * Если выводим закрытые заявки, то проверяем настройки пользователя, на количество вывода закрытых заявок
     * $type, определяет что выводить, массив из заявок или колчиество заявок.
     * $type = 0 - массив
     * $type = 1 - количество
     * Если есть строка поиска то добовляем посик по полям. Не учитывая пользователя. Поиск производится по всем заявкам
     */
    public function appView($status, $type, $search = null){
        $query = App::find()
            ->orderBy(['app.date_ct' => SORT_DESC])
            ->joinWith(['problem' , 'priority'])
            ->joinWith(['user' => function($q){$q->select(['id', 'login', 'username']);}])
            ->joinWith(['podr' => function($q){$q->select(['id', 'name']);}]);
        if (isset($_GET['search'])){
            if($_GET['search'] == 'Все'){
                $query->andWhere(['=', 'status', $status])->limit(50);
            }else{
                $query = $query
                    ->joinWith(['appContent' => function($q){$q->select(['id_app','content','id_fio', 'ip'])->joinwith(['fio']);}])
                    ->orWhere(['like', 'fio.name', $search])
                    ->orWhere(['like', 'app.id', $search])
                    ->orWhere(['like', 'appContent.content', $search])
                    ->orWhere(['like', 'username', $search])
                    ->orWhere(['like', 'appContent.dv', $search])
                    ->andWhere(['=', 'status', $status]);
            }
        }else{
            $query = $query->joinWith(['appContent' => function($q){$q->select(['id_app','content','id_fio', 'ip'])->joinwith(['fio']);}])
                ->andWhere(['=', 'status', $status]);
            if(($_SESSION['User']['role'] == '100') or ($_SESSION['User']['role'] == '110')) {
                $query = $query->andWhere(['=', 'app.id_user', $_SESSION['User']['id']]);
            }
        }
        if($status == 3) {
            $limit = isset($search) ? 40 : $_SESSION['User']['count'];
            $query = $query->limit($limit);
        }
        return $type == 0 ? $query->all() : $query->count();
    }

    /*
     * Меням стату заявки на просмотрено/НЕ просмотрено
     */
    public function appReview($id, $type = 1){
        $review = App::findOne($id);
        if($type == 1){
            if($review->review == 1){
                if($review->id_user == isset($_SESSION['User']['id']) ? $_SESSION['User']['id'] : App::findOne($id)->id_user){
                    $review->review = null;
                    $review->save();
                }
            }
        }else{
            if($review->id_user == isset($_SESSION['User']['id']) ? $_SESSION['User']['id'] : App::findOne($id)->id_user){
                $review->review = 1;
                $review->save();
            }
        }
    }
}

