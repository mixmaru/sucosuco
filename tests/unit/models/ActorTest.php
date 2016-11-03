<?php
namespace tests\models;
use app\models\Actor;
use app\tests\fixtures\ActorFixture;
use app\tests\fixtures\ActorTextPropertyFixture;
use app\tests\fixtures\TextFixture;

class ActorTest extends \Codeception\Test\Unit
{
    private $actor_fixture;
    private $actor_text_property_fixture;
    private $text_fixture;
    public function _before(){
        $this->actor_fixture = new ActorFixture();
        $this->actor_fixture->load();

        $this->actor_text_property_fixture = new ActorTextPropertyFixture();
        $this->actor_text_property_fixture->load();

        $this->text_fixture = new TextFixture();
        $this->text_fixture->load();
    }

    public function _after(){
    }

    // tests
    public function testGetInstanceById()
    {
        //millle_name無しの場合
        expect_that($actor = Actor::getInstanceById(1,1));
        expect($actor->id)->equals($this->actor_fixture->data[1]['id']);
        expect($actor->type)->equals($this->actor_fixture->data[1]['type']);
        expect($actor->key_name)->equals($this->actor_fixture->data[1]['key_name']);
        expect($actor->first_name)->equals($this->text_fixture->data[1]['text']);
        expect($actor->middle_name)->isEmpty();
        expect($actor->last_name)->equals($this->text_fixture->data[2]['text']);

        //millle_nameありの場合
        expect_that($actor = Actor::getInstanceById(4,2));
        expect($actor->id)->equals($this->actor_fixture->data[4]['id']);
        expect($actor->type)->equals($this->actor_fixture->data[4]['type']);
        expect($actor->key_name)->equals($this->actor_fixture->data[4]['key_name']);
        expect($actor->first_name)->equals($this->text_fixture->data[8]['text']);
        expect($actor->middle_name)->equals($this->text_fixture->data[9]['text']);
        expect($actor->last_name)->equals($this->text_fixture->data[10]['text']);
    }
}
