<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\file\FileInput;
use kartik\widgets\DatePicker;
use yii\widgets\Pjax;
use timurmelnikov\widgets\LoadingOverlayPjax;
?>


<div class="org-form">
    <?php LoadingOverlayPjax::begin([
        'color'=> 'rgba(217, 237, 247, 0.2)',
        'fontawesome' => 'fa fa-spinner fa-spin',
        'id' =>'problem',
//        'size' => '15px',
        'enablePushState' => false
    ]); ?>
    <?php $form = ActiveForm::begin(['action' => ['/adm/problem'],'options' => ['data-pjax' => true]]); ?>

    <div class="row" style=" font-size: 10pt">
        <div class="col-md-8 col-md-offset-2" >
            <div class="row">
                <div class="col-md-10">
                    <?= $form->field($model, 'name')->textInput()->label(false) ?>
                </div>
                <div class="col-md-2">
                    <label> &nbsp </label>
                    <?= Html::submitButton('Добавить', ['class' => 'btn btn-success']) ?>
                </div>
            </div>

            <table class=" table table-border table-hover table-condensed">
                <tr class="bg-info">
                    <td>№</td>
                    <td>Наименование</td>
                    <td></td>
                </tr>
                <?php  foreach ($list as $item){ ?>
                    <tr>
                        <td><?= $item->id ?></td>
                        <td><?= $item->name ?></td>

                        <td><?= Html::a('<span class="glyphicon glyphicon-remove "></span>',['/adm/problem', 'delete' => $item->id, ],
                                ['data' => ['confirm' => 'Удалить?', 'method' => 'post', ]]);
                            ?>
                        </td>
                    </tr>
                <?php } ?>
            </table>
            <?php ActiveForm::end(); ?>
            <?php LoadingOverlayPjax::end(); ?>
        </div>
    </div>


</div>
