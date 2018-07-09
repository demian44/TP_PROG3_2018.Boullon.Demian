<?php

include_once './elements.php';

$app = new \Slim\App();

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
})->add(\LoginMiddleware::class . ':ValidarToken');


$app->group('/informes', function () {

    $this->get('/allWithInfo', \UserApi::class . ':GetAllWithInfo');

    $this->get('/dayAndHourEntry', \UserApi::class . ':DayAndHourEntry');
    
    $this->get('/sectorOperation', \UserApi::class . ':SectorOperation');
    
    $this->post('/sectorOperation', \UserApi::class . ':GetBySectorOperation');
    
    $this->get('/resumenPedidos', \OrderApi::class . ':ResumenPedidos');

})->add(\LoginMiddleware::class . ':ValidarSocio')
    ->add(\LoginMiddleware::class . ':ValidarToken');



$app->get('/checkOrder', \OrderApi::class . ':GetStateOrder');
$app->get('/orderInfoToEvaluate', \OrderApi::class . ':GetOrderInfoToEvaluate');
$app->post('/setEvaluation', \OrderApi::class . ':SetEvaluation');

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
