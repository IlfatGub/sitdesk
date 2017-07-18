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
AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <link rel="shortcut icon" href="<?= Yii::$app->request->baseUrl ?>/image/icon.jpeg" type="image/x-icon" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title> <?= Yii::$app->name ?> </title>
    <?php $this->head() ?>
    <?php  mb_internal_encoding('UTF-8');  ?>
</head>

<body>
<div id="page-preloader"><span class="spinner"></span></div>

<?php $this->beginBody() ?>


<div id="wrapper">
    <?= $content ?>
</div>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>


