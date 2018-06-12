<?php

include_once("./IApiUsable.php");
include_once("./InternalResponse.php");

//Entidades
include_once("./Model/pedido.php");
include_once("./Model/user.php");
include_once("./Model/tipos.php");

//Repository 
include_once("./Repository/AccesoDatos.php");
include_once("./Repository/pedidoRepository.php");
include_once("./Repository/userRepository.php");
include_once("./Repository/loginRepository.php");

//Api
include_once("./Api/pedidoApi.php");
include_once("./Api/userApi.php");
include_once("./Api/loginApi.php");

include_once("./Api/Token/token.php");
include_once("./Api/Token/token.php");

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \Firebase\JWT\JWT;

require './vendor/autoload.php';

$app = new \Slim\App;

    $app->group('/pedidos', function () {
        $this->get('/{nombre}', function ($request, $response, $args) {
            $nombre = $args['nombre'];
            $response->getBody()->write("Hola $nombre");
        });


        $this->post('/', \PedidoApi::class . ':cargarUno');
    });

    $app->group('/users', function () {
        $this->post('/{nombre}', function ($request, $response, $args) {
            $nombre = $args['nombre'];
            $response->getBody()->write("Hola $nombre");
        });
        
        $this->get('/prueba', function ($request, $response, $args) {
            $nombre =  "Demian";//$args['nombre'];
            $response->getBody()->write("Hola $nombre");

            return $response;
        });

        $this->post('/', \UserApi::class . ':CargarUno');

    })->add();

    $app->group('/login', function () {

        $this->post('/validateLogin', \LoginApi::class . ':Login');

        $this->post('/chekking',\LoginApi::class.':ValidarMozo');
        
        //$this->post('/login', \UserApi::class . ':CargarUno');

    })->add();

    $app->run();

?>
