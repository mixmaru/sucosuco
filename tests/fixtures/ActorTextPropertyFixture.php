<?php
namespace app\tests\fixtures;

use yii\test\ActiveFixture;
/**
 * Created by PhpStorm.
 * User: mix
 * Date: 2016/11/02
 * Time: 21:11
 */
class ActorTextPropertyFixture extends ActiveFixture
{
    public $tableName = "actor_text_property";

    public function unload(){
        $this->resetTable();
        parent::unload();
    }

}