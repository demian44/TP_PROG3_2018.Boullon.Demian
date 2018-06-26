<?php

const PROYECT_NAME = '/Parcial_Programacion_3/';
include_once './elements.php';

$app = new \Slim\App();

$app->group('/media', function () {

    /////////////////////////////    MEDIAS

    $this->post('/newMedia', \MediaApi::class . ':CargarUno')
        ->add(\MediaMiddleware::class . ':CheckCarga');

    $this->delete('/', \MediaApi::class . ':BorrarUno')
        ->add(\MediaMiddleware::class . ':ExisteId')
        ->add(\MediaMiddleware::class . ':IdCargado')
        ->add(\LoginMiddleware::class . ':ValidarDuenio');

    $this->get('/', \MediaApi::class . ':TraerTodos')
        ->add(\MediaMiddleware::class . ':FiltrarRespuesta');

    /////////////////////////////    VENTAS

    $this->post('/vender', \VentaApi::class . ':CargarUno')
        ->add(\VentaMiddleware::class . ':ExisteMediaId')
        ->add(\VentaMiddleware::class . ':CheckCarga')
        ->add(\LoginMiddleware::class . ':ValidarEncargadoEncargado');

    $this->put('/venderEdit', \VentaApi::class . ':ModificarUno')
        ->add(\VentaMiddleware::class . ':ExisteVenta')
        ->add(\VentaMiddleware::class . ':CheckCargaEdit')
        ->add(\LoginMiddleware::class . ':ValidarEncargado');

})->add(\LoginMiddleware::class . ':ValidarToken');

/////////////////////////////    USUARIOS

$app->group('/users', function () {

    $this->post('/', \UserApi::class . ':CargarUno')
        ->add(\UserMiddleware::class . ':UserRepetido')
        ->add(\UserMiddleware::class . ':CheckUserData');
    $this->get('/', \UserApi::class . ':TraerTodos');
    $this->put('/', \UserApi::class . ':Editar')
        ->add(\UserMiddleware::class . ':CheckUserEdit');

    $this->delete('/', \UserApi::class . ':Borrar')
        ->add(\UserMiddleware::class . ':ExisteId')
        ->add(\UserMiddleware::class . ':IdCargado')
        ->add(\LoginMiddleware::class . ':ValidarDuenio');

})->add(\LoginMiddleware::class . ':ValidarToken');

$app->group('/login', function () {

    /////////////////////////////    Login

    $this->post('/validateLogin', \TokenApi::class . ':Login');

    $this->post('/chekking', \TokenApi::class . ':ValidarToken');

})->add(\LoginMiddleware::class . ':checkLoginData');

$app->run();
