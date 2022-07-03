<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

class Search extends ActiveRecord
{

    public static function tableName(): string
    {
        return 'product';
    }
    
    public function searchProduct($tags): array
    {
        $tags = explode(',', $tags);
        $rows = Product::find()->select(['id_prd', 'tags'])->asArray()->all();
        $id = [];
        $result = [];
        foreach ($rows as $row) {
            $arrayStr = json_decode($row['tags']);
            $hawTag = false;
            for($i=0; $i<count($arrayStr);$i++){
                if (in_array(mb_strtolower($arrayStr[$i]), array_map('mb_strtolower', $tags)))
                    $hawTag = true;
            }
            if($hawTag){
                $result[] = $row;
            }                            
        }

        if (count($tags) > 1)
            for ($i = 1; $i < count($tags); $i++)
                foreach ($result as $key => $row) {                   
                    if (!mb_strtolower($row['tags']) === mb_strtolower($tags[0])){
                        unset($result[$key]);
                        
                    }
                        
                }

        foreach ($result as $key => $row)
            $id[] = $result[$key]['id_prd'];

        if (count($id) === 0)
            return ['message' => 'Совпадений не найдено'];
            $result = Product::findAll($id);          
        return $result;
    }
}