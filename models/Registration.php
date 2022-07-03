<?php

namespace app\models;

use Yii;
use yii\base\Module;
use yii\web\Response;

class Registration extends Module
{
    /**
     * @throws \yii\base\Exception
     */
    public function RegisterF()
    {
        $session = Yii::$app->session;
        $post = Yii::$app->request->post();
        $result = (new User())->registerUserBD($post);
        if (!$result)
            return [
                'status code' => 401,
                'status text' => 'Invalid register data',
                'body' => [
                    'status' => false,
                    'message' => 'Invalid register data'
                ]
            ];


        if (!$result['status']) {
            return [
                'status code' => 401,
                'status text' => 'Invalid register data',
                'body' => [
                    'status' => false,
                    'message'=> $result['body']['message']
                ]
            ];

        }
        else{
            $user = new User();
        
            $user->login = $post['login'];
            $user->password = md5($post['password']);
            $user->name = $post['name'];
            $user->surname = $post['surname'];
            $user->id_rules = 0;
            $session->set("user", $user);
            $tokenModel = new Token();
            $token = $tokenModel->createToken($post['login']);

            $result['body']['token'] = $token;
            $session['token'] = $token;
        }
        return $result;

    }

    public function checkPassword($userPassword, $password)
    {
        if ($userPassword === $password)
            return [
                'status' => true,
                'statusCode' => 200,
                'statusText' => 'Successful authorization'
            ];
    }


}