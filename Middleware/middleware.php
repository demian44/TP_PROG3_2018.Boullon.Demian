<?php
 class SocioMiddleware 
 {
    public static function Validar($request, $response, $next){
        $response->getBody()->write('BEFORE');
        $response = $next($request, $response);
        $response->getBody()->write('AFTER');
        return $response;
    }
 }

?>