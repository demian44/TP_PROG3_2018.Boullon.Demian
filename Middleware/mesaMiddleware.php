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

}
