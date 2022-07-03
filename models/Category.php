<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

class Category extends ActiveRecord
{

    public static function tableName(): string
    {
        return 'Category';
    }
    
    public function getAllCategory(): array
    {
        try {
            $result['status'] = true;
            $result['categorys'][] = $this->find()->asArray()->all();
        } catch (\Exception $e) {
            $result['status'] = false;
            $result['body']['message']['db'] = 'Category not found';
        }

        return $result;
    }
}