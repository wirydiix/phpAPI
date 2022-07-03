<?php

namespace app\models;

use Yii;
use yii\base\Module;
use yii\web\Response;

class Auth extends Module
{
    /**
     * @throws \yii\base\Exception
     */
    public function getAccessToken()
    {
        
        $session = Yii::$app->session;
        $post = Yii::$app->request->post();
        $user = (new User())->getUserDB($post);
        if(!$post || !$post['login'] || !$post['password'])
        return [
            'status code' => 401,
            'status text' => 'Invalid authorization data',
            'body' => [
                'status' => false,
                'message' => 'Invalid authorization data'
            ]
        ];       
        $session->set("user", $user);
        
        $user['password'];

        $result = $this->checkPassword(md5($post['password']), $user['password']);

        if ($user['id_rules']>=2) {
            $result['body']['admin'] = true;
            $session['admin'] = true;
        }

        if (!$result['status']) {
            return [
                'status code' => 401,
                'status text' => 'Invalid authorization data',
                'body' => [
                    'status' => false,
                    'message' => $result['statusText']
                ]
            ];

        } else {
            $tokenModel = new Token();
            $token = $tokenModel->createToken($session['user']['login']);

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
        else
            return[
                'status' => false,
                'statusCode' => 401,
                'statusText' => 'Invalid username or password'
            ];
    }


}