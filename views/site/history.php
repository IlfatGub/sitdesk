
<?php
Use app\modules\admin\models\MyDate;
Use app\modules\admin\models\Login;
?>


<!--        История заявки -->
<div class="row" style="margin: 10px">
    <div class="col-lg-12" style="border: 1px dashed silver; height: 100%; background: #F4F4F4;">
        <div class="row">
            <div  class="col-md-11">
                <table class="table table-hover table-condensed" style="font-size: 10pt">
                    <?php foreach ($history as $item){ ?>
                        <tr>
                            <td class="col-md-3"> <small><?= MyDate::getDate($item->date) ?>. <strong><?= $item->user->login ?></strong></small></td>
                            <td> <?= $item->history->name ?> </td>
                            <td> <?= $item->usercomment->username ?> </td>
                        </tr>
                    <?php } ?>
                </table>
            </div>
        </div>
    </div>
</div>
<!--    История  заявки-->
