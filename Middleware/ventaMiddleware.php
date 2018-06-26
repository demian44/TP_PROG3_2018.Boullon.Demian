<?php
class VentaMiddleware
{
    public function CheckCarga($request, $response, $next)
    {

        $flag = false;
        $parsedBody = $request->getParsedBody();
        $file = $request->getUploadedFiles();
        $destino = './fotos/';
        $mensaje = 'Faltan datos';
        if (isset($parsedBody['idMedia']) && isset($parsedBody['nombreCliente']) &&
            isset($parsedBody['fecha']) && isset($parsedBody['importe'])) {

            $flag = true;
        }

        $date = explode("/", $parsedBody['fecha']);
        if ($flag && count($date) != 3) {
            $flag = false;
            $mensaje = 'Formato fecha invalido (debe ser 00/00/000)';
        }

        if ($flag && isset($file['foto'])) {

            if (!$file['foto']->getError()) {

                $response = $next($request, $response);

            } else {
                $result = new ApiResponse(REQUEST_ERROR_TYPE::NODATA, 'Foto corrompida.');
                $response->getBody()->write($result->ToJsonResponse());
            }

        } else {
            $result = new ApiResponse(REQUEST_ERROR_TYPE::NODATA, $mensaje);
            $response->getBody()->write($result->ToJsonResponse());
        }

        return $response;
    }

    public function CheckCargaEdit($request, $response, $next)
    {
        $flag = false;
        $parsedBody = $request->getParsedBody();
        $file = $request->getUploadedFiles();
        $destino = './fotos/';
        $mensaje = 'Faltan datos';

        if (isset($parsedBody['id']) && isset($parsedBody['idMedia']) && isset($parsedBody['nombreCliente']) &&
            isset($parsedBody['fecha']) && isset($parsedBody['importe'])) {

            $flag = true;
        }

        $date = explode("/", $parsedBody['fecha']);

        if ($flag && count($date) != 3) {
            $flag = false;
            $mensaje = 'Formato fecha invalido (debe ser 00/00/000)';
        }

        if ($flag) {
            $response = $next($request, $response);
        } else {
            $result = new ApiResponse(REQUEST_ERROR_TYPE::NODATA, $mensaje);
            $response->getBody()->write($result->ToJsonResponse());
        }

        return $response;
    }

    public function ExisteMediaId($request, $response, $next)
    {
        $parsedBody = $request->getParsedBody();

        $media = MediaRepository::TraerPorId($parsedBody['idMedia']);
        if (!is_null($media)) {
            $response = $next($request, $response);

        } else {
            $result = new ApiResponse(REQUEST_ERROR_TYPE::NOEXIST, 'No existe la media.');
            $response->getBody()->write($result->ToJsonResponse());
        }

        return $response;
    }

    public function ExisteVenta($request, $response, $next)
    {
        $parsedBody = $request->getParsedBody();

        $venta = VentaRepository::TraerPorId($parsedBody['id']);
        if (!is_null($venta)) {
            $array = [$venta->GetFoto(), $venta->GetId()];
            $newResponse = $response->withAddedHeader("venta", $array);
            $response = $next($request, $newResponse);
        } else {
            $result = new ApiResponse(REQUEST_ERROR_TYPE::NOEXIST, 'No existe la venta.');
            $response->getBody()->write($result->ToJsonResponse());
        }

        return $response;
    }

}
