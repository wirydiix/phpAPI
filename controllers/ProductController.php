<?php

namespace app\controllers;

use Yii;
use app\models\Token;
use app\models\User;
use app\models\Product;
use app\models\Comment;
use app\models\Category;
use app\models\Search;
use app\models\Contract;

use yii\web\Controller;
use yii\web\Response;

class ProductController extends Controller
{
    public $enableCsrfValidation = false;

    public function actionIndex($id = null)
    {

        \Yii::$app->response->format = Response::FORMAT_JSON;

        $method = Yii::$app->request->method;
        $result = [];

        // get all products
        if ($method === 'GET' && !$id) {
            $result = (new Product())->getAllProduct();

            if (!$result['status']) {
                \Yii::$app->response->statusCode = 404;

                $result['statusCode'] = 404;
                $result['statusText'] = 'Products not found';

                return $result;
            }

            $result['statusCode'] = 200;
            $result['statusText'] = 'List product';

            return $result;
        }
        // get one product
        if ($method === 'GET' && $id) {
            \Yii::$app->response->format = Response::FORMAT_JSON;

            $result = (new Product())->getOneProduct($id);

            if (!$result['status']) {
                \Yii::$app->response->statusCode = 404;

                $result['statusCode'] = 404;
                $result['statusText'] = 'Product not found';
            } else 
                \Yii::$app->response->statusCode = $result['statusCode'];

            return $result;
        }

        // add new product
        if ($method === 'POST' && !$id) {
            $isValid = (new User())->validUser();
            if (!$isValid['status']) {
                \Yii::$app->response->statusCode = $isValid['statusCode'];
                return $isValid;
            }               

            $result['body'] = (new Product())->addProduct($_POST);

            if ($result['body']['status']) {
                \Yii::$app->response->statusCode = 201;

                $result['statusCode'] = 201;
                $result['statusText'] = 'Successful creation';
            } else {
                \Yii::$app->response->statusCode = 400;

                $result['statusCode'] = 400;
                $result['statusText'] = 'Creating error';
            }

            return $result;
        }

        // add change product
        if ($method === 'POST' && $id) {
            $isValid = (new User())->validUser();
            if (!$isValid['status']) {
                \Yii::$app->response->statusCode = $isValid['statusCode'];
                return $isValid;
            }

            $result['body'] = (new Product())->updateProduct($id, $_POST);

            if ($result['body']['status']) {
                \Yii::$app->response->statusCode = 201;
                $result['statusCode'] = 201;
                $result['statusText'] = 'Successful edite';
            } else {
                \Yii::$app->response->statusCode = 400;
                $result['statusCode'] = 400;
                $result['statusText'] = 'Editing error';
            }

            return $result;
        }

        // delete product
        if ($method === 'DELETE') {
            $isValid = (new User())->validUser();
            if (!$isValid['status']) {
                \Yii::$app->response->statusCode = $isValid['statusCode'];
                return $isValid;
            }

            $product = Product::findOne($id);
            if ($product === null) {
                \Yii::$app->response->statusCode = 404;

                $result['body']['status'] = false;
                $result['body']['message']['db'] = 'Product not found';
                $result['statusCode'] = 404;
                $result['statusText'] = 'Product not found';

                return $result;
            }

            if ($product->delete()) {
                \Yii::$app->response->statusCode = 201;

                $result['body']['status'] = true;
                $result['body']['message'] = 'Successful delete';
                $result['statusCode'] = 201;
                $result['statusText'] = 'Successful delete';
            }

            return $result;
        }

    }
    public function actionCategory($id = null)
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;

        $method = Yii::$app->request->method;
        $result = [];
        
        // get all category
        if ($method === 'GET' && !$id) {
            $result = (new Category())->getAllCategory();
            if (!$result['status']) {
                \Yii::$app->response->statusCode = 404;

                $result['statusCode'] = 404;
                $result['statusText'] = 'Category not found';

                return $result;
            }

            $result['statusCode'] = 200;
            $result['statusText'] = 'List category';

            return $result;
        }
    }
    public function actionSearch($tag = null)
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;

        $method = Yii::$app->request->method;
        $result = [];
        // get all category
        if ($method === 'GET' && !$id) {
            $result["products"] = (new Search())->searchProduct($_GET["tags"]);
            if (!count($result["products"])) {
                \Yii::$app->response->statusCode = 404;

                $result['statusCode'] = 404;
                $result['statusText'] = 'Product not found';

                return $result;
            }
            $result['status'] = true;
            $result['statusCode'] = 200;
            $result['statusText'] = 'List category';

            return $result;
        }
    }
    public function actionContract($id = null)
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;

        $session = Yii::$app->session;
        $method = Yii::$app->request->method;
        $result = [];
        if ($method === 'GET' && $id) {
            $result = (new Contract())->getContract($id);
            
        }
        // addContract
        if ($method === 'POST') {
            if (!$session['token'] && !$_POST['login']) {
                \Yii::$app->response->statusCode = 400;

                return [
                    'status' => false,
                    'statusCode' => 400,
                    'statusText' => 'Bad request',
                    'message' => 'Not found login',
                ];
            }

            if (!$session['token'])
                $login = $_POST['login'];
            else {
                

                $isValid = (new Token())->checkToken($login);

                if (!$isValid) {
                    \Yii::$app->response->statusCode = 401;

                    return [
                        'status' => false,
                        'statusCode' => 401,
                        'statusText' => 'Unauthorized',
                        'message' => 'Unauthorized',
                    ];
                }
            }
            $id_user = $session['user']['id_user'];
            $result['body']['status'] = (new Contract())->addContract($_POST, $id_user);

            if ($result['body']['status']['status']) {
                \Yii::$app->response->statusCode = 201;

                $result['body']['message'] = 'Successful creation';
                $result['statusCode'] = 201;
                $result['statusText'] = 'Successful creation';
            } else {
                \Yii::$app->response->statusCode = 400;
                $result['statusCode'] = 400;
                $result['statusText'] = 'Create error';

            }

            return $result;
        }
        return $result;
    }
    public function actionComment($id = null)
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;

        $session = Yii::$app->session;
        $method = Yii::$app->request->method;
        $result = [];

        // add new comment
        if ($method === 'POST') {
            if (!$session['token'] && !$_POST['login']) {
                \Yii::$app->response->statusCode = 400;

                return [
                    'status' => false,
                    'statusCode' => 400,
                    'statusText' => 'Bad request',
                    'message' => 'Not found login',
                ];
            }

            if (!$session['token'])
                $login = $_POST['login'];
            else {
                $id_user = $session['user']['id_user'];

                $isValid = (new Token())->checkToken($login);

                if (!$isValid) {
                    \Yii::$app->response->statusCode = 401;

                    return [
                        'status' => false,
                        'statusCode' => 401,
                        'statusText' => 'Unauthorized',
                        'message' => 'Unauthorized',
                    ];
                }
            }

            $result['body']['status'] = (new Comment())->addComment($_POST, $id_user, $id);

            if ($result['body']['status']) {
                \Yii::$app->response->statusCode = 201;

                $result['body']['message'] = 'Successful creation';
                $result['statusCode'] = 201;
                $result['statusText'] = 'Successful creation';
            } else {
                \Yii::$app->response->statusCode = 400;
                $result['statusCode'] = 400;
                $result['statusText'] = 'Editing error';

            }

            return $result;
        }

        // delete comment
        if ($method === 'DELETE') {
            $isValid = (new User())->validUser();
            if (!$isValid['status']) {
                \Yii::$app->response->statusCode = $isValid['statusCode'];
                return $isValid;
            }

            $comment = Comment::findOne($id);

            if ($comment === null) {
                \Yii::$app->response->statusCode = 404;

                $result['body']['status'] = false;
                $result['body']['message']['db'] = 'Comment not found';
                $result['statusCode'] = 404;
                $result['statusText'] = 'Comment not found';

                return $result;
            }

            if ($comment->delete()) {
                \Yii::$app->response->statusCode = 201;

                $result['body']['status'] = true;
                $result['body']['message'] = 'Successful delete';
                $result['statusCode'] = 201;
                $result['statusText'] = 'Successful delete';
            }

            return $result;
        }
    }

}