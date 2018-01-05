<?php
/**
 * Created by PhpStorm.
 * User: luke
 * Date: 7/02/16
 * Time: 1:37 AM
 */
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \Library\Settings as Settings;

require '../vendor/autoload.php';

$custom = new \Slim\Container(); //Create Your container

//Override the default Not Found Handler & notAllowedHandler
$custom['notFoundHandler'] = function ($c) {
    return function ($request, $response) use ($c) {
        $data = array('data' => 'Incorrect Call', 'success' => false);
        return $c['response']
            ->withStatus(404)
            ->withJson($data);
    };
};
$custom['notAllowedHandler'] = function ($c) {
    return function ($request, $response) use ($c) {
        $data = array('data' => 'Incorrect Call', 'success' => false);
        return $c['response']
            ->withStatus(405)
            ->withJson($data);
    };
};

$app = new \Slim\App($custom);




$app->run();