
<?php
use yii\helpers\Html;
use yii\helpers\Url;
use app\models\Sitdesk;
use app\modules\admin\models\Login;
use app\components\AssistsAppWidget;
?>



<?php  foreach ($model as $item) { ?>
    <?php if(Login::validateLoginApp($item->id)){ ?>

        <div class="panel panel-info" style="width: 390px; display: inline-block; margin: 5px 0 ">
            <div class="panel-heading" style="display: inline-block; padding: 8px 12px; height: 100%">
                <div class="panel-title"> <?= $item->login ?> </div>
            </div>
<!--            <div class="panel-body" style="display: inline-block;  padding: 0 !important; ">-->
                <?= AssistsAppWidget::widget(['id' => $item->id]) ?>
<!--            </div>-->
        </div>

    <?php } ?>
<?php } ?>

