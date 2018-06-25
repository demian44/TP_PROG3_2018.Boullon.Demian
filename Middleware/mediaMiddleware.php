<?php
class MediaMiddleware
{
    public function CheckCarga($request, $response, $next)
    {
        $flag = false;
        $parsedBody = $request->getParsedBody();
        $file = $request->getUploadedFiles();
        $destino = './fotos/';

        if (isset($parsedBody['color']) && isset($parsedBody['marca']) &&
            isset($parsedBody['talle']) && isset($parsedBody['precio'])) {
            $flag = true;
        }

        if ($flag && isset($file['foto'])) {

            if (!$file['foto']->getError()) {
                $response = $next($request, $response);
            } else {
                $result = new ApiResponse(REQUEST_ERROR_TYPE::NODATA, 'Foto corrompida.');
                $response->getBody()->write($result->ToJsonResponse());
            }

        } else {
            $result = new ApiResponse(REQUEST_ERROR_TYPE::NODATA, 'Faltan datos');
            $response->getBody()->write($result->ToJsonResponse());
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

    public function ValidarDelete($request, $response, $next)
    {
        $parsedBody = $request->getParsedBody();

        if (isset($parsedBody['id'])) {
            $response = $next($request, $response);
        } else {

            $result = new ApiResponse(REQUEST_ERROR_TYPE::NODATA, 'Faltan datos');
            $response->getBody()->write($result->ToJsonResponse());
        }

        return $response;
    }
}
