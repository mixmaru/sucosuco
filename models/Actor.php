<?php

namespace app\models;

use yii\base\UnknownPropertyException;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

class Actor extends ActiveRecord
{
    const TYPE_AV = 1;  //AV女優
    const TYPE_TV = 2;  //芸能人

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
    public function getActorTextProperties(){
        return $this->hasMany(ActorTextProperty::className(), ['actor_id' => 'id']);
    }
    public function getTexts(){
        return $this->hasMany(Text::className(), ['id' => 'text_id'])->via('actorTextProperties');
    }

    public function getFirstName($lang_id){
        return "first_name";
    }

    static function saveNewAvActor($key_name, $lang_id, $first_name, $middle_name, $last_name, array $thumb_url = []){
        //todo: 文字数制限チェックと画像urlの登録
        $profiles = ['first_name', 'middle_name', 'last_name'];
        $transaction = self::getDb()->beginTransaction();
        try{
            //女優を新規登録
            $actor = new Actor();
            $actor->key_name = $key_name;
            $actor->type = self::TYPE_AV;
            $actor->save();

            //名前を新規登録
            $name_ids = [];
            foreach($profiles as $profile){
                if(!empty($$profile)){
                    $text = new Text();
                    $text->text = $$profile;
                    $text->save();
                    $name_ids[$profile] = $text->id;
                }
            }

            //女優プロフィール関連テーブルに保存
            foreach($profiles as $profile){
                if(!empty($$profile)){
                    $text_property = TextProperty::findOne(['name' => "actor_".$profile]);
                    $actor_text_profile = new ActorTextProperty();
                    $actor_text_profile->actor_id = $actor->id;
                    $actor_text_profile->text_property_id = $text_property->id;
                    $actor_text_profile->language_id = $lang_id;
                    $actor_text_profile->text_id = $name_ids[$profile];
                    $actor_text_profile->save();
                }
            }
        }catch(\Exception $e){
            $transaction->rollBack();
            throw $e;
        }
        $transaction->commit();
        return true;
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