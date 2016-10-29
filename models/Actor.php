<?php

namespace app\models;

use yii\base\Model;

class Actor extends Model {
    public $id;
    public $type;
    public $key_name;
    public $first_name;
    public $middle_name;
    public $last_name;
    public $profile_image_paths = [];

    public static function getInstanceById($id, $language_id){
        $actor = new Actor();
        $actor->loadFromDbById($id, $language_id);
        if(is_null($actor->id)){
            return false;
        }else{
            return $actor;
        }
    }

    public function loadFromDbById($id, $language_id){
        $db = \Yii::$app->db;
        $sql = "SELECT ac.id as id, ac.type as type, ac.key_name as key_name, "
            ."MAX( CASE WHEN atp.text_property_id = 1 THEN t.text ELSE NULL END) as first_name, "
            ."MAX( CASE WHEN atp.text_property_id = 2 THEN t.text ELSE NULL END) as middle_name, "
            ."MAX( CASE WHEN atp.text_property_id = 3 THEN t.text ELSE NULL END) as last_name "
            ."FROM actor ac "
            ."INNER JOIN actor_text_property atp ON atp.actor_id = ac.id "
            ."INNER JOIN text t ON t.id = atp.text_id "
            ."WHERE ac.id = :id "
            ."AND atp.language_id = :language_id "
            ."GROUP BY id ";
        $param = $db->createCommand($sql, [':id' => $id, ':language_id' => $language_id ])->queryOne();
        $this->id = $param['id'];
        $this->type = $param['type'];
        $this->key_name = $param['key_name'];
        $this->first_name = $param['first_name'];
        $this->middle_name = $param['middle_name'];
        $this->last_name = $param['last_name'];
    }
}
