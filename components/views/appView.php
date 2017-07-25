
<?php
use yii\helpers\Url;
use app\modules\admin\models\MyDate;
use yii\helpers\Html;
?>
<?//=$iFio.$iIp?>
<?php $review = isset($iReview) ?  'help_main_border_active' : 'help_main_border'; ?>
<?php $icon = isset($iType) ?  'glyphicon glyphicon-list-alt' : 'glyphicon glyphicon-earphone'; ?>
<?php $danger = $iPriority == 3 ? 'bg-danger' : '1'; ?>
<?php if(isset($_GET['id'])){if($_GET['id'] == $iId){ $style = "background: #B1D6F0; border-right: 0px solid white"; } else { $style = ''; }}?>
<?php if( $_SESSION['User']['menu'] == 0){
    $content = 'ФИО: '.$iFio."<br>";
    $content .= 'Ip: '.$iIp."<br>";
    $content .= $iContent;
}else{
    $content = $iPodr. " - ". $iProblem."<br><hr style='margin: 0; padding: 0'>";
    $content .= 'ФИО: '.$iFio."<br>";
    $content .= 'Ip: '.$iIp."<br>";
    $content .= $iContent;
}  ?>

<?php if($_SESSION['User']['settings_menu'] == 1){ ?>
    <style>

        #wrapper {
            padding-left: 280px;
        }

        #sidebar-wrapper {
            width: 280px !important;
        }
        .sidebar-nav{
            width: 280px !important;
        }
        .search-width{
            width: 210px !important;
        }

    </style>
    <li id="hs_<?= $iId ?>"  class="sidebar-brand" style=" font-size: 10pt; border-bottom: 1px dashed silver" data-toggle="tooltip" data-placement="right" title="<?=nl2br(Html::encode($content))?>">
        <div class="<?= $review ?>" style="margin-left: 10px; <?= isset($style) ? $style : '' ?>" >
            <a href="<?= Url::to(['/index', 'id'=>$iId, 'search' =>isset($_GET['search']) ? $_GET['search'] : null, '#' => 'hs_'.$iId])?>">
                <div style="display: inline-block">
                    <div class="<?= $danger ?>"  style="width: 220px">
                        <strong><span class="<?= $icon.' '.$danger ?>"></span> <?= $iId ?></strong>. <small style="color: slategray">  <?=  \app\models\Sitdesk::fio($iUsername, 1) ?></small>
                        <div style="float: right;"><small><?= MyDate::getDate($iDate) ?></small></div>
                    </div>
                    <?php if( $_SESSION['User']['menu'] == 0){?>
                        <div >
                            <small> <?= $iPodr ?> - <?= $iProblem ?></small>
                        </div>
                    <?php } ?>
                    <?php if( $_SESSION['User']['menu'] == 2){?>
                        <div >
                            <small> <?= $iPodr ?> - <?= $iProblem ?></small>
                        </div>
                        <div>
                            <small> <?= $iFio ?> <?= $iIp == '' ? '' : ' - '.$iIp ?></small>
                        </div>
                    <?php } ?>
                </div>
            </a>
        </div>
    </li>
<?php }else{ ?>
    <li id="hs_<?= $iId ?>"  class="sidebar-brand" style=" font-size: 10pt; border-bottom: 1px dashed silver" data-toggle="tooltip" data-placement="right" title="<?=nl2br(Html::encode($content))?>">
        <div class="<?= $review ?>" style="margin-left: 10px; <?= isset($style) ? $style : '' ?>" >
            <a href="<?= Url::to(['/index', 'id'=>$iId, 'search' =>isset($_GET['search']) ? $_GET['search'] : null, '#' => 'hs_'.$iId])?>">
                <div style="display: inline-block">
                    <div class="<?= $danger ?>"  style="width: 300px">
    <!--                    <strong><span class="--><?//= $icon.' '.$danger ?><!--"></span> --><?//= $iPriority ?><!-- Заявка --><?//= $iId ?><!--</strong>. <small style="color: slategray">--><?//= $iLogin ?><!--@snhrs.ru</small>-->
                        <strong><span class="<?= $icon.' '.$danger ?>"></span> Заявка <?= $iId ?></strong>. <small style="color: slategray">  <?=  \app\models\Sitdesk::fio($iUsername) ?></small>
                        <div style="float: right;"><small><?= MyDate::getDate($iDate) ?></small></div>
                    </div>
                    <?php if( $_SESSION['User']['menu'] == 0){?>
                    <div >
                        <small> <?= $iPodr ?> - <?= $iProblem ?></small>
                    </div>
                    <?php } ?>
                    <?php if( $_SESSION['User']['menu'] == 2){?>
                        <div >
                            <small> <?= $iPodr ?> - <?= $iProblem ?></small>
                        </div>
                        <div>
                            <small> <?= $iFio ?> <?= $iIp == '' ? '' : ' - '.$iIp ?></small>
                        </div>
                    <?php } ?>
                </div>
            </a>
        </div>
    </li>
<?php } ?>

