<?php

include_once './elements.php';

$app = new \Slim\App();
/**
 * 
 * IMPORTANTE AGREGUE TIEMPO ESTIMADO POR ITEM, EMPLEADO A CARGO Y ESTADO.
 * 
 */
$app->group('/order', function () {
    $this->post('/new', \OrderApi::class . ':CargarUno')
        ->add(\OrderMiddleware::class . ':ExistAllItems')
        ->add(\MesaMiddleware::class . ':ValidarMesa')
        ->add(\OrderMiddleware::class . ':CheckCarga')
        ->add(\LoginMiddleware::class . ':ValidarMozo');
   
        $this->post('/takeOrder', \OrderApi::class . ':TakeOrder')
        ->add(\OrderMiddleware::class . ':ExistAllItems')
        ->add(\OrderMiddleware::class . ':CheckCarga')
        ->add(\LoginMiddleware::class . ':ValidarMozo');

    $this->get('', \OrderApi::class . ':GetAll');

})->add(\LoginMiddleware::class . ':ValidarToken');

$app->group('/users', function () {

    $this->post('/', \UserApi::class . ':CargarUno')
        ->add(\UserMiddleware::class . ':UserRepetido')
        ->add(\UserMiddleware::class . ':CheckUserData');
    // $this->get('/', \UserApi::class . ':GetAll');
    // $this->put('/', \UserApi::class . ':Editar')
    //     ->add(\UserMiddleware::class . ':CheckUserEdit');

    // $this->delete('/', \UserApi::class . ':Borrar')
    //     ->add(\UserMiddleware::class . ':ExisteId')
    //     ->add(\UserMiddleware::class . ':IdCargado')
    //     ->add(\LoginMiddleware::class . ':ValidarDuenio');

})->add(\LoginMiddleware::class . ':ValidarToken');

$app->group('/mesas', function () {
    $this->post('', \MesaApi::class . ':CargarUno')
        ->add(\LoginMiddleware::class . ':ValidarSocio');
    $this->get('', \MesaApi::class . ':GetAll')
        ->add(\LoginMiddleware::class . ':ValidarSocio');
})->add(\LoginMiddleware::class . ':ValidarToken');

$app->group('/login', function () {
    $this->post('/validateLogin', \TokenApi::class . ':Login');

    $this->post('/chekking', \TokenApi::class . ':GetOne')
        ->add(\LoginMiddleware::class . ':ValidarSocio')
        ->add(\LoginMiddleware::class . ':ValidarToken');

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
