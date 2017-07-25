<?php


use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\models\LoginForm;

//$model = new LoginForm();
?>
<div class="site-login">

    <?php $form = ActiveForm::begin([
        'id' => 'login-form',
        'layout' => 'horizontal',
        'enableAjaxValidation' => true,
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
            'labelOptions' => ['class' => 'col-lg-1 control-label'],
        ],
    ]); ?>
        <div style="margin-bottom:15px">
            <?= $form->field($model, 'username')->textInput(['autofocus' => true])->label('Логин') ?>
        </div>
        <div style="margin-bottom:10px">
            <?= $form->field($model, 'password')->passwordInput()->label('Пароль') ?>
        </div>
        <div style="margin-bottom:10px">
            <?= $form->field($model, 'rememberMe')->checkbox([
                'template' => "<div class=\"col-lg-offset-1 col-lg-3\">{input} {label}</div>\n<div class=\"col-lg-8\">{error}</div>",
            ]) ?>
        </div>

        <div class="form-group">
            <div class="col-lg-offset-1 col-lg-11">
                <?= Html::submitButton('Войти', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
            </div>
        </div>

    <?php ActiveForm::end(); ?>

    <?= $_SERVER['REMOTE_ADDR'] ?>
</div>

