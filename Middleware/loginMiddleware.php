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

                $newResponse = $response->withAddedHeader("perfil", $decodedUser->perfil);

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
    public function ValidarDuenio($request, $response, $next)
    {
        $header = $response->getHeader("perfil");
        if ($header[0] == "dueño") {
            $response = $next($request, $response);
        } else {

            $response->getBody()->write(
                (new ApiResponse(REQUEST_ERROR_TYPE::TOKEN, "no es duenio"))->toJsonResponse());

        }

        return $response;
    }
    public function ValidarEncargadoEncargado($request, $response, $next)
    {
        $header = $response->getHeader("perfil");
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
