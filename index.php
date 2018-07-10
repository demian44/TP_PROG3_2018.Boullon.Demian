<?php

include_once './elements.php'; //Links a los arvhicos del proyecto.

$app = new \Slim\App();

$app->group('/order', function () {

    /**
     * Ingresa una nueva orden. Cambia el estado de la mesa a CON_CLIENTE_ESPERANDO_PEDIDO
     */
    $this->post('/new', \OrderApi::class . ':Set')
        ->add(\OrderMiddleware::class . ':ExistAllItems')
        ->add(\MesaMiddleware::class . ':EstaLibre')
        ->add(\MesaMiddleware::class . ':CheckIfExist')
        ->add(\OrderMiddleware::class . ':CheckCarga')
        ->add(\LoginMiddleware::class . ':ValidarMozo');

    /**
     * Tomar uno o varios items de un pedido. Los mozos no pueden acceder.
     */
    $this->post('/takeOrder', \OrderApi::class . ':TakeOrder')
        ->add(\OrderMiddleware::class . ':ExistOrderItems')
        ->add(\OrderMiddleware::class . ':CheckTakedOrders')
        ->add(\OrderMiddleware::class . ':CheckUserTaking'); //Distinto de mozo

    /**
     * Cambia el estado de los itmes que se están preparando a Ready (Filtra por usuario).
     */
    $this->post('/resolvePendings', \OrderApi::class . ':ResolvePendings')
        ->add(\OrderMiddleware::class . ':ExistOrderItemsToResolve')
        ->add(\OrderMiddleware::class . ':CheckTakedOrdersResolve');

    /**
     * Solo los mozos (Aunque los socios tienen acceso a todo).
     * Cambia el estado de un pedido de Ready a Delivered(entregado).
     * También edita el esado de la mesa a CON_CLIENTES_COMIENDO
     */
    $this->post('/deliverOrder', \OrderApi::class . ':DeliverOrder')
        ->add(\OrderMiddleware::class . ':CheckOrder')
        ->add(\LoginMiddleware::class . ':ValidarMozo');

    /**
     * Obiene pendientes items que se están preparando según el empleado que lo solicita.(Por token)
     */
    $this->get('/Pendings', \OrderApi::class . ':GetPendings');

    /**
     * Obtiene todos los pedidos con sus items.
     * Si es mozo solo visualiza los que están listos para entregar
     * Si es cocinero, bartender, etc solo ve los que estan disponibles para tomar.
     * Si es Socio ve todos los pedidos.
     */
    $this->get('', \OrderApi::class . ':GetAll');

})->add(\LoginMiddleware::class . ':ValidarToken');

$app->group('/users', function () {

    /*
     * Solo socios -> Carga un empleado.
     */
    $this->post('', \UserApi::class . ':CargarUno')
        ->add(\UserMiddleware::class . ':UserRepetido')
        ->add(\UserMiddleware::class . ':CheckUserData');
})->add(\LoginMiddleware::class . ':ValidarToken');

$app->group('/informes', function () {

    /**
     * Obtener informes.
     */
    $this->get('/allWithInfo', \UserApi::class . ':GetAllWithInfo');
    $this->get('/dayAndHourEntry', \UserApi::class . ':DayAndHourEntry');
    $this->get('/sectorOperation', \UserApi::class . ':SectorOperation');
    $this->post('/sectorOperation', \UserApi::class . ':GetBySectorOperation');
    $this->get('/resumenPedidos', \OrderApi::class . ':ResumenPedidos');
    $this->get('/resumenMesas', \MesaApi::class . ':ResumenMesas');
    $this->post('/facturadoEntreFechas', \MesaApi::class . ':FacturadoEntreFechas')
        ->add(\MesaMiddleware::class . ':ValidarFromTo');

})->add(\LoginMiddleware::class . ':ValidarSocio')
    ->add(\LoginMiddleware::class . ':ValidarToken');

$app->group('/mesas', function () {
    /**
     * Cargar una mesa. Solo socios.
     */
    $this->post('', \MesaApi::class . ':CargarUno')
        ->add(\LoginMiddleware::class . ':ValidarSocio');
    /**
     * Ver todas las mesas socio y mozos.
     */
    $this->get('', \MesaApi::class . ':GetAll')
        ->add(\LoginMiddleware::class . ':ValidarMozo');

    /**
     * Setear estado de la mesa en esperando pedido.
     */
    $this->post('/waiting', \MesaApi::class . ':Waiting')
        ->add(\MesaMiddleware::class . ':CheckMesaIdSetted')
        ->add(\LoginMiddleware::class . ':ValidarMozo');
    /**
     * Setear estado de la mesa en esperando comiento.
     */
    $this->post('/eating', \MesaApi::class . ':Eating')
        ->add(\MesaMiddleware::class . ':CheckMesaIdSetted')
        ->add(\LoginMiddleware::class . ':ValidarMozo');
    /**
     * Setear estado de la mesa en esperando pagando.
     */
    $this->post('/paying', \MesaApi::class . ':paying')
        ->add(\MesaMiddleware::class . ':CheckMesaIdSetted')
        ->add(\LoginMiddleware::class . ':ValidarMozo');
    /**
     * Setear estado de la mesa en esperando cerrado.
     */
    $this->post('/close', \MesaApi::class . ':close')
        ->add(\MesaMiddleware::class . ':CheckMesaIdSetted')
        ->add(\LoginMiddleware::class . ':ValidarSocio');

})->add(\LoginMiddleware::class . ':ValidarToken');

$app->group('/items', function () {

    /**
     * Cargar un pedido, puede ser comida, cerbeza, trago, postre.
     */
    $this->post('', \ItemApi::class . ':CargarUno')
        ->add(\ItemMiddleware::class . ':CheckData')
        ->add(\LoginMiddleware::class . ':ValidarSocio');

    /**
     * Ver todas las comidas y bebidas.
     */
    $this->get('', \ItemApi::class . ':GetAll')
        ->add(\LoginMiddleware::class . ':ValidarMozo');

})->add(\LoginMiddleware::class . ':ValidarToken');

$app->group('/login', function () {

    /**
     * Login.... Devuelve token con usuario y categoria y un tiempo de expiracion
     */
    $this->post('/validateLogin', \TokenApi::class . ':Login');

    /**
     * Metodo de prueba.
     */
    $this->post('/chekking', \TokenApi::class . ':GetOne')
        ->add(\LoginMiddleware::class . ':ValidarSocio')
        ->add(\LoginMiddleware::class . ':ValidarToken');

})->add(\LoginMiddleware::class . ':checkLoginData');

/**
 * Este metodo requiere los codigos y permite chequear l tiempo estimado del pedido.
 * En caso de ser sobrepsado el tieempo estimado o no estar disponible devuelve 
 * el texto "En instantes...".
 */
$app->post('/checkOrder', \OrderApi::class . ':GetStateOrder');

/**
 * Este metodo lo hice para chequear  los pedidos que están para ser evaluados. 
 */
$app->post('/orderInfoToEvaluate', \OrderApi::class . ':GetOrderInfoToEvaluate');


/**
 * Requiere varios campos que están explicados en el archivo ejemplo en la carpeta del proyecto.
 * Los campos son referentes a la evaluacion del restaurante, cocineros y la mesa. 
 */
$app->post('/setEvaluation', \OrderApi::class . ':SetEvaluation')
    ->add(\OrderMiddleware::class . ':ExisteEvaluacion')
    ->add(\OrderMiddleware::class . ':ValidarEvaluacionCocineros')
    ->add(\OrderMiddleware::class . ':ValidarEvaluacion');



$app->run();
