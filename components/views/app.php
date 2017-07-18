<?php
use yii\helpers\Url;
use app\modules\admin\models\MyDate;
use app\components\AppViewWidget;
?>


<!--    --><?php
//    if(isset($_SESSION['User']['settings_menu'])){
//        if($_SESSION['User']['settings_menu'] == 1){
//            echo 'asdasdasda';
//        }
//    }
//    ?>


    <li class="bg-primary text-center" style="margin:0 25px 0 10px">
        В работе(<?= $countActive ?>)
    </li>



    <?php foreach ($active as $item) { ?>

        <?= AppViewWidget::widget([
            'iReview'=>$item->review,
            'iType' => $item->type,
            'iId' => $item->id,
            'iContent'=> isset($item->appContent->content) ? $item->appContent->content : null,
            'iLogin'=>$item->user->login,
            'iPodr' => $item->podr->name,
            'iProblem' => $item->problem->name,
            'iDate' => $item->date_ct,
            'iPriority' => $item->id_priority,
            'iFio' => isset($item->appContent->fio->name) ? $item->appContent->fio->name : null,
            'iIp' => isset($item->appContent->ip) ? $item->appContent->ip : null,
            'iUsername' => $item->user->username,
        ]) ?>
    <?php } ?>

    <br>

    <li class="text-center" style="background: #B1D6F0;margin:0 25px 0 10px">
        В Ожидании(<?= $countPending ?>)
    </li>

    <?php foreach ($pending as $item) { ?>
        <?= AppViewWidget::widget([
            'iReview'=>$item->review,
            'iType' => $item->type,
            'iId' => $item->id,
            'iContent'=> isset($item->appContent->content) ? $item->appContent->content : null,
            'iLogin'=>$item->user->login,
            'iPodr' => $item->podr->name,
            'iProblem' => $item->problem->name,
            'iDate' => $item->date_ct,
            'iPriority' => $item->id_priority,
            'iFio' => isset($item->appContent->fio->name) ? $item->appContent->fio->name : null,
            'iIp' => isset($item->appContent->ip) ? $item->appContent->ip : null,
            'iUsername' => $item->user->username,

        ]) ?>
    <?php } ?>


    <br>

    <li class="text-center" style="background: #B1D6F0;margin:0 25px 0 10px">
        Закрытые
    </li>

    <?php foreach ($close as $item) { ?>
        <?= AppViewWidget::widget([
            'iReview'=>$item->review,
            'iType' => $item->type,
            'iId' => $item->id,
            'iContent'=> isset($item->appContent->content) ? $item->appContent->content : null,
            'iLogin'=>$item->user->login,
            'iPodr' => $item->podr->name,
            'iProblem' => $item->problem->name,
            'iDate' => $item->date_ct,
            'iPriority' => $item->id_priority,
            'iFio' => isset($item->appContent->fio->name) ? $item->appContent->fio->name : null,
            'iIp' => isset($item->appContent->ip) ? $item->appContent->ip : null,
            'iUsername' => $item->user->username,

        ]) ?>
    <?php } ?>

