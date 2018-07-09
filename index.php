<?php

include_once './elements.php';

$app = new \Slim\App();
/**
 *
 * IMPORTANTE AGREGUE TIEMPO ESTIMADO POR ITEM, EMPLEADO A CARGO Y ESTADO.
 *
 */
$app->group('/order', function () {
    $this->post('/new', \OrderApi::class . ':Set')
        ->add(\OrderMiddleware::class . ':ExistAllItems')
        ->add(\MesaMiddleware::class . ':CheckIfExist')
        ->add(\OrderMiddleware::class . ':CheckCarga')
        ->add(\LoginMiddleware::class . ':ValidarMozo');

    $this->post('/takeOrder', \OrderApi::class . ':TakeOrder')        
        ->add(\OrderMiddleware::class . ':ExistOrderItems')
        ->add(\OrderMiddleware::class . ':CheckTakedOrders')
        ->add(\OrderMiddleware::class . ':CheckUserTaking');

    $this->post('/resolvePendings', \OrderApi::class . ':ResolvePendings')
        ->add(\OrderMiddleware::class . ':ExistOrderItems')
        ->add(\OrderMiddleware::class . ':CheckTakedOrders');
    //MIDDLEWARE PARA EMPLEADOS QUE PUEDEN VER ESTO

    $this->post('/deliverOrder', \OrderApi::class . ':DeliverOrder')
        ->add(\OrderMiddleware::class . ':CheckOrder')
        ->add(\LoginMiddleware::class . ':ValidarMozo');

    $this->get('/Pendings', \OrderApi::class . ':GetPendings');
    //MIDDLEWARE PARA EMPLEADOS QUE PUEDEN VER ESTO

    $this->get('', \OrderApi::class . ':GetAll');

})->add(\LoginMiddleware::class . ':ValidarToken');

$app->group('/users', function () {

    $this->post('', \UserApi::class . ':CargarUno')
    ->add(\UserMiddleware::class . ':UserRepetido')
    ->add(\UserMiddleware::class . ':CheckUserData');
    
    $this->get('/allWithInfo', \UserApi::class . ':GetAllWithInfo')
    ->add(\LoginMiddleware::class . ':ValidarSocio');
    
    $this->get('/dayAndHourEntry', \UserApi::class . ':DayAndHourEntry')
    ->add(\LoginMiddleware::class . ':ValidarSocio');
    
})->add(\LoginMiddleware::class . ':ValidarToken');

$app->get('/checkOrder', \OrderApi::class . ':GetStateOrder');

$app->group('/mesas', function () {
    $this->post('', \MesaApi::class . ':CargarUno')
        ->add(\LoginMiddleware::class . ':ValidarSocio');
    $this->get('', \MesaApi::class . ':GetAll')
        ->add(\LoginMiddleware::class . ':ValidarSocio');
    $this->put('/waiting', \MesaApi::class . ':Waiting')
        ->add(\MesaMiddleware::class . ':CheckMesaIdSetted')
        ->add(\LoginMiddleware::class . ':ValidarMozo');
    $this->put('/eating', \MesaApi::class . ':Eating')
        ->add(\MesaMiddleware::class . ':CheckMesaIdSetted')
        ->add(\LoginMiddleware::class . ':ValidarMozo');
    $this->put('/paying', \MesaApi::class . ':paying')
        ->add(\MesaMiddleware::class . ':CheckMesaIdSetted')
        ->add(\LoginMiddleware::class . ':ValidarMozo');
    $this->put('/close', \MesaApi::class . ':close')
        ->add(\MesaMiddleware::class . ':CheckMesaIdSetted')
        ->add(\LoginMiddleware::class . ':ValidarSocio');

})->add(\LoginMiddleware::class . ':ValidarToken');

$app->group('/items', function () {
    $this->post('', \ItemApi::class . ':CargarUno')
        ->add(\ItemMiddleware::class . ':CheckData')
        ->add(\LoginMiddleware::class . ':ValidarSocio');
    $this->get('', \ItemApi::class . ':GetAll')
        ->add(\LoginMiddleware::class . ':ValidarMozo');

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
