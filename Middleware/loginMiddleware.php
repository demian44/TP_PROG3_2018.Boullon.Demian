<?php
 class LoginMiddleware
 {
     public function checkLoginData($request, $response, $next)
     {
         $parsedBody = $request->getParsedBody();
         $flag = true;
         $errorMessege = '';
         if (!isset($parsedBody['user'])) {
             $flag = false;
             $errorMessege = 'Falta el campo usuario'; // Este campo debería ser chequeado en front.
         }
         if (!isset($parsedBody['password'])) {
             if (!$flag) {
                 $errorMessege .= 'y password';
             } else {
                 $errorMessege = 'Falta el campo password';
                 $flag = false;
             }
         }
         if ($flag) {
             $response = $next($request, $response);
         } else {
             $response->getBody()->write(json_encode([-1, $errorMessege]));
         }

         return $response;
     }

     public function ValidarToken($request, $response, $next)
     {
         try {
             $tokenApi = new TokenApi();
             if ($tokenApi->ValidarToken($request, $response, $next)) {
                 $response = $next($request, $response);
             } else {
                 $response->getBody()->write('Error');
             }
         } catch (Exception $exception) {
             $response->getBody()->write($exception->getMessage());
         }

         return $response;
     }
 }
