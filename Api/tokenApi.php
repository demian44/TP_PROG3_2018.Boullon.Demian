<?php

class TokenApi extends TokenRepository implements IApiUsable
{
    public function GetOne($request, $response, $args)
    {
        echo "Llego";
    }

    public function Ver($request, $response, $args)
    {
        $response->getBody()->write('Hola');
    }

    public function GetAll($request, $response, $args)
    {
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

        $loginResponse = $this->CheckUser($user);
        if ($loginResponse->GetElement()['succesToken']) {
            $token = array(
                'category' => $loginResponse->GetElement()['category'], //Tipo de usuario
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

    public function CargarUno($request, $response, $args)
    {
    }

    public function BorrarUno($request, $response, $args)
    {
    }

    public function ModificarUno($request, $response, $args)
    {
    }

    public function ValidarToken($request, $response, $next)
    {
        $return = false;
        try {
            $header = $request->getHeader('token');
            $tk = new SecurityToken();

            if (count($header) > 0) {
                $decodedUser = $tk->Decode($header[0]);
                $newResponse = $response->withAddedHeader("category", $decodedUser->category);
                
                $return = true;
            } else {
                $apiResponse = new ApiResponse(REQUEST_ERROR_TYPE::TOKEN, "Falta token");
                $response->getBody()->write($apiResponse->ToJsonResponse());
            }
        } catch (BeforeValidException $exception) {
            $apiResponse = new ApiResponse(REQUEST_ERROR_TYPE::TOKEN, $exception->getMessage());
            $response->getBody()->write($apiResponse->ToJsonResponse());
        } catch (ExpiredException $exception) {
            $apiResponse = new ApiResponse(REQUEST_ERROR_TYPE::TOKEN, $exception->getMessage());
            $response->getBody()->write($apiResponse->ToJsonResponse());
        } catch (SignatureInvalidException $exception) {
            $apiResponse = new ApiResponse(REQUEST_ERROR_TYPE::TOKEN, $exception->getMessage());
            $response->getBody()->write($apiResponse->ToJsonResponse());
        } catch (Exception $exception) {
            $apiResponse = new ApiResponse(REQUEST_ERROR_TYPE::TOKEN, $exception->getMessage());
            $response->getBody()->write($apiResponse->ToJsonResponse());
        }

        return $return;
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
