<?php

require './vendor/autoload.php';

include "./IApiUsable.php";
include "./InternalResponse.php";

//Entidades
include "./Model/pedido.php";
include "./Model/usuario.php";
include "./Model/TIPOS.php";

//Repository 
include "./Repository/AccesoDatos.php";
include "./Repository/pedidoRepository.php";

//Api
include "./api/pedidoApi.php";
include "./api/loginApi.php";

require './vendor/autoload.php';

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \Firebase\JWT\JWT;


try
{

    $app = new \Slim\App;
    
    $app->group('/pedidos',function(){
        $this->get('/{nombre}',function($request,$response,$args){
            $nombre = $args['nombre'];
            $response->getBody()->write("Hola $nombre");
        });
    
        
        $this->post('/', \PedidoApi::class . ':cargarUno');
        
    });
    
    $app->group('/usuario',function(){
        // $this->get('/',function($request,$response,$args){
        //     $usuario = new Empleado;
        //     //$usuario->tipo = ;
        //     $usuario->SetTipo(TIPO_USUARIO::SOCIO);
            
        //     $response->getBody()->write("We".$usuario->GetTipo());
        // });


      
        $this->post('/', \PedidoApi::class . ':cargarUno');
        
        
    });
    $app->group('/login',function(){       

        $this->post('/', \LoginApi::class . ':GenerarTokken');
        
        $this->post('/probar', \LoginApi::class . ':ProbarTokken');
    });

    
    
    $app->run();

}catch(Exception $exception){
    echo("coso:".$exception->getMessage());
}
?>
