<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

class Comment extends ActiveRecord
{

    public static function tableName(): string
    {
        return 'comment';
    }

    public function getComment($id): array
    {
        $comment = $this->find()->select(['text', 'time', 'id_user'])->where(["id_prd" => $id])->asArray()->all();

        if ($comment === null) {
            $result['status'] = false;
            $result['message']['db'] = 'Comment not found';

            return $result;
        }

        $result['status'] = true;
        if (count($comment) == 0) {
            $result['message'] = 'No comment';

            return $result;
        }

        $result['comments'] = $comment;
        return $result;
    }

    public function addComment($post, $id_user, $id)
    {
        $comment = new Comment();

        $comment->id_prd = $id;
        $comment->id_user = $id_user;
        $comment->text = $post['text'];
        $comment->time = date("Y-m-d");

        return $comment->save();
    }
}