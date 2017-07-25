<?php

namespace app\models;

use app\modules\admin\models\Login;
use Yii;
use yii\base\Model;
use app\modules\admin\models\App;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

/**
 * LoginForm is the model behind the login form.
 */
class Sitdesk extends Model
{

    /*
     *  Отправка на почту.
     *
     */
    public function appMail($id, $user, $type = null){


        $model =  App::find()
            ->where(['app.id' => $id])
            ->joinWith(['problem' , 'priority'])
            ->joinWith(['user' => function($q){$q->select(['id', 'login']);}])
            ->joinWith(['priority', 'podr'])
            ->joinWith(['appContent' => function($q) {$q->joinwith(['fio']); }])
            ->one();

        $mailTo = Login::findOne($user)->login."@snhrs.ru";

        $danger = $model->id_priority == 3 ? 'bg-danger' : '';

        $message = "";
        $message .=
            "
<table style='width: 600px' class='app_mail_table'>";
if($type == 1){
    $message .= "<tr style='background: #F0AD4E;  color: white; text-align: center; font-size: 14pt'><td colspan = 2>Перенаправленна заявка</td></tr>";
}else{
    $message .= "<tr style='background: #337AB7;  color: white; text-align: center; font-size: 14pt'><td colspan = 2>Зарегестрирована новая заявка</td></tr>";
}
$message .="
<tr class='app_mail'>
    <td style='border-bottom:1px solid silver; padding: 2px; background: #E5EFFD; width: 25%'>Номер</td>                         
    <td style='border-bottom:1px solid silver; padding: 2px;'> <a href='http://sitdesk.snhrs.ru/index?id=".$id."'>" .$model->id."</a>  ( <a href='http://sitdesk.snhrs.ru/status?id=".$id."&status=4'>Закрыть</a> )   </td>       
</tr>
<tr class='app_mail'> 
    <td style='border-bottom:1px solid silver; padding: 2px; background: #E5EFFD;'>Приоритет</td>                     
    <td style='border-bottom:1px solid silver; padding: 2px;'>".$model->priority->name."</td>
</tr>
<tr class='app_mail'> 
    <td style='border-bottom:1px solid silver; padding: 2px; background: #E5EFFD;'>Подразделение</td>                 
    <td style='border-bottom:1px solid silver; padding: 2px;'>".$model->podr->name."</td>       
</tr>
<tr class='app_mail'> 
    <td style='border-bottom:1px solid silver; padding: 2px; background: #E5EFFD;'>Тип проблемы</td>                  
    <td style='border-bottom:1px solid silver; padding: 2px;'>".$model->problem->name."</td>       
</tr>
<tr class='app_mail'> 
    <td style='border-bottom:1px solid silver; padding: 2px; background: #E5EFFD;'>ФИО</td>                           
    <td style='border-bottom:1px solid silver; padding: 2px;'>".$model->appContent->fio->name."</td>       
</tr>
<tr class='app_mail'> 
    <td style='border-bottom:1px solid silver; padding: 2px; background: #E5EFFD;'>Телефон</td>                       
    <td style='border-bottom:1px solid silver; padding: 2px;'>".$model->appContent->phone."</td>      
</tr>
<tr class='app_mail'> 
    <td style='border-bottom:1px solid silver; padding: 2px; background: #E5EFFD;'>IP</td>                            
    <td style='border-bottom:1px solid silver; padding: 2px;'>".$model->appContent->ip."</td>       
</tr>";
if($model->type == 1){
$message .= "<tr class='app_mail'> 
            <td style='border-bottom:1px solid silver; padding: 2px; background: #E5EFFD;'>DV</td>                  
            <td style='border-bottom:1px solid silver; padding: 2px;'>".$model->appContent->dv."</td>       
        </tr>";
}
if($type == 1){
    $message .= "<tr class='app_mail' style='background: #F0AD4E; color: white; text-align: center;'><td colspan = 2>Описание</td></tr>";
}else{
    $message .= "<tr class='app_mail' style='background: #337AB7; color: white; text-align: center;'><td colspan = 2>Описание</td></tr>";
}
$message .= "
<tr class='app_mail'><td colspan = 2 style='border-bottom:1px solid silver; padding: 2px;'>".$model->appContent->content."</td></tr>
</table>
";

//echo $message;

//echo $mailTo;

        Yii::$app->mailer->compose()
            ->setFrom('ticket00@snhrs.ru')
            //    ->setTo('01gig@snhrs.ru')
            //    ->setTo(['01gig@snhrs.ru', 'ilshat@snhrs.ru'])
            //    ->setTo(['01gig@snhrs.ru', 'ilfat.gubaidullin@gmail.com', 'ilshat@snhrs.ru', 'gub.ilshat@gmail.com' ])
            ->setTo([str_replace(" ", "", $mailTo)])
            ->setSubject('New ticket '.$id)
            ->setHtmlBody($message)
            ->send();
    }

    /*
     * ФИО
     * type = 1     -       выводи только Имени
     */
    public function fio($fio, $type = null){
        if(isset($fio)){
            $var = explode(" ", trim($fio));
            if($type == 1){
                $text = $var[1].'.'. mb_substr($var[0], 0, 1, 'UTF-8');
            }else{
                $text = $var[0].' '.$var[1];
            }
        }else{
            $text = $fio;
        }
        return $text;
    }

    /*
     * Преобразуем в массив
     */
    public function CommList($comment){
        return explode(',', $comment);
    }

    /*
     * Проверка на наличие заявок у коллег
     */
    public function countAssist(){
        return  App::find()
            ->joinWith(['problem'])
            ->joinWith(['user' => function($q){$q->select(['id', 'username']);}])
            ->andWhere(['type' => null])
            ->andWhere(['status' => 1])
            ->andWhere(['id_user' => ArrayHelper::map( Login::find()->where(['depart' => $_SESSION['User']['depart']])->all(), 'id', 'id')])
            ->andWhere('id_user != :id_user', ['id_user' => $_SESSION['User']['id']])
            ->select(['app.id', 'id_user', 'id_priority', 'id_problem', 'review'])
            ->orderBy(['id_user' => SORT_DESC])
            ->count();
    }
}
