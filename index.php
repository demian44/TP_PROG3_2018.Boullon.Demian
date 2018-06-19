<?php

include_once './elements.php';

$app = new \Slim\App();

$app->group('/order', function () {
    $this->post('/newOrder', \OrderApi::class.':CargarUno');
});

$app->group('/users', function () {
    $this->get('/', function ($request, $response, $args) {
        $nombre = 'termino';
        $response->getBody()->write("$nombre");
    });

    $this->post('/', \UserApi::class.':CargarUno');
})->add(\LoginMiddleware::class.':ValidarToken');

$app->group('/login', function () {
    $this->post('/validateLogin', \TokenApi::class.':Login');

    $this->post('/chekking', \TokenApi::class.':ValidarMozo');
})->add(\LoginMiddleware::class.':checkLoginData');

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
