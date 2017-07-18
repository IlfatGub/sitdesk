<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\file\FileInput;
use kartik\widgets\DatePicker;
use yii\widgets\Pjax;
use timurmelnikov\widgets\LoadingOverlayPjax;
use yii\helpers\Url;
$i=0;
?>


<div class="org-form">
    <?php LoadingOverlayPjax::begin([
        'color'=> 'rgba(217, 237, 247, 0.2)',
        'fontawesome' => 'fa fa-spinner fa-spin',
        'id' =>'login',
        'enablePushState' => false
    ]); ?>

    <div class="row" >
        <div class="col-md-12" >

            <?php $form = ActiveForm::begin(['action' => ['/adm/createlogin'],'options' => ['data-pjax' => true]]); ?>
            <div class="row">
                <div class="col-md-10">
                    <?= $form->field($model, 'login')->textInput()->label(false) ?>
                </div>
                <div class="col-md-2">
                    <label> &nbsp </label>
                    <?= Html::submitButton('Добавить', ['class' => 'btn btn-success']) ?>
                </div>
            </div>

            <table class=" table table-border table-hover">
                <tr class="bg-primary">
                    <td>#</td>
                    <td>Login</td>
                    <td>ФИО</td>
                    <td>Должность</td>
                    <td>Доступ</td>
                    <td></td>
                </tr>
                <?php  foreach ($list as $item){ ?>
                    <?php  $class =  $item->visible == 1 ? 'background: #F5F5F5' : ''  ?>
                        <?php  $disabled = $item->visible == 0 ? false : true ?>
                        <tr style="<?=$class?>">
                            <td><?= ++$i; ?></td>
                            <td style="width: 100px">
                                <?= Html::input('username', 'string', $item->login,
                                    ['disabled' => $disabled,  'class' => 'form-control portInput input-sm',
                                        'onchange' => '$.post(" '.Url::toRoute(['login']).'?id='.$item->id.'&type=1&text='.'"+$(this).val());'
                                    ])
                                ?>
                            </td>
                            <td>
                                <?= Html::input('username', 'string', $item->username,
                                    ['disabled' => $disabled, 'class' => 'form-control portInput input-sm',
                                        'onchange' => '$.post(" '.Url::toRoute(['login']).'?id='.$item->id.'&type=2&text='.'"+$(this).val());'
                                    ])
                                ?>
                            </td>
                            <td>
                                <?= Html::input('username', 'string', $item->post,
                                    ['disabled' => $disabled, 'class' => 'form-control portInput input-sm',
                                        'onchange' => '$.post(" '.Url::toRoute(['login']).'?id='.$item->id.'&type=3&text='.'"+$(this).val());'
                                    ])
                                ?>
                            </td>
                            <td>
                                <?= Html::dropDownList('role', [$item->role] ,['100' => 'Инженер', '105' => 'Диспетчер', '110' => 'Администратор'],
                                ['disabled' => $disabled, 'class' => 'form-control portInput input-sm',
                                        'onClick' => '$.post(" '.Url::toRoute(['login']).'?id='.$item->id.'&type=4&text='.'"+$(this).val());'
                                    ]) ?>
                            </td>
                            <td>
                                <?= Html::dropDownList('depart', [$item->depart] ,['1' => 'СИТ', '2' => 'СИРИАС', '3' => 'SAP', '4' => 'Связисты'],
                                    ['disabled' => $disabled, 'class' => 'form-control portInput input-sm',
                                        'onClick' => '$.post(" '.Url::toRoute(['login']).'?id='.$item->id.'&type=5&text='.'"+$(this).val());'
                                    ]) ?>
                            </td>
                            <?php  if($item->visible == 0){  ?>
                                <td>
                                    <?= Html::a('<span class="glyphicon glyphicon-remove""></span>',
                                        ['/adm/login', 'id' => $item->id, 'vis' => $item->visible == 0 ? '1' : '0'],
                                        ['data' => ['method' => 'post', ]]);
                                    ?>
                                </td>
                            <?php  }else{  ?>
                                <td>
                                    <?= Html::a('<span class="glyphicon glyphicon-ok "></span>',
                                        ['/adm/login', 'id' => $item->id, 'vis' => $item->visible == 0 ? '1' : '0'],
                                        ['data' => ['method' => 'post', ]]);
                                    ?>
                                </td>
                            <?php  }  ?>
                        </tr>

                <?php } ?>
            </table>
            <?php ActiveForm::end(); ?>
            <?php LoadingOverlayPjax::end(); ?>
        </div>
    </div>


</div>
