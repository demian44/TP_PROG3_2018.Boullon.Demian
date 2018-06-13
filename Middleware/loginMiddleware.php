<?php
 class LoginMiddleware 
 {
    public static function ValidarSocio($request, $response, $next){  
        
        echo "coso";
        $response = $next($request, $response);    
        // if(LoginApi::ValidarSocio()){
        //     $response = $next($request, $response);    
        // }
        // else 
        // {
            
        // }
        return $response;
    }
 }

?>