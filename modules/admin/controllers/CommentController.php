<?php
namespace app\modules\admin\controllers;
session_start();
use app\modules\admin\models\Comment;
use app\modules\admin\models\MyDate;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\admin\models\AppComment;
use app\modules\admin\models\App;
use app\modules\admin\models\History;
use app\modules\admin\models\Recal;
use app\modules\admin\models\Call;

date_default_timezone_set('Asia/Yekaterinburg');

class CommentController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }


    public function actionIndex()
    {
        $searchModel = new LoginSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }


    public function actionCreate($id, $comment = null)
    {
        $model = new AppComment();

        if(isset($comment)){ $_POST['App']['comment'] = $comment; }

        if($_POST['App']['comment']){

            Comment::commentAdd($id, $_POST['App']['comment']);

            /*
             * Если в настройках пользователя указано на закрытие заявки, то при
             * добавлении комментарий "Выполнено", заявка автоматически закроется
             * Если не установлена, то страница перезагрузиться что бы появилась кнопка "Закрыть"
             */
            if(mb_strtolower($_POST['App']['comment'], "UTF-8") == 'выполнено'){
                if(($_SESSION['User']['close'] == 1) and ($_SESSION['User']['id'] == App::findOne($id)->id_user) ){
                    App::Status($id, 3);
                    return $this->redirect(['//site/index']);
                }
                return $this->redirect(['//site/index', 'id' => $_GET['id']]);
            }

            if(AppComment::find()->where(['id_app' => $_GET['id']])->count() == 1){
                return $this->redirect(['//site/index', 'id' => $_GET['id']]);
            }
        }

        $model = App::appList($_GET['id']);                 //вывод всей информации завяки
        $comment = AppComment::commentList($_GET['id']);    //вывод коментарий заявки
        $recal = Recal::recalList();                        //вывод напоминаний

        return $this->render('//site/index',
            [
                'model' => $model,
                'comment' => $comment,
                'recal' => $recal,
            ]);
    }

    /**
     * Updates an existing Executor model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Executor model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Executor model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Executor the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Login::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}



