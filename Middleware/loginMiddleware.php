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
        ///Implementar middleware así
        try {
            $header = $request->getHeader('token');
            $tk = new SecurityToken();

            if (count($header) > 0) {
                $decodedUser = $tk->Decode($header[0]);

                $newResponse = $response->withAddedHeader("category", $decodedUser->category);

                $response = $next($request, $newResponse);

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

        return $response;
    }
    
    public function ValidarSocio($request, $response, $next)
    {
        $response = Self::ValidarUser($request, $response, $next, USER_TYPE::SOCIO, "socio");
        return $response;
    }
    public function ValidarMozo($request, $response, $next)
    {
        $response = Self::ValidarUser($request, $response, $next, USER_TYPE::MOZO, "mozo");
        return $response;
    }
    public function ValidarBarTender($request, $response, $next)
    {
        $response = Self::ValidarUser($request, $response, $next, USER_TYPE::BARTENDER, "bartender");
        return $response;
    }
    public function ValidarCerbecero($request, $response, $next)
    {
        $response = Self::ValidarUser($request, $response, $next, USER_TYPE::CERBECERO, "cerbecero");
        return $response;
    }
    public function ValidarCocinero($request, $response, $next)
    {
        $response = Self::ValidarUser($request, $response, $next, USER_TYPE::COCINERO, "cocinero");
        return $response;
    }
    private static function ValidarUser($request, $response, $next, $user, $userName)
    {
        $header = $response->getHeader("category");
        if ($header[0] == $user) {
            $response = $next($request, $response);
        } else {
            $response->getBody()->write((new ApiResponse(REQUEST_ERROR_TYPE::TOKEN,
                "no es $userName"))->toJsonResponse());
        }
        return $response;
    }

    public function ValidarEncargadoEncargado($request, $response, $next)
    {
        $header = $response->getHeader("category");
        if ($header[0] == "empleado" || $header[0] == "encargado") {
            $response = $next($request, $response);
        } else {
            $response->getBody()->write((new ApiResponse(REQUEST_ERROR_TYPE::TOKEN,
                "no es encargado ni empleado"))->toJsonResponse());

        }

        return $response;
    }
    public function ValidarEncargado($request, $response, $next)
    {
        $header = $response->getHeader("perfil");
        if ($header[0] == "encargado") {
            $response = $next($request, $response);
        } else {

            $response->getBody()->write(
                (new ApiResponse(REQUEST_ERROR_TYPE::TOKEN, "no es encargado"))->toJsonResponse());

        }

        return $response;
    }

}
