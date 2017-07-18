<?php
namespace app\modules\admin\models;

date_default_timezone_set('Asia/Yekaterinburg');

class MyDate extends \yii\db\ActiveRecord
{

    public $date;

    public function rules()
    {
        return[
            ['date', 'date']
        ];
    }

    /*
     * date - дата которую необходимо перевети
     * перевод в юникс дату
     */
    public function getTimestamp($date)
    {
        $date = new \DateTime($date);
        return $date->getTimestamp();
    }

    /*
     * dates - дата которую необходимо перевети
     * перевод в дату в обычном виде
     */
    public function getDate($dates, $format=null)
    {
        $date = new \DateTime();
        $date->setTimestamp($dates);
        if(isset($format)){
            return $date->format('H:i | Y-m-d');
        }else{
            $datetime1 = new \DateTime(date('Y-m-d'));
            $datetime2 = new \DateTime($date->format('Y-m-d'));
            $interval = $datetime1->diff($datetime2);
            if($interval->format('%a') == 0){
                return $date->format('Сегодня H:i');
            }elseif ($interval->format('%a') == 1){
                return $date->format('Вчера H:i');
            }
            return $date->format('H:i | d-m-Y');
        }
    }

    public function getWeek($w){
        switch ($w) {
            case 0: echo "Вс"; break;
            case 1: echo "Пн"; break;
            case 2: echo "Вт"; break;
            case 3: echo "Ср"; break;
            case 4: echo "Чт"; break;
            case 5: echo "Пт"; break;
            case 6: echo "Сб"; break;
        }
    }
}
