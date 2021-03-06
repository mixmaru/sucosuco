<?php
namespace tests\models;
use app\models\Actor;
use app\tests\fixtures\ActorFixture;
use app\tests\fixtures\ActorTextPropertyFixture;
use app\tests\fixtures\TextFixture;
use app\tests\fixtures\TextPropertyFixture;

class ActorTest extends \Codeception\Test\Unit
{
    private $actor_data;
    private $actor_text_property_data;
    private $text_data;
    private $text_property;
    public function _fixtures(){
        return [
            'actor' => ActorFixture::className(),
            'actor_text_property' => ActorTextPropertyFixture::className(),
            'text' => TextFixture::className(),
            'text_property' => TextPropertyFixture::className(),
        ];
    }

    public function _before(){
        //フィクスチャのテキストデータを読み込む（Yiiで考慮されている正式な取り出し方がわからないので。。）
        $data_dir_path = \Yii::getAlias("@app/tests/fixtures/data/");
        $this->actor_data = require($data_dir_path."actor.php");
        $this->actor_text_property_data = require($data_dir_path."actor_text_property.php");
        $this->text_data = require($data_dir_path."text.php");
        $this->text_property = require($data_dir_path."text_property.php");
    }

    public function _after(){
    }

    // tests
    public function testGetInstanceById()
    {
        //middle_name無しの場合
        expect_that($actor = Actor::getInstanceById(1,1));
        expect($actor->id)->equals($this->actor_data[1]['id']);
        expect($actor->type)->equals($this->actor_data[1]['type']);
        expect($actor->key_name)->equals($this->actor_data[1]['key_name']);
        expect($actor->first_name)->equals($this->text_data[1]['text']);
        expect($actor->middle_name)->isEmpty();
        expect($actor->last_name)->equals($this->text_data[2]['text']);
        expect($actor->created)->equals($this->actor_data[1]['created']);
        expect($actor->updated)->equals($this->actor_data[1]['updated']);

        //middle_nameありの場合
        expect_that($actor = Actor::getInstanceById(4,2));
        expect($actor->id)->equals($this->actor_data[4]['id']);
        expect($actor->type)->equals($this->actor_data[4]['type']);
        expect($actor->key_name)->equals($this->actor_data[4]['key_name']);
        expect($actor->first_name)->equals($this->text_data[8]['text']);
        expect($actor->middle_name)->equals($this->text_data[9]['text']);
        expect($actor->last_name)->equals($this->text_data[10]['text']);
        expect($actor->created)->equals($this->actor_data[4]['created']);
        expect($actor->updated)->equals($this->actor_data[4]['updated']);
    }

    public function testExistId(){
        //存在するとき
        expect(Actor::existId(1))->true();
        //存在しないとき
        expect(Actor::existId(1000000))->false();
        //適当な数値
        expect(Actor::existId("aaa"))->false();
    }

    public function testSetterGetter(){
        $actor = new Actor();
        $actor->id = 1000;
        $actor->type = 1;
        $actor->key_name = "key_name";
        $actor->first_name = "first_name";
        $actor->middle_name = "middle_name";
        $actor->last_name = "last_name";
        expect($actor->id)->equals(1000);
        expect($actor->type)->equals(1);
        expect($actor->key_name)->equals("key_name");
        expect($actor->first_name)->equals("first_name");
        expect($actor->middle_name)->equals("middle_name");
        expect($actor->last_name)->equals("last_name");
    }

    public function testGetAttributes(){
        //middle_nameなし
        $actor = Actor::getInstanceById(1, 1);
        $expect_array = $this->actor_data['1'];
        $expect_array['id'] = (int) $expect_array['id'];
        $expect_array['first_name'] = $this->text_data[1]['text'];
        $expect_array['middle_name'] = null;
        $expect_array['last_name'] = $this->text_data[2]['text'];
        $expect_array['profile_image_paths'] = [];
        expect($actor->attributes)->equals($expect_array);

        //middle_nameあり、英語指定
        $actor = Actor::getInstanceById(4, 2);
        $expect_array = $this->actor_data['4'];
        $expect_array['id'] = (int) $expect_array['id'];
        $expect_array['first_name'] = $this->text_data[8]['text'];
        $expect_array['middle_name'] = $this->text_data[9]['text'];
        $expect_array['last_name'] = $this->text_data[10]['text'];
        $expect_array['profile_image_paths'] = [];
        expect($actor->attributes)->equals($expect_array);
    }

    public function testSave(){
        //新規追加
        $actor = new Actor();
        $actor->type = 1;
        $actor->key_name = "harumage_ee_don";
        $actor->first_name = "ハルマゲ";
        $actor->middle_name = "エエ";
        $actor->last_name = "ドン";
//        $actor->save(1);
        $check_actor = Actor::getInstanceById($actor->id, 1);
        expect($actor->attributes)->equals($check_actor->attributes);

//        $actor->first_name = "harumage";
//        $actor->middle_name = "ee";
//        $actor->last_name = "don";
//        $actor->save(2);
//
//        //既存の編集
//        $actor = Actor::getInstanceById();
    }
}
