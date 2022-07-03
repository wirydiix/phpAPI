<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\base\Model;
use yii\web\UploadedFile;

class Product extends ActiveRecord
{

    public static function tableName(): string
    {
        return 'product';
    }

    public function getAllProduct(): array
    {
        try {
            $result['status'] = true;
            $result['products'][] = $this->find()->asArray()->all();
        } catch (\Exception $e) {
            $result['status'] = false;
            $result['body']['message']['db'] = 'Products not found';
        }

        return $result;
    }

    public function getOneProduct($id): array
    {
        $product = $this->findOne($id);

        if ($product === null) {
            $result['status'] = false;
            $result['message']['db'] = 'Product not found';

            return $result;
        }

        $result['product'] = $product;
        $comment = (new Comment())->getComment($id);
        if(!$comment['message']=="No comment"){     
            $comment = (new User())->getNameById($comment);
        }
            
        if (!$comment['status'])
            return $comment;
        else {
            $result['status'] = true;
            $result['statusCode'] = 200;
            $result['statusText'] = 'View product';
            $result['comment'] = $comment;
        }

        return $result;
    }

    public function addProduct($post): array
    {
        if (!$post || !$post['id_ctg'] || !$post['name_prd'] || !$post['description_prd'] || !$post['price'] || !$post['tags'] || $post['image']) {
            $result['message']['post'] = 'Post array is incomplete or empty';
            return $result;
        }

        $product = new Product();
        if (!$product)
            $result['message']['db'] = 'Error connection db';


        $product->id_ctg = $post['id_ctg'];
        $product->name_prd = $post['name_prd'];
        $product->description_prd = $post['description_prd'];
        $product->price = $post['price'];
        $post['tags'] = explode(',', $post['tags']);
        $product->tags = json_encode($post['tags'], JSON_UNESCAPED_UNICODE);
        $errorText ="";
        $typeFile = $_FILES['image']['type'];
        $typeSize = $_FILES['image']['size'];
        if(($typeFile == "image/jpeg" || $typeFile == "image/png") && $_FILES['image']['size'] <= 2097152){
            $countFiles = count(scandir("../post_images"))-2;
            $uploaddir = '../post_images';
            $uploadfile = $uploaddir ."/". $countFiles . ".png";
            if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadfile)) {
                $url = ((!empty($_SERVER['HTTPS'])) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'];
                $url = explode('?', $url);
                $url = $url[0];
                $product->image =  $url."/post_images/$countFiles.png";
                $result['status'] = $product->save();
                $result['message'] = 'Successful create';
            }
        }
        else if($_FILES['image']['size'] > 2097152)
            $errorText = "Incorrect size";
        else
            $errorText = "Incorrect format";
        
        try {
        } catch (\Exception $e) {
            $result['status'] = false;
            $result['message']['added'] = 'Error added product';
        }
        if(!$product['image'])
            $result['message']['added'] = 'Error added product.' . $errorText;

        return $result;
    }

    public function updateProduct($id, $post): array
    {
        if (!$post) {
            $result['message']['post'] = 'Post array is incomplete or empty';
        }

        $product = Product::findOne($id);
        if ($product === null) {
            $result['status'] = false;
            $result['message']['db'] = 'Product not found';

            return $result;
        }

        if ($post['id_ctg'] !== null) $product->id_ctg = $post['id_ctg'];
        if ($post['name_prd'] !== null) $product->name_prd = $post['name_prd'];
        if ($post['description_prd'] !== null) $product->description_prd = $post['description_prd'];
        if ($post['price'] !== null) $product->price = $post['price'];
        if ($post['tags'] !== null) {
            $post['tags'] = explode(',', $post['tags']);
            $product->tags = json_encode($post['tags'], JSON_UNESCAPED_UNICODE);
        }
        $errorText ="";
        if($_FILES['image']){
            $typeFile = $_FILES['image']['type'];
            $typeSize = $_FILES['image']['size'];
            $imageValid = false;
            if(($typeFile == "image/jpeg" || $typeFile == "image/png") && $_FILES['image']['size'] <= 2097152){
                $countFiles = count(scandir("../post_images"))-2;
                $uploaddir = '../post_images';
                $uploadfile = $uploaddir ."/". $countFiles . ".png";
                if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadfile)) {
                    $url = ((!empty($_SERVER['HTTPS'])) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'];
                    $url = explode('?', $url);
                    $url = $url[0];
                    $product->image =  $url."/post_images/$countFiles.png";
                    $result['message'] = 'Successful edite';
                    $imageValid = true;
                } 
            }
            else if($_FILES['image']['size'] > 2097152)
                $errorText = "Incorrect size";
            else if(($typeFile !== "image/jpeg" || $typeFile !== "image/png"))
                $errorText = "Incorrect format";
            try {
            } catch (\Exception $e) {
                $result['message']['change'] = 'Error change product';
            }
            if(!$imageValid)
                $result['message'] = 'Error change product. ' . $errorText;
        }
        $result['status'] = $product->save();
        return $result;
    }

    
}