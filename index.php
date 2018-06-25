<?php

const PROYECT_NAME = '/Parcial_Programacion_3/';
include_once './elements.php';

$app = new \Slim\App();

$app->group('/media', function () {

    $this->post('/newMedia', \MediaApi::class . ':CargarUno')->add(\MediaMiddleware::class . ':CheckCarga');
    $this->delete('/', \MediaApi::class . ':BorrarUno')->add(\MediaMiddleware::class . ':ValidarDelete')->add(\LoginMiddleware::class . ':ValidarDueño');
    $this->get('/', \MediaApi::class . ':TraerTodos');

})->add(\LoginMiddleware::class . ':ValidarToken');

/////////////////////////////    USUARIOS

$app->group('/users', function () {

    $this->post('/', \UserApi::class . ':CargarUno')->add(\UserMiddleware::class . ':CheckUserData');
    $this->get('/', \UserApi::class . ':TraerTodos');

})->add(\LoginMiddleware::class . ':ValidarToken');

///////////////////////////   END USUARIOS

$app->group('/login', function () {

    $this->post('/validateLogin', \TokenApi::class . ':Login');

    $this->post('/chekking', \TokenApi::class . ':ValidarToken');

})->add(\LoginMiddleware::class . ':checkLoginData');

$app->run();

/*
<?php
$datetime1 = new DateTime('2009-10-11');
$datetime2 = new DateTime('2009-10-13');
$interval = $datetime1->diff($datetime2);
echo $interval->format('%R%a días');
$date = date('Y/m/d H:i');

$nuevaHora = date('Y/m/d H:i', time() + 600);
echo "\n";
echo "\n";
echo "\n";
echo "primera fecha: $date";
echo "\n";
echo "nueva fecha: $nuevaHora";
echo "\n";
echo "\n";
echo "\n";
?>
 */
