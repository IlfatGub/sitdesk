
<?php
use timurmelnikov\widgets\LoadingOverlayPjax;
use yii\widgets\ActiveForm;
use yii\helpers\Html;

?>

<?php LoadingOverlayPjax::begin([
//                    'color'=> 'rgba(217, 237, 247, 0.2)',
    'fontawesome' => 'fa fa-spinner fa-spin',
    'id' =>'recal',
    'size' => '15px',
    'enablePushState' => false
]); ?>
<?php $form = ActiveForm::begin(['action' => ['/admin/recal/create', 'id' => $_GET['id']],'options' => ['data-pjax' => true]]); ?>
<div class="row">
    <div class="col-md-12" >
        <?= $form->field($model, 'text')->textarea(['rows' => 1, 'class'=>'form-control input-sm input', 'style' => 'min-width: 200px; ', 'onkeydown'=>"keyDownRecal(event)", 'onkeyup'=>"keyUpRecal(event)", 'placeholder'=>'Напоминания'])->label(false) ?>
    </div>
    <div class="col-md-0" style="visibility: hidden; position: fixed">
        <?= Html::submitButton('+', ['id' => 'btnRecal','class' => 'btn btn-success btn-sm input col-sm-12']) ?>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <table class="table table-hover table-condensed" style="font-size: 10pt">
            <?php foreach ($recal as $item){ ?>
                <div class="bs-example">
                    <div class="alert alert-info fade in" style="padding: 5px; margin-bottom: 0; margin-top: 5px; font-size: 10pt">
                        <!--                                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">-->
                        <?= Html::a('×',
                            [
                                '/admin/recal/create',
                                'id' => $_GET['id'],
                                'del' => $item->id,
                            ],
                            [
                                'data' => [
//                                                            'confirm' => 'Вы хотите удалить?',
                                    'method' => 'post',
                                ],
                            ]
                        );
                        ?>
                        <?= nl2br(Html::encode($item->text)) ?>
                    </div>
                </div>
            <?php } ?>
        </table>
    </div>
</div>
<?php ActiveForm::end(); ?>
<?php LoadingOverlayPjax::end(); ?>
