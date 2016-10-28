<?php

namespace app\models;

use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

class Article extends ActiveRecord
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
    public function getSite(){
        return $this->hasOne(Site::className(), ['id' => 'site_id']);
    }
    public function getActors(){
        return $this->hasMany(Actor::className(), ['id' => 'actor_id'])->viaTable('article_actor', ['actor_id', 'id']);
    }

    /**
     * 新しい記事を登録する。サンプル画像、サンプル動画は直リンクで登録する。
     * 指定の女優、カテゴリーが存在しなかった場合は新規登録される。
     * カテゴリ名、女優名などテキストで放り込むことができる
     * @param int       $lang_id            登録言語
     * @param string    $title_text         記事タイトル
     * @param string    $copy_text          記事キャッチコピー
     * @param string    $main_text          本文
     * @param int       $site_id            サイトid
     * @param string    $source_url         元記事url
     * @param string[]  $actor_names        女優名
     * @param string[]  $category_names     カテゴリー名
     * @param string[]  $sample_image_urls  サンプル画像url
     * @param string[]  $sample_movie_urls  サンプル動画url
     * @return bool     成功したらtrue, 失敗したらfalse
     */
    public function addNewArticleByRawData($lang_id, $title_text,
                                  $copy_text, $main_text,
                                  $site_id, $source_url,
                                  array $actor_names, array $category_names,
                                  array $sample_image_urls, array $sample_movie_urls){
        /*todo:
        ◆次やること
        女優モデル、カテゴリモデルに登録、取得に必要な機能を実装していく。
        その後、このモデルの動作を完成させる


        トランザクション開始
        $this->source_url = $source_url;
        $this->save();

//        女優ids[] = 女優model->getIdsFromNames($actor_names);//存在しなければ新規登録してidを返す
//        カテゴリーids[] = カテゴリーmodel->getIdsFromNames($category_names);//存在しなければ新規登録してidを返す
        foreach(){
            $this->addCategory(カテゴリーモデル);
            $this->addActor(女優モデル);
        }

        $image_ids = [];
        foreach($sample_image_urls as $url){
            $image = new 直リンク画像モデル();
            $image->saveByUrl($url, $this->id);
            $image_ids[] = $image->id;
        }

        $movie_ids = [];
        foreach($sample_movie_urls as $url){
            $movie = new 直リンク動画モデル();
            $movie->saveByUrl($url, $this->id);
            $movie_ids[] = $movie->id;
        }

        foreach(記事のプロパティの種類 as $プロパティid){
            $article_property = new 記事プロパティモデル();
            $article_property->saveWithText($title_text, $this->id, $プロパティid, $lang_id);//$title_textの部分をcopy,main,で行う
        }

        コミット

        */
        return true;
    }
}