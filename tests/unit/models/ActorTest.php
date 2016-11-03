<?php
namespace tests\models;
use app\models\Actor;
use app\tests\fixtures\ActorFixture;

class ActorTest extends \Codeception\Test\Unit
{
    private $actor_fixture;
    public function _before(){
        $this->actor_fixture = new ActorFixture();
        $this->actor_fixture->load();
    }

    public function _after(){
        $this->actor_fixture->unload();
    }

    // tests
    public function testGetActorData()
    {
//        $actor = Actor::getInstanceById(1,1);
//        $actor_array = $actor->attributes;
////        var_dump($actor_array);
//        expect($actor_array)->equals(['afdsafs']);
    }
}
