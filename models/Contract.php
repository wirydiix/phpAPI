<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

class Contract extends ActiveRecord
{

    public static function tableName(): string
    {
        return 'contract';
    }

    public function getContract($id): array
    {
        $comment = $this->find()->select(['id_user', 'id_prd', 'from_date', 'to_date','final_price'])->where(["id_contract" => $id])->asArray()->all();

        if ($comment === null) {
            $result['status'] = false;
            $result['message']['db'] = 'Contract not found';

            return $result;
        }

        $result['status'] = true;
        if (count($comment) == 0) {
            $result['message'] = 'No contract';

            return $result;
        }

        $result['contract'] = $comment;
        return $result;
    }

    public function addContract($post, $id_user)
    {
        if($post['id_prd'] && $post['from_date'] &&$post['to_date']){
            $contract = new Contract();

            $contract->id_user = $id_user;
            $contract->id_prd = $post['id_prd'];
            $contract->from_date = $post['from_date'];
            $contract->to_date = $post['to_date'];
            
            try {
                $request['status'] = $contract->save();
                $request['message'] = 'Register complite';
            } catch (\Exception $e) {
                $request['message'] = 'The room is occupied';
                $request['status'] = false;

            }
        }
        
        return $request;
    }
}