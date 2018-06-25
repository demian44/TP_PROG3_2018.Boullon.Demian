<?php

class TokenApi extends TokenRepository implements IApiUsable
{
    public function TraerUno($request, $response, $args)
    {
    }

    public function Ver($request, $response, $args)
    {
        $response->getBody()->write('Hola');
    }

    public function TraerTodos($request, $response, $args)
    {
    }

    public function Login($request, $response, $args)
    {
        $headers = $request->getHeaders();

        $parsedBody = $request->getParsedBody();
        $user = new User(
            '',
            $parsedBody['user'],
            $parsedBody['password'],
            ""
        );

        $loginResponse = $this->CheckUser($user); // Obtengo un ApiResponse

        if ($loginResponse->Succes()) { // Metodo devuelve true si no hay error
            $token = array(
                "perfil" => $loginResponse->GetResponse(),
                'exp' => time() + 6000, // La sesión dura 10 minutos.
                'nbf' => time(),
            );

            $securityToken = new SecurityToken();
            try {
                $encodedToken = $securityToken->Encode($token);
                // $responseToken = $headers['HTTP_TOKEN']; // Guardo el token en el header
                // $headers['category'] = $loginResponse->GetElement()['category'];
                // $request->withAddedHeader('Category', $responseToken);  // Setteo en el header el tipo
                // $newResponse = $responseToken;
                $loginResponse->SetResponse($encodedToken);
            } catch (Exception $excption) {
                $loginResponse = new ApiResponse(REQUEST_ERROR_TYPE::TOKEN, "Error al generar token");
            }
        } else {
            $result = json_encode([REQUEST_ERROR_TYPE::TOKEN, $loginResponse->GetMessege()]);
        }

        $response->getBody()->write($loginResponse->ToJsonResponse());
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
        ///Este nivel de abstraccion no es necesario, mejor resolverlo en MIddleware
        try {
            $header = $request->getHeader('token');
            $tk = new SecurityToken();
            $decodedUser = $tk->Decode($header[0]);
            $response = $next($request, $response);

        } catch (BeforeValidException $exception) {
            $loginResponse = new ApiResponse(REQUEST_ERROR_TYPE::TOKEN, $exception->getMessage());
        } catch (ExpiredException $exception) {
            $loginResponse = new ApiResponse(REQUEST_ERROR_TYPE::TOKEN, $exception->getMessage());
        } catch (SignatureInvalidException $exception) {
            $loginResponse = new ApiResponse(REQUEST_ERROR_TYPE::TOKEN, $exception->getMessage());
        } catch (Exception $exception) {
            $loginResponse = new ApiResponse(REQUEST_ERROR_TYPE::TOKEN, $exception->getMessage());
        }

        if (!$loginResponse->Succes()) {
            return $response->getBody()->write($loginResponse->ToJsonResponse());
        }

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
