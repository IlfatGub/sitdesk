<?php

namespace app\modules\admin\models;

use Yii;

class Comment extends \yii\db\ActiveRecord
{

    public static function tableName()
    {
        return 'comment';
    }

    public function rules()
    {
        return [
            [['name'], 'string'],
        ];
    }


    public function attributeLabels()
    {
        return [
            'name' => 'Коментарий',
        ];
    }

    public function getId($name){
        $count = Comment::find()->where(['name' => $name])->count();
        if($count > 0){
            return Comment::findOne(['name' => $name])->id;
        }else{
            $comment = new Comment();
            $comment->name = $name;
            $comment->save();
            return Comment::findOne(['name' => $name])->id;
        }
    }

    /*
     * Добовляем новый коментарий
     */
    public function commentAdd($id, $comment){
        $model = new AppComment();
        $model->id_app = $id;
        $model->comment = Comment::getId($comment);
        $model->id_user = $_SESSION['User']['id'];
        $model->date = MyDate::getTimestamp(date('Y-m-d H:i:s'));
        $model->save();
    }
}
