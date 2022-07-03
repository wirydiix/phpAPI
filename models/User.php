<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

class User extends ActiveRecord
{
    public static function tableName(): string
    {
        return 'users';
    }

    public function getUserDB($postRequest)
    {
        $request = $this->find()->where(['login' => $postRequest['login']])->asArray()->one();
        $this->login = $request['login'];
        return $request;
    }

    public function updateRules($id, $rules)
    {
        $user = User::findOne($id);
        if($user->id_rules !=3){
            $user->id_rules = $rules;
            $request['status'] = $user->save();
        }
        else{
            $request['status'] = false;
        }
        return $request;
    }
    public function getNameById($comment)
    {
        // print_r($comment['comments']);
        // $comment = $this->find()->where(['login' => $postRequest['login']])->asArray()->one();
        for($i=0; $i<count($comment['comments']);$i++){
            $comment['comments'][$i]["FullName"] = $this->find()->select(['name', 'surname', 'login'])->where(["id_user" => $comment['comments'][$i]["id_user"]])->asArray()->one();
        }
        return $comment;
    }

    public function registerUserBD($post)
    {

        if (!$post || !$post['login'] || !$post['password'] || !$post['name'] || !$post['surname']) {
            $result['message']['post'] = 'Register fall.';
        }
        else{
            $user = new User();
        
            $user->login = $post['login'];
            $user->password = md5($post['password']);
            $user->name = $post['name'];
            $user->surname = $post['surname'];
            
        try {
            $request['status'] = $user->save();
            $request['message'] = 'Register complite';
        } catch (\Exception $e) {
            $request['body']['message'] = "Login is unavailable";
        }
            
        }
        
        return $request;
    }
    public function getMultipleParams($id = null, $params = [])
    {
        return $this->find()->select($params)->where(['id_user' => $id])->one();
    }

    public function validUser()
    {
        $session = Yii::$app->session;

        if (!$session['user']['id_rules'])
            return [
                'status' => false,
                'statusCode' => 403,
                'statusText' => 'Execute access forbidden'
            ];


        $isValid = (new Token())->checkToken($session['user']['login']);
        
        if (!$isValid)
            return [
                'status' => false,
                'statusCode' => 401,
                'statusText' => 'Unauthorized'
            ];

        return ['status' => true];
    }
}
