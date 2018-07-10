<?php
class MesaMiddleware
{
    public function CheckMesaIdSetted($request, $response, $next)
    {
        $parsedBody = $request->getParsedBody();

        if (isset($parsedBody["mesaId"])) {
            $newResponse = $response->withAddedHeader("mesaId", isset($parsedBody["mesaId"]));
            $response = $next($request, $newResponse);

        } else {
            $result = new ApiResponse(REQUEST_ERROR_TYPE::NOEXIST, 'Campo mesaId no cargado.');
            $response->getBody()->write($result->ToJsonResponse());
        }

        return $response;
    }

    public function CheckIfExist($request, $response, $next)
    {
        $parsedBody = $request->getParsedBody();
        $mesaCode = MesaRepository::GetCodeById($parsedBody["mesaId"]);
        if ($mesaCode != null) {
            $newResponse = $response->withAddedHeader("mesaCode", $mesaCode);
            $response = $next($request, $newResponse);
        } else {
            $result = new ApiResponse(REQUEST_ERROR_TYPE::NOEXIST, 'Id inexistente no cargado.');
            $response->getBody()->write($result->ToJsonResponse());
        }

        return $response;
    }
    public function ValidarFromTo($request, $response, $next)
    {
        $parsedBody = $request->getParsedBody();
        if (isset($parsedBody["to"]) && isset($parsedBody["from"]) &&
            isset($parsedBody["mesaId"])) {

            $to = $parsedBody["to"];
            $from = $parsedBody["from"];
            $id = $parsedBody["mesaId"];
            $fecha = explode("/", $to);
            $fechaFrom = explode("/", $from);
            if (checkdate($fecha[1], $fecha[2], $fecha[0]) &&
                checkdate($fechaFrom[1], $fechaFrom[2], $fechaFrom[0]) &&
                is_numeric($id)) {
                $response = $next($request, $response);
            } else {
                $result = new ApiResponse(REQUEST_ERROR_TYPE::NOEXIST,
                    'FORMATO admitido 2018/07/15 y mesaId debe ser numerico');
                $response->getBody()->write($result->ToJsonResponse());
                
            }
            
        }else{
            $result = new ApiResponse(REQUEST_ERROR_TYPE::NOEXIST,
                'Cargar datos');
            $response->getBody()->write($result->ToJsonResponse());
            
        }

        return $response;
    }

}
