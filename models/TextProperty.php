<?php

namespace app\models;
use yii\base\Model;

class TextProperty extends Model
{
    public $id;
    public $name;
    public $text_limit;

    static private $cache = [];//テーブルデータをクラスキャッシュ

    static public function getInstanceFromName($name){
        $ret_obj = false;
        if(empty(self::$cache)){
            self::loadCache();
        }
        foreach(self::$cache as $data){
            if($data['name'] == $name){
                $ret_obj = new self($data);
            }
        }
        return $ret_obj;
    }

    private static function loadCache(){
        $db = \Yii::$app->db;
        self::$cache = $db->createCommand("SELECT id, name, text_limit FROM text_property")->queryAll();
    }
}