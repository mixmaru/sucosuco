<?php

namespace app\models;

use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

class ActorTextProperty extends ActiveRecord
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
    public function getActor(){
        return $this->hasOne(Actor::className(), ['id' => 'actor_id']);
    }
    public function getTextProperty(){
        return $this->hasOne(TextProperty::className(), ['id' => 'text_property_id']);
    }
    public function getText(){
        return $this->hasOne(Text::className(), ['id' => 'text_id']);
    }
}