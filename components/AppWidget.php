<?php

namespace  app\components;
use app\modules\admin\models\App;
use yii\base\Widget;

class AppWidget extends Widget{

    public $id;
    public $search;

    public function init () {
        parent::init();
        if($this->id === null){
            $this->id = 0;
        }
        if($this->search === null){
            $this->search = 0;
        }
    }

    public function run()
    {
        $active = array();
        $pending = array();
        $close = array();

        $search = isset($_GET['search']) ? $_GET['search'] : null;

        $active =  App::appView(1,0, $search);
        $countActive = App::appView(1, 1, $search);

        $pending =  App::appView(2,0, $search);
        $countPending = App::appView(2, 1, $search);

        $close =  App::appView(3, 0, $search);

//        if (isset($_GET['search'])){
//            $act = App::find()
//                ->joinWith(['problem' , 'priority'])
//                ->joinWith(['user' => function($q){$q->select(['id', 'login']);}])
//                ->joinWith(['podr' => function($q){$q->select(['id', 'name']);}])
//                ->joinWith(['appContent' => function($q){$q->joinwith(['fio'])->select(['id_app','content']);}])
//                ->orderBy(['app.date_ct' => SORT_DESC])
//                ->orWhere(['like', 'fio.name', $_GET['search']])
//                ->orWhere(['like', 'appContent.content', $_GET['search']])
//                ->orWhere(['like', 'username', $_GET['search']])
//                ->orWhere(['like', 'appContent.dv', $_GET['search']])
//                ->andWhere(['=', 'status', 1]);
//
//            $pen = App::find()
//                ->joinWith(['problem' , 'priority'])
//                ->joinWith(['user' => function($q){$q->select(['id', 'login']);}])
//                ->joinWith(['podr' => function($q){$q->select(['id', 'name']);}])
//                ->joinWith(['appContent' => function($q){$q->joinwith(['fio'])->select(['id_app','content']);}])
//                ->orderBy(['app.date_ct' => SORT_DESC])
//                ->orWhere(['like', 'appContent.content', $_GET['search']])
//                ->orWhere(['like', 'username', $_GET['search']])
//                ->orWhere(['like', 'appContent.dv', $_GET['search']])
//                ->andFilterWhere(['=', 'status', 2]);
//
//            $close = App::find()
//                ->joinWith(['problem' , 'priority'])
//                ->joinWith(['user' => function($q){$q->select(['id', 'login']);}])
//                ->joinWith(['podr' => function($q){$q->select(['id', 'name']);}])
//                ->joinWith(['appContent' => function($q){$q->joinwith(['fio'])->select(['id_app','content']);}])
//                ->orderBy(['app.date_ct' => SORT_DESC])
//                ->orWhere(['like', 'username', $_GET['search']])
//                ->orWhere(['like', 'appContent.content', $_GET['search']])
//                ->orWhere(['like', 'appContent.dv', $_GET['search']])
//                ->andWhere(['=', 'status', 3])->limit($_SESSION['User']['count'])->all();
//
//            $active = $act->all();
//            $pending = $pen->all();
//            $countActive = $act->count();
//            $countPending = $pen->count();
//        }else{
//            if($_SESSION['User']['role'] == '105'){
//                $act = App::find()
//                    ->joinWith(['problem' , 'priority'])
//                    ->joinWith(['user' => function($q){$q->select(['id', 'login']);}])
//                    ->joinWith(['podr' => function($q){$q->select(['id', 'name']);}])
//                    ->joinWith(['appContent' => function($q){$q->select(['id_app','content']);}])
//                    ->orderBy(['app.date_ct' => SORT_DESC])
//                    ->andWhere(['=', 'status', 1]);
//
//                $pen = App::find()
//                    ->joinWith(['problem' , 'priority'])
//                    ->joinWith(['user' => function($q){$q->select(['id', 'login']);}])
//                    ->joinWith(['podr' => function($q){$q->select(['id', 'name']);}])
//                    ->joinWith(['appContent' => function($q){$q->select(['id_app','content']);}])
//                    ->orderBy(['app.date_ct' => SORT_DESC])
//                    ->andWhere(['=', 'status', 2]);
//
//                $close = App::find()
//                    ->joinWith(['problem' , 'priority'])
//                    ->joinWith(['user' => function($q){$q->select(['id', 'login']);}])
//                    ->joinWith(['podr' => function($q){$q->select(['id', 'name']);}])
//                    ->joinWith(['appContent' => function($q){$q->select(['id_app','content']);}])
//                    ->orderBy(['app.date_ct' => SORT_DESC])
//                    ->andWhere(['=', 'status', 3])
//                    ->limit($_SESSION['User']['count'])->all();
//                $active = $act->all();            $countActive = $act->count();
//                $pending = $pen->all();            $countPending = $pen->count();
//            }else{
//                $active =  App::appView(1,0);
//                $countActive = App::appView(1, 1);
//
//                $pending =  App::appView(2,0);
//                $countPending = App::appView(2, 1);
//
//                $close =  App::appView(3, 0);
//
//            }
//        }

        return $this->render('app',
            [
                'active' => $active,
                'countActive' => $countActive,
                'pending' => $pending,
                'countPending' => $countPending,
                'close' => $close,
            ]);
    }
}