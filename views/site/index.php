<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\typeahead\TypeaheadBasic;
use app\modules\admin\models\Fio;
use app\modules\admin\models\Login;
use app\modules\admin\models\Problem;
use app\modules\admin\models\Priority;
use app\modules\admin\models\Podr;
use yii\widgets\Pjax;
use app\modules\admin\models\MyDate;
use yii\helpers\Url;
use yii\bootstrap\Modal;
use app\modules\admin\models\AppComment;
use app\modules\admin\models\History;
use timurmelnikov\widgets\LoadingOverlayPjax;
use app\components\AssistsWidget;
use app\components\RecalWidget;
use app\modules\admin\models\Status;
use app\modules\admin\models\Comment;

date_default_timezone_set('Asia/Yekaterinburg');
if(isset($_GET['app'])){
    $model->isNewRecord = $_GET['app'] == 1 ? 1 : 0;
    $model->id_podr = '';
    $model->id_problem = '';
    $model->id_user = '';
}

?>

<?php if($_SESSION['User']['login']) {  ?>
    <?php if(($_SESSION['User']['role'] == '105') or ($_SESSION['User']['role'] == '110')) { $disabled = false; }else { $disabled = true; } ?>
    <div class="col-lg-12" style="padding: 0 0 0 40px">
    <!--        Общая информация по заявке -->
        <div class="row">
            <div class="col-lg-5" style="min-width: 550px; max-width: 550px; padding-right: 0; padding-left: 0; margin-right: 15px;">
                    <div class="col-lg-12 block-app bg-sitdesk-block">
                        <div class="ticket-form ">
                            <div class="row">
                                <div class="bg-infos " style="height:31px; background: #E4E4E4; border-radius: 2px;  padding:  5px; margin: 5px">
                                    <div style="display: inline-block; margin-top: -5px; margin-left: -5px">
                                        <?php if($model->isNewRecord) { echo  'Новая заявка'; } else{
                                            echo Html::button('№'.$_GET['id'], [ 'value' =>Url::to(['site/history', 'id' => $_GET['id'] ] ), 'class' => 'btn btn-primary btn-sm modalButton', 'style' => 'margin-right: 10px; background: #E4E4E4; border-color: #E4E4E4; color:black;  ', 'title' => 'История заявки' ]);
                                            echo '<div style="font-size: 10pt; display: inline-block">'. MyDate::getDate($model->date_ct).'('.Status::Name($model->status).')</div>';
                                        }?>
                                    </div>
                                    <?php if(($_SESSION['User']['id'] == $model->id_user) or ($_SESSION['User']['role'] == '105') or ($_SESSION['User']['role'] == 110))  {  ?>
                                        <div style="float: right; display: inline-block; margin-top: -5px; margin-right: -5px ">
                                            <div style="display: inline-block; margin-right: 30px ">
                                                <?php  if(($_SESSION['User']['role'] == 105) or ($_SESSION['User']['role'] == 110)) { ?>
                                                    <?php  if($_SESSION['User']['role'] == 110) { ?>
                                                        <div class="btn-group">
                                                            <button type="button" data-toggle="dropdown" class="btn btn-info btn-sm glyphicon glyphicon-align-justify" title="Действия"></button>
                                                            <ul class="dropdown-menu">
                                                                <li>
                                                                    <?= Html::button('Тип проблем',     [ 'value' =>Url::to(['adm/problem'] ), 'class' => 'btn btn-info btn-sm modalButton dropdownButton', ])?>
                                                                </li>
                                                                <li>
                                                                    <?= Html::button('Подразделения',   [ 'value' =>Url::to(['adm/podr'] ), 'class' => 'btn btn-info btn-sm modalButton dropdownButton', ])?>
                                                                </li>
                                                                <li>
                                                                    <?= Html::button('Пользователи',    [ 'value' =>Url::to(['adm/login'] ), 'class' => 'btn btn-info btn-sm modalButton dropdownButton', ])?>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                        <?php } ?>
                                                    <?php Pjax::begin(['id' =>'call', 'enablePushState' => false, 'options' => ['style' => 'display:inline-block']]); ?>
                                                    <?= Html::a('<span class="btn btn-info btn-sm glyphicon glyphicon-phone-alt" title="Справочная"></span>',['site/call']) ?>
                                                    <?php Pjax::end(); ?>
                                                    <?= Html::a('<span class="btn btn-info btn-sm glyphicon glyphicon-plus" title="Добавить новую заявку"></span>', ['index', 'app' => 1]) ?>
                                                <?php } ?>
                                            </div>

                                                <?php if($model->isNewRecord){ ?>
                                                    <input class="btn btn-primary btn-sm " type="button" value="DV"  onClick="show('htb1')">
                                                <?php }else{ ?>

                                                <?php $serach = isset($_GET['search']) ? $_GET['search'] : null ?>
                                                    <?php  if($model->status == 1){ ?>
                                                        <?= Html::a('<span class="btn btn-primary btn-sm glyphicon glyphicon-fire disabled"></span>',    ['index' , 'id'=>$_GET['id'], 'search' => $serach], ['title' => 'В работу'] )?>
                                                    <?php }else{ ?>
                                                        <?= Html::a('<span class="btn btn-primary btn-sm glyphicon glyphicon-fire"></span>',    ['status' , 'id'=>$_GET['id'], 'status'=> 1, 'search' => $serach], ['title' => 'В работу'])?>
                                                    <?php } ?>
                                                    <?php  if($model->status == 2){ ?>
                                                        <?= Html::a('<span class="btn btn-primary btn-sm glyphicon glyphicon-question-sign disabled"></span>',  ['index' , 'id'=>$_GET['id'], 'search' =>$serach], ['title' => 'В ожидании'])?>
                                                    <?php }else{ ?>
                                                        <?= Html::a('<span class="btn btn-primary btn-sm glyphicon glyphicon-question-sign "></span>',  ['status' , 'id'=>$_GET['id'], 'status'=> 2, 'search' =>$serach], ['title' => 'В ожидании'])?>
                                                    <?php } ?>
                                                    <?php  if(AppComment::appComment($_GET['id'])) { ?>
                                                        <?php if($model->status == 3){ ?>
                                                                <?= Html::a('<span class="btn btn-primary btn-sm glyphicon glyphicon-remove-sign disabled"></span>',    ['index' , 'id'=>$_GET['id'], 'search' =>$serach], ['title' => 'Закрыть'] )?>
                                                            <?php }else{ ?>
                                                                <?= Html::a('<span class="btn btn-primary btn-sm glyphicon glyphicon-remove-sign"></span>',    ['status' , 'id'=>$_GET['id'], 'status'=> 3, 'search' =>$serach], ['title' => 'Закрыть'])?>
                                                        <?php } ?>
                                                <?php } ?>
                                                <?php  if($_SESSION['User']['role'] == 110) { ?>
                                                        <?= Html::a('<span class="btn btn-danger btn-sm glyphicon glyphicon-trash"></span>',    ['delete' , 'id'=>$_GET['id'], 'search' =>$serach], ['title' => 'Удалить заявку и все что с ним связано' , 'alert' => 'Удалить?'])?>
                                                <?php  }?>
                                            <?php } ?>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                            <?php $form = ActiveForm::begin();
                                if(!isset($_GET['app'])){
                                    $model->content =   isset($model->appContent->content) ? $model->appContent->content : null;
                                    $model->fio =       isset($model->appContent->fio->name) ? $model->appContent->fio->name : null;
                                    $model->ip =        isset($model->appContent->ip) ? $model->appContent->ip : null;
                                    $model->phone =     isset($model->appContent->phone) ? $model->appContent->phone : null;
                                    $model->type =      isset($model->appContent->dv) ? $model->appContent->dv : null;
                                    $model->note =      isset($model->appContent->note) ? $model->appContent->note : null;
                                }else{
                                    $model->ip =  '10.224.';
                                    $model->id_priority =  2;
                                } ?>
                            <div class="row">
                                <div class="col-lg-6">
                                    <label>
                                        <?= Html::button('ФИО', [ 'value' =>Url::to(['logs'] ), 'id' => 'modalFio', 'class' => 'btn btn-link btn-sm input_modal'])?>
                                    </label>
                                    <?php
                                    echo $form->field($model, 'fio')->widget(TypeaheadBasic::classname(), [
                                        'data' => ArrayHelper::map(Fio::find()->all(),'id', 'name'),
                                        'pluginOptions' => ['highlight'=>true, 'minLength' => 0],
                                        'options' => ['class'=>'form-control input-sm input', 'tabindex' => '1', 'onkeydown'=>"keyUp(event)", 'onkeyup'=>"keyUp(event)"],
                                        'dataset' => [
                                            'limit' => 20,
                                        ],
                                        'scrollable' => true,
                                    ])->label(false);
                                    ?>
                                </div>
                                <div class="col-lg-3">
                                    <label>
                                        <?= Html::button('Ip', [ 'value' =>Url::to(['logs'] ), 'id' => 'modalIp', 'class' => 'btn btn-link btn-sm  input_modal'])?>
                                    </label>
                                    <?= $form->field($model, 'ip')->textInput(['maxlength' => true, 'tabindex' => '2', 'class'=>'form-control input-sm input', 'onkeydown'=>"keyUp(event)", 'onkeyup'=>"keyUp(event)"])->label(false) ?>
                                </div>
                                <div class="col-lg-3">
                                    <label>
                                        <?= Html::button('Тел', [ 'value' =>Url::to(['phone'] ), 'id' => 'modalPhone', 'class' => 'btn btn-link btn-sm  input_modal'])?>
                                    </label>
                                    <?= $form->field($model, 'phone')->textInput(['maxlength' => true, 'tabindex' => '3', 'class'=>'form-control input-sm input', 'onkeydown'=>"keyUp(event)", 'onkeyup'=>"keyUp(event)",])->label(false) ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <?= $form->field($model, 'id_podr')->dropDownList(ArrayHelper::map(Podr::find()->orderBy(['name' => SORT_ASC])->where(['visible'=>1])->all(),'id', 'name'), ['tabindex' => '4', 'class'=>'form-control input-sm input ', 'disabled' => $disabled, 'prompt'=>'- Выбрать подразделение -']) ?>
                                </div>
                                <div class="col-lg-6">
                                    <?= $form->field($model, 'id_priority')->dropDownList(ArrayHelper::map(Priority::find()->all(),'id', 'name'), ['tabindex' => '5', 'options' =>[ '2' => ['Selected' => true]], 'class'=>'form-control input-sm input', 'disabled' => $disabled]) ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <?= $form->field($model, 'id_problem')->dropDownList(ArrayHelper::map(Problem::find()->orderBy(['name' => SORT_ASC])->where(['visible'=>1])->all(),'id', 'name'), ['tabindex' => '6','class'=>'form-control input-sm input', 'disabled' => $disabled, 'prompt'=>'- Выбрать проблему -']) ?>
                                </div>
                                <div class="col-lg-6">
                                    <?= $form->field($model, 'id_user')->dropDownList(ArrayHelper::map(Login::find()->orderBy(['username' => SORT_ASC])->where(['visible'=>0])->all(),'id', 'username'), ['tabindex' => '7','class'=>'form-control input-sm input', 'disabled' => $disabled, 'prompt'=>'- Выбрать исполнителя -']) ?>
                                </div>

                            </div>
                            <?php if(isset($_GET['app'])) { $model->type = null; $style=''; } else {$style = $model->type ? 'block' : '';} ?>
                            <div class="row" id="htb1" style="display: <?= $style ?>;">
                                <div class="col-lg-12" >
                                    <?= $form->field($model, 'type')->textInput(['tabindex' => '10', 'maxlength' => true, 'class'=>'form-control input-sm input']) ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <?= $form->field($model, 'note')->textInput(['tabindex' => '8', 'maxlength' => true, 'class'=>'form-control input-sm input']); ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <?= $form->field($model, 'content')->textarea(['tabindex' => '9', 'rows' => 6, 'class'=>'form-control input-sm input' , 'style' => 'max-width: 610px']); ?>
                                </div>
                            </div>
                            <?php if(($_SESSION['User']['role'] == '105') or ($_SESSION['User']['role'] == '110')) { ?>
                                <div class="form-group col-lg-1 col-lg-offset-5">
                                    <?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Обновить', ['class' => $model->isNewRecord  ? 'btn btn-sm btn-success input' : 'btn btn-sm btn-primary input']) ?>
                                </div>
                            <?php } ?>
                            <?php ActiveForm::end(); ?>
                        </div>
                    </div>
                <!--        Общая информация по заявке -->

                <br>

                <!--        Комментарии-->
                <?php if($_GET){ if(($_GET['id']) and !isset($_GET['app'])){ ?>
                        <div class="col-lg-12 bg-sitdesk-block block-comment">
<!--                            --><?php //Pjax::begin(['id' =>'app1', 'enablePushState' => false]); ?>
                            <?php LoadingOverlayPjax::begin([
                                'color'=> 'rgba(228, 228, 228, 0.2)',
                                'fontawesome' => 'fa fa-spinner fa-spin',
                                'id' =>'app1',
                                'size' => '15px',
                                'enablePushState' => false
                            ]); ?>
                            <?php $form = ActiveForm::begin(['action' => ['/admin/comment/create', 'id'=> $_GET['id']],'options' => ['data-pjax' => true]]); ?>
                            <div class="row" style="padding-top: 10px">
                                <div class="col-md-10" >
                                    <?= $form->field($model, 'comment')->textarea(['rows' => 2, 'class'=>'form-control input-sm input', 'style' => 'max-width: 500px; min-width: 300px;', 'onkeydown'=>"keyDown(event)", 'onkeyup'=>"keyUp(event)", 'placeholder'=>'Коментарии'])->label(false) ?>
                                </div>
                                <div>
                                    <div class="btn-group">
                                        <?= Html::submitButton('<span class="glyphicon glyphicon-plus"></span>', ['id' => 'btnComment','class' => 'btn btn-success btn-sm ']) ?>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-default btn-sm  dropdown-toggle" data-toggle="dropdown">
                                                <span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <?php foreach(\app\models\Sitdesk::CommList($_SESSION['User']['comment_list']) as $item){ ?>
                                                    <li><a href="<?= Url::to(['/admin/comment/create','id'=> $_GET['id'], 'comment'=> $item ]) ?>"><?= $item ?></a></li>
                                                <?php } ?>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div  class="col-md-12">
                                    <table class="table table-hover table-condensed" style="font-size: 10pt">
                                        <?php foreach ($comment as $item){ ?>
                                            <tr>
                                                <?php if($_SESSION['User']['settings_comment'] == 0){ ?>
                                                    <td class="comment-main">
                                                        <small style=" max-width: 200px;">
                                                            <?= MyDate::getDate($item->date) ?>. <strong><?= \app\models\Sitdesk::fio($item->user->username) ?>: </strong>
                                                        </small>
                                                        <br>
                                                        <?= nl2br(Html::encode($item->comments->name)) ?>
                                                    </td>
                                                <?php }else{ ?>
                                                    <td>
                                                        <small style="">
                                                            <?= MyDate::getDate($item->date) ?>.<strong><?= $item->user->login ?></strong>
                                                        </small>
                                                        <br>
                                                        <?= nl2br(Html::encode($item->comments->name)) ?>
                                                    </td>
                                                <?php } ?>
                                            </tr>
                                        <?php } ?>
                                    </table>
                                </div>
                            </div>
                            <?php ActiveForm::end(); ?>
                            <?php LoadingOverlayPjax::end(); ?>
<!--                            --><?php //Pjax::end(); ?>
                        </div>
                <?php } } ?>
                <!--        Комментарии-->

                <br>
            </div>

        <div style="display: inline-block;">
            <!--            Заявки коллег   -->
            <?php if(($_SESSION['User']['assist'] == 1) and ($_SESSION['User']['role'] <> '105') and (\app\models\Sitdesk::countAssist() > 0)){ ?>
                <div class="block-assist bg-sitdesk-block">
                    <?= AssistsWidget::widget() ?>
                </div>
            <?php } ?>
            <!--            Заявки коллег   -->

            <!--            Напоминания-->
            <div class="block-recal bg-sitdesk-block" >
                <?= RecalWidget::widget() ?>
            </div>
            <!--            Напоминания-->
        </div>


        </div>
    </div>


    <?php
        unset($model);
    ?>

<?php } else {
    return Yii::$app->response->redirect(['site/login']);
} ?>


<div class="body-content">
    <p>
        <?php
        Modal::begin([
            'options' => [
                'id' => 'modal',
                'tabindex' => false, // important for Select2 to work properly
            ],
            'size' => 'modal-lg',
        ]);
        echo "<div id='modalContent'>  </div>";
        Modal::end();
        ?>
    </p>
</div>


