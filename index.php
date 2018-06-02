<?php
/*
include "./AccesoDatos.php";
include "./cliente.php";*/

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require './vendor/autoload.php';

$app = new \Slim\App;

$app->group('/saludo',function(){
    $this->get('/{nombre}',function($request,$response,$args){
        $nombre = $args['nombre'];
        $response->getBody()->write("Hola $nombre");
    });


    $this->post('/',function($request,$response,$args){
        $parsedBody = $request->getParsedBody();
        $name = $parsedBody['name'];
        
        $response->getBody()->write("Hello $name");
    });
});

$app->run();
?>