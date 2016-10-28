<?php

namespace app\models;

use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

class Actor extends ActiveRecord
{
    public function behaviors(){
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created',
                'updatedAtAttribute' => 'updated',
                'value' => new Expression('NOW()'),
            ],
        ];
    }
    public function getArticles(){
        return $this->hasMany(Article::className(), ['id' => 'article_id'])->viaTable('article_actor', ['actor_id' => 'id']);
    }

    /**
     * 女優名から存在確認する。
     * @param string $name  女優名
     * @param string $lang  言語
     * @return bool
     */
    static public function existCheckByName($name, $lang){
        /*todo:
        $nameからす

        */
        return true;
    }
}