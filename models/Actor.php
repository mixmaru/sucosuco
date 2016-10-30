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
    public $created;
    public $updated;

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

    public function save($language_id){
        if(!self::existId($this->id)){
            $this->create($language_id);
        }else{
            $this->update($language_id);
        }
    }

    public static function existId($id){
        $sql = "SELECT id FROM actor WHERE id = :id";
        $result = \Yii::$app->db->createCommand($sql, [':id' => $id])->queryOne();
        if($result === false){
            return false;
        }else{
            return true;
        }
    }

    private function create($language_id){
        $db = \Yii::$app->db;
        $did_id_null = is_null($this->id);
        $now = date('Y-m-d H:i:s');
        $actor_sql = "INSERT INTO actor (type, key_name, created, updated) VALUES (:type, :key_name, :created, :updated)";
        $param = [
            ':type' => $this->type,
            ':key_name' => $this->key_name,
            ':created' => $now,
            ':updated' => $now,
        ];
        $transaction = $db->beginTransaction();
        try{
            $db->createCommand($actor_sql, $param)->execute();
            if($did_id_null){
                $this->id = $db->getLastInsertID();
            }

            //textにfirst_name, middle_name, last_nameを保存して、それぞれのidを保持
            $text_insert_command = $db->createCommand("INSERT INTO text (text, created, updated) VALUES (:text, :created, :updated)");
            $actor_text_property_insert_command = $db->createCommand("INSERT INTO actor_text_property (actor_id, text_property_id, language_id, text_id, created, updated) VALUES (:actor_id, :text_property_id, :language_id, :text_id, :created, :updated)");
            foreach($this->getTextPropertyNames() as $text_property_name){
                if(is_null($this->$text_property_name)){
                    continue;
                }
                $text_property_obj = TextProperty::getInstanceFromName("actor_".$text_property_name);
                $text_insert_command->bindValues([
                    ':text' => $this->$text_property_name,
                    ':created' => $now,
                    ':updated' => $now,
                ]);
                $text_insert_command->execute();
                //id取得
                $text_id = $db->getLastInsertID();
                $actor_text_property_insert_command->bindValues([
                    ':actor_id' => $this->id,
                    ':text_property_id' => $text_property_obj->id,
                    ':language_id' => $language_id,
                    ':text_id' => $text_id,
                    ':created' => $now,
                    ':updated' => $now,
                ]);
                $actor_text_property_insert_command->execute();
            }
        }catch(\Exception $e){
            $transaction->rollBack();
            if($did_id_null){
                $this->id = null;
            }
            throw $e;
        }
        $this->created = $now;
        $this->updated = $now;
        $transaction->commit();
    }

    private function update($language_id){
        $now = date('Y-m-d H:i:s');
        $sql = "UPDATE actor SET type = :type, key_name = :key_name, updated = :updated WHERE id = :id";
        $param = [
            ':id' => $this->id,
            ':type' => $this->type,
            ':key_name' => $this->key_name,
            ':updated' => $now,
        ];
        try{
            \Yii::$app->db->createCommand($sql, $param)->execute();
        }catch(\Exception $e){
            throw $e;
        }
        $this->updated = $now;
    }

    private function getTextPropertyNames(){
        return [
            'first_name',
            'middle_name',
            'last_name',
        ];
    }
}
