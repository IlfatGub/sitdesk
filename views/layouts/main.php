<?php
//session_start();

use yii\helpers\Html;
use app\assets\AppAsset;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use app\components\AppWidget;
use kartik\typeahead\TypeaheadBasic;
use yii\helpers\ArrayHelper;
use yii\widgets\Pjax;
use app\modules\admin\models\Login;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<!--<meta http-equiv="X-UA-Compatible" content="IE=edge" />-->
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=9; IE=8; IE=7; IE=EDGE" />
    <link rel="shortcut icon" href="<?= Yii::$app->request->baseUrl ?>/image/icon.jpeg" type="image/x-icon" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title> <?= Yii::$app->name ?> </title>
    <?php $this->head() ?>
    <?php  mb_internal_encoding('UTF-8');  ?>
</head>

<body>
<?php $this->beginBody() ?>


<div id="wrapper">

    <!-- Sidebar -->
    <div id="sidebar-wrapper">

        <ul class="sidebar-nav">
            <?php if($_SESSION['User']['id']){ ?>
            <div class="col-lg-12 text-center" style="display: inline-block; background: #E4E4E4; font-size: 14pt; margin-bottom: 5px">
                <li class="dropdown" style="display: inline-block; background: #E4E4E4; font-size: 14pt; margin-bottom: 5px">
                    <a id="drop1" href="#" role="button" class="dropdown-toggle" data-toggle="dropdown"><?=$_SESSION['User']['username']?><b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li role="presentation"><a  href="<?= Url::to(['index', 'search' => 'Все']) ?>" >Все</a></li>
                        <li role="presentation" class="divider"></li>
                        <?php foreach (Login::find()->where(['visible' => 0])->select(['username'])->all() as $item) { ?>
                            <li role="presentation"><a  href="<?= Url::to(['index', 'search' => $item->username]) ?>" ><?= $item->username ?></a></li>
                        <?php } ?>
                    </ul>
                </li>
            </div>

            <li style="margin: 10px">
                <div style="display: inline-block;"> <?= Html::a('<span title="Настройки" class="btn btn-default glyphicon glyphicon-cog"    ></span>', ['settings']) ?> </div>
                <div style="display: inline-block;"> <?= Html::a('<span title="Домой" class="btn btn-default glyphicon glyphicon-home" ></span>', ['index']) ?> </div>
                <div style="display: inline-block;"> <?= Html::a('<span title="Выйти" class="btn btn-default glyphicon glyphicon-log-out"></span>', ['/site/logoutuser']) ?> </div>
                <?= Html::button('', [ 'value' =>Url::to(['site/stat'] ), 'class' => 'btn btn-default glyphicon glyphicon-time modalButton', 'title'=>'Статистика', ])?>
                <?php if ($_SESSION['User']['role'] == '105') { ?>
                    <?= Html::button('', [ 'value' =>Url::to(['site/close'] ), 'class' => 'btn btn-info glyphicon glyphicon-envelope modalButton', 'title'=>'Закрытые заявки'])?>
                <?php } ?>
            </li>

            <li>
                <?php  ActiveForm::begin([ 'action' => ['/site/index'], 'method' => 'get'] ); ?>
                <div class="input-group input-group-sm" style="margin: 10px">

                    <?= Html::input('search', 'search', '', [
                        'class' =>'form-control search-width input-sm input',
                        'id' => 'search',
                        ]) ?>
                    <span class="input-group-btn" style="width: 100%;">
                            <?=  Html::submitButton( '<span class="glyphicon glyphicon-search"></span>', [ 'class' => 'btn btn-primary' ]); ?>
                    </span>
                </div>
                <?php ActiveForm::end(); ?>
            </li>
            <?php } ?>

            <?php if(isset($_SESSION['User']['id'])){ ?>
                <?= AppWidget::widget(['search' => isset($_GET['search']) ? $_GET['search'] : null]) ?>
            <?php } ?>
        </ul>
    </div>

    <div id="page-content-wrapper" class="col-lg-12" style="padding: 0; margin: 15px 0 0 0;">
        <div class="container-fluid">
            <div class="row" >
                <div class="col-lg-12" style="margin: 0; padding: 0">
                    <?= $content ?>
                </div>
            </div>
        </div>
    </div>

</div>

<?php $this->endBody() ?>
</body>

<script>
    $("#menu-toggle").click(function(e) {
        e.preventDefault();
        $("#wrapper").toggleClass("toggled");
    });
</script>
</html>
<?php $this->endPage() ?>
