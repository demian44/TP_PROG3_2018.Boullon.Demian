<?php

class TokenApi
{
    public function Ver($request, $response, $args)
    {
        $response->getBody()->write('Hola');
    }

    public function Login($request, $response, $args)
    {
        $newResponse = ['Usuario inexistente', -1];

        $headers = $request->getHeaders();

        $parsedBody = $request->getParsedBody();
        $user = new User(
            '',
            $parsedBody['user'],
            $parsedBody['password'],
            ''
        );

        $loginResponse = new InternalResponse();

        $loginResponse = TokenRepository::CheckUser($user);
        if ($loginResponse->GetElement()['succesToken']) {

            $token = array(
                'category' => $loginResponse->GetElement()['category'], //Tipo de usuario
                'user' => $user->GetUser(), // usuario
                'exp' => time() + 60000, // La sesión dura 10 minutos.
                'nbf' => time(),
            );

            $securityToken = new SecurityToken();
            try {
                $headers['HTTP_TOKEN'] = $securityToken->Encode($token);
                $responseToken = $headers['HTTP_TOKEN']; // Guardo el token en el header
                $headers['category'] = $loginResponse->GetElement()['category'];
                $request->withAddedHeader('Category', $responseToken); // Setteo en el header el tipo
                $newResponse = $responseToken;
                $result = json_encode([REQUEST_ERROR_TYPE::NOERROR, $responseToken]);
            } catch (Exception $excption) {
                $result = [-1, $excption->getMessage()];
            }
        } else {
            $result = json_encode([REQUEST_ERROR_TYPE::TOKEN, $loginResponse->GetMessege()]);
        }

        $response->getBody()->write($result);
    }

   

   

    public function ValidarMozo($request, $response, $args)
    {
        $return = false;

        if ($headers['category'] == Category::MOZO) {
            $return = true;
        }

        return $return;
    }

    public function ValidarSocio($request, $response, $args)
    {
        $return = false;

        if ($headers['category'] == Category::SOCIO) {
            $return = true;
        }

        return $return;
    }
}
