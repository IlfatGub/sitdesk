<?php
/**
 * Created by PhpStorm.
 * User: 01gig
 * Date: 20.06.2017
 * Time: 14:33
 */

use app\models\Sitdesk;
use app\modules\admin\models\Comment;
//use Yii;



//exec( "c:/windows/system32/calc.exe" );

//"C:\Program Files (x86)\SolarWinds\DameWare Remote Support\DWRCC.exe" -u:admin@zsmik.com -p:Zamazon@;


echo Yii::$app->session['User']['login'];
echo Yii::$app->session['User']['id']              ;
echo Yii::$app->session['User']['login']           ;
echo Yii::$app->session['User']['username']        ;
echo Yii::$app->session['User']['role']            ;
echo Yii::$app->session['User']['close']           ;
echo Yii::$app->session['User']['count']           ;
echo Yii::$app->session['User']['menu']            ;
echo Yii::$app->session['User']['settings_comment'];
echo Yii::$app->session['User']['assist']          ;
echo Yii::$app->session['User']['visible']         ;
echo Yii::$app->session['User']['depart']          ;
echo Yii::$app->session['User']['settings_menu']   ;
echo Yii::$app->session['User']['comment_list']    ;
?>


