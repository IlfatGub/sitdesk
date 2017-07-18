<?php

use yii\widgets\Pjax;
use yii\helpers\Html;
use kartik\date\DatePicker;
use yii\helpers\Url;

?>

<?php Pjax::begin(['id' => 'stat', 'enablePushState' => false]); ?>
<?php $count = 0; $activ = 0; $pending = 0; $close = 0; ?>

<div class="row">

    <div class="col-md-6">
        <?= Html::beginForm(['site/stat'], 'post', ['data-pjax' => '', 'class' => 'form-inline']); ?>
        <?=
        DatePicker::widget([
            'name' => 'date_to',
            'value' => $date_to,
            'type' => DatePicker::TYPE_RANGE,
            'name2' => 'date_do',
            'value2' => $date_do,
            'pluginOptions' => [
                'autoclose'=>true,
                'format' => 'yyyy-mm-dd',
                'class' => 'col-lg-6',
            ],
            'pluginEvents' => [
                "changeDate" => "function(e) {  $('#refreshButton').click();  }",
            ],
        ]);
        ?>
        <?= Html::submitButton('Выполнить ', ['class' => 'btn btn-primary display-none', 'id' => 'refreshButton', 'name' => 'hash-button']) ?>
        <?= Html::endForm() ?>
    </div>
    <div class="col-lg-12">
        <table class="table table-bordered table-condensed  table-hover" style="margin-top: 25px" >
            <tr class="bg-primary">
                <td> ФИО </td>
                <td class="text-center" > Всего </td>
                <td class="text-center" > В работе </td>
                <td class="text-center" > Ожидающие </td>
                <td class="text-center" > Закрыто </td>
            </tr>
            <?php foreach ($user as $item){ ?>
                <?php $class = $item['username'] == $_SESSION['User']['username'] ? 'bg-warning' : ''; ?>
                <tr class="<?=$class?>" >
                    <td style="padding: 2px"><?= $item['username'] ?></td>
                    <td style="padding: 2px; width: 100px;" class="text-center"><?= $item['count']   > 0 ? '<strong>'.$item['count'].'</strong>' : $item['count'] ?></td>
                    <td style="padding: 2px; width: 100px;" class="text-center"><?= $item['activ']   > 0 ? '<strong>'.$item['activ'].'</strong>' : $item['activ'] ?></td>
                    <td style="padding: 2px; width: 100px;" class="text-center"><?= $item['pending'] > 0 ? '<strong>'.$item['pending'].'</strong>' : $item['pending'] ?> </td>
                    <td style="padding: 2px; width: 100px;" class="text-center"><?= $item['close']   > 0 ? '<strong>'.$item['close'].'</strong>' : $item['close'] ?> </td>
                    <?php $count = $count + $item['count'] ?>
                    <?php $activ = $activ + $item['activ'] ?>
                    <?php $pending = $pending + $item['pending'] ?>
                    <?php $close = $close + $item['close'] ?>
                </tr>
            <?php } ?>
            <tr style="padding: 2px" class="bg-danger">
                <td><strong>Всего           </strong></td>
                <td class="text-center"><strong><?= $count ?>   </strong></td>
                <td class="text-center"><strong><?= $activ ?>   </strong></td>
                <td class="text-center"><strong><?= $pending ?> </strong></td>
                <td class="text-center"><strong><?= $close ?>   </strong></td>
            </tr>

        </table>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <table class="table table-bordered table-condensed  table-hover" style="margin-top: 25px; width: 400px">
            <tr class="bg-info text-center">
                <td class="col-lg-8">Диспетчер(Справочная)</td>
                <td class="col-lg-4"><?= $countCall ?></td>
            </tr>
        </table>
    </div>
</div>

<?php Pjax::end(); ?>








