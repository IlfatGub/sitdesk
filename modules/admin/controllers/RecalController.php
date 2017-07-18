<?php
namespace app\modules\admin\controllers;
session_start();
use app\modules\admin\models\MyDate;
use app\modules\admin\models\Recal;
use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\modules\admin\models\AppComment;
use app\modules\admin\models\App;
use app\modules\admin\models\History;
use app\modules\admin\models\Call;

date_default_timezone_set('Asia/Yekaterinburg');

class RecalController extends Controller
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

    public function actionCreate($del = null)
    {
        $model = new Recal();
//        print_r($_POST);
        if(isset($del)){
            Recal::findOne($del)->delete();
        }else{
            if($_POST['App']['text']){
                $model->text  = $_POST['App']['text'];
                $model->id_user = $_SESSION['User']['id'];
                $model->date = MyDate::getTimestamp(date('Y-m-d H:i:s'));
                $model->save();
            }
        }

        if(isset($_GET['id'])){
            $model = App::appList($_GET['id']);                 //вывод всей информации завяки
        }else{
            $_GET['id'] = null;
            $model = new App();
        }

        $comment = AppComment::commentList($_GET['id']);    //вывод коментарий заявки
        $recal = Recal::recalList();                        //вывод напоминаний


        return $this->render('//site/index',
            [
                'model' => $model,
                'comment' => $comment,
                'recal' => $recal,
            ]);
    }


    protected function findModel($id)
    {
        if (($model = Recal::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }


}

