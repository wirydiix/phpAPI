<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

class Token extends ActiveRecord
{

    public static function tableName(): string
    {
        return 'token';
    }

    /**
     * @throws \yii\base\Exception
     */
    public function createToken($login): string
    {
        $tokenString = $login . time();
        $tokenHash = \Yii::$app->security->generatePasswordHash($tokenString);

        $token = new Token();
        $token->login = $login;
        $token->token = $tokenHash;
        $token->create_at = date("H:i:s");
        $token->destroy_at = date("H:i:s", strtotime('+1800 seconds'));

        $token->save();
        return $tokenHash;
    }

    public function getToken($token)
    {
        $row = $this->find()->where(['token' => $token])->asArray()->one();

        if (!$row)
            return ['message' => 'Неверный токен'];

        return $row;
    }

    public function deleteToken($token)
    {
        $session = Yii::$app->session;
        $row = Token::find()->where(['token' => $session['token']])->one();

        if ($row["token"] === $token)
            return $row->delete();
         else 
            return false;
        
    }

    public function checkToken($login): bool
    {
        $bearerToken = \Yii::$app->request->headers['authorization'];
        $token = substr($bearerToken, 7, strlen($bearerToken) - 7);

        if ($token === false)
            return false;

        $row = Token::find()->where(['token' => $token])->asArray()->one();

        if ($row === null && $row['login'] !== $login && $row['token'] !== $token )
            return false;

        return true;
    }
}