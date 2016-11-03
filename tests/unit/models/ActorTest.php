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
        $actor = Actor::getInstanceById(1,1);
//        $actor_array = $actor->attributes;
////        var_dump($actor_array);
//        expect($actor_array)->equals(['afdsafs']);
    }
}
