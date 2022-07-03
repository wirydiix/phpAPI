<?php

namespace app\controllers;

use Yii;
use app\models\Token;
use app\models\User;
use app\models\Auth;
use app\models\Registration;
use yii\web\Controller;
use yii\web\Response;

class AppController extends Controller
{

    public $enableCsrfValidation = false;

    public function actionIndex(): bool
    {
        return phpinfo();
    }

    public function actionError(): array
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        \Yii::$app->response->statusCode = 404;

        return 
        [
            'status code' => 404,
            'body' => [
                'status' => false,
                'message' => 'Page not found'
            ]
        ];
    }

    public function actionAuth() 
    {
        $session = Yii::$app->session;
        $session->open();

        \Yii::$app->response->format = Response::FORMAT_JSON;

        if ($session['token']) {           
            \Yii::$app->response->statusCode = 401;
            
            return [
                'statusCode' => 401,
                'statusText' => 'Invalid authorization',
                'body' => [
                    'status' => false,
                    'message' => 'You are already logged in'
                ]
            ];
        }

        $userAuth = new Auth(null);
        $userAuth = $userAuth->getAccessToken();
        
        if (!$userAuth['status']) \Yii::$app->response->statusCode = 401;
        \Yii::$app->response->statusCode = $userAuth['statusCode'];
        return $userAuth;
    }
    public function actionRegistration() 
    {
        $session = Yii::$app->session;
        $session->open();

        \Yii::$app->response->format = Response::FORMAT_JSON;

        if ($session['token']) {
            \Yii::$app->response->statusCode = 401;
            
            return [
                'statusCode' => 401,
                'statusText' => 'Invalid register',
                'body' => [
                    'status' => false,
                    'message' => 'Invalid register'
                ]
            ];
        }
        $userAuth = new Registration(null);
        $userAuth = $userAuth->RegisterF();
        
        if (!$userAuth['status']) \Yii::$app->response->statusCode = 401;
        \Yii::$app->response->statusCode = $userAuth['statusCode'];
        return $userAuth;
    }
    public function actionRules() 
    {
        $session = Yii::$app->session;
        $session->open();

        \Yii::$app->response->format = Response::FORMAT_JSON;

        $token = \Yii::$app->request->headers->get("Authorization");

        if (!$session['token']) {
            \Yii::$app->response->statusCode = 400;
            return [
                'statusCode' => 400,
                'statusText' => 'Invalid update rules',
                'body' => [
                    'status' => false,
                    'message' => 'You were not authorized'
                ]
            ];
        }   
        if($session['user']['id_rules']==3){
            if($_POST['id_user']!=""&& $_POST['id_rules']!=""){
                if($_POST['id_rules']>=3 || $_POST['id_rules']<0 || $_POST['id_user']<=0){
                    return [
                        'statusCode' => 400,
                        'statusText' => 'Invalid update rules',
                        'body' => [
                            'status' => false,
                            'message' => 'Going beyond the range of values'
                        ]
                    ];}
                if($_POST['id_user']==$session['user']['id_user']){
                    return [
                        'statusCode' => 400,
                        'statusText' => 'Invalid update rules',
                        'body' => [
                            'status' => false,
                            'message' => 'Going beyond the range of values'
                        ]
                    ];
                }
                $status = (new User())->updateRules($_POST['id_user'], $_POST['id_rules']);
                return [
                    'statusCode' => 200,
                    'statusText' => 'Successful update',
                    'body' => [
                        'status' => true,
                        'message' => 'Successful update'
                    ]
                ]; 
                
            }
            else
            return [
                'statusCode' => 400,
                'statusText' => 'Invalid update rules',
                'body' => [
                    'status' => false,
                    'message' => 'Not data'
                ]
            ];
        }
        else{
            \Yii::$app->response->statusCode = 400;
            return [
                'statusCode' => 400,
                'statusText' => 'Invalid update rules',
                'body' => [
                    'status' => false,
                    'message' => 'Not enough rights'
                ]
            ];
        }

        return( [
            'statusCode' => 400,
            'statusText' => 'Invalid update rules',
            'body' => [
                'status' => false,
                'message' => 'You were not authorized'
            ]
        ]);
    }

    /**
     * Logout action.
     *
     * @return string[]
     */
    public function actionLogout(): array
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $cookies = Yii::$app->response->cookies;
        $session = Yii::$app->session;

        $token = \Yii::$app->request->headers->get("Authorization");

        if (!$session['token']) {
            \Yii::$app->response->statusCode = 400;
            return [
                'statusCode' => 400,
                'statusText' => 'Invalid logout',
                'body' => [
                    'status' => false,
                    'message' => 'You were not authorized'
                ]
            ];
        }

        $token = mb_substr($token, 7, strlen($token));
        $status = (new Token())->deleteToken($token);
        if (!$status) {
            \Yii::$app->response->statusCode = 400;
            return [
                'statusCode' => 400,
                'statusText' => 'Error logout',
                'body' => [
                    'status' => false,
                    'message' => 'Error logout'
                ]
            ];
        }

        $cookies->remove('PHPSESSID');
        $session->destroy();
        \Yii::$app->response->statusCode = 200;

        return [
                'statusCode' => 200,
                'statusText' => 'Successful logout',
                'body' => [
                    'status' => true,
                    'message' => 'Successful logout'
                ]
            ];
    }
}
