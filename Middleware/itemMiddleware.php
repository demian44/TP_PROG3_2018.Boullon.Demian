<?php
class ItemMiddleware
{
    public function CheckData($request, $response, $next)
    {
        $parsedBody = $request->getParsedBody();
        if (isset($parsedBody["name"]) && isset($parsedBody["sector"]) &&
            isset($parsedBody["precio"])) {

            if (is_numeric($parsedBody["sector"]) && floatval($parsedBody["precio"])) {
                $response = $next($request, $response);
            } else {
                $result = new ApiResponse(REQUEST_ERROR_TYPE::NOEXIST, 'Error de formato en '
                    . 'datos');
                $response->getBody()->write($result->ToJsonResponse());

            }

        } else {
            $result = new ApiResponse(REQUEST_ERROR_TYPE::NOEXIST, 'Faltan campos name, ' .
                'sector, precio');
            $response->getBody()->write($result->ToJsonResponse());
        }

        return $response;
    }

}
