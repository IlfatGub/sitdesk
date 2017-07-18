<?php

namespace  app\components;
use app\modules\admin\models\App;
use app\modules\admin\models\Recal;
use yii\base\Widget;

class RecalWidget extends Widget{

    public $id;

    public function init () {
        parent::init();
        if($this->id === null){
            $this->id = 0;
        }
    }

    public function run()
    {

        $model = new App();

        $recal = Recal::find()
            ->where(['id_user' => $_SESSION['User']['id']])
            ->orderBy(['date' => SORT_DESC])
            ->select(['text', 'id'])
            ->all();

        return $this->render('recal',
            [
                'model' => $model,
                'recal' => $recal,
            ]);
    }
}