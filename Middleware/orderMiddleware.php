<?php
class OrderMiddleware
{
    public function CheckCarga($request, $response, $next)
    {
        $flag = false;
        $parsedBody = $request->getParsedBody();
        $file = $request->getUploadedFiles();
        $destino = './fotos/';
        $mensaje = 'Faltan datos';
        if (isset($parsedBody['orderIdItems']) && isset($parsedBody['clientName']) &&
            isset($parsedBody['mesaId']) && isset($parsedBody['importe'])) {

            if (isset($file['foto']) && $file['foto']->getError()) {
                $result = new ApiResponse(REQUEST_ERROR_TYPE::NODATA, 'Foto corrompida.');
                $response->getBody()->write($result->ToJsonResponse());
            } else {
                $response = $next($request, $response);
            }
        } else {
            $messege = "Datos faltantes: ";
            if (!isset($parsedBody['orderIdItems'])) {
                $messege .= " orderIdItems ";
            }
            if (!isset($parsedBody['clientName'])) {
                $messege .= "clientName ";
            }
            if (!isset($parsedBody['mesaId'])) {
                $messege .= " mesaId ";
            }
            if (!isset($parsedBody['importe'])) {
                $messege .= " importe ";
            }
            $response->getBody()->write(
                (new ApiResponse(REQUEST_ERROR_TYPE::NODATA, $messege))->ToJsonResponse());
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

        if (isset($parsedBody['id']) && isset($parsedBody['idMedia']) && isset($parsedBody['clientName']) &&
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

    public function ExistAllItems($request, $response, $next)
    {
        $parsedBody = $request->getParsedBody();
        $orderItems = json_decode($parsedBody['orderIdItems']);
        $idItems = array_map(function ($item) {
            return $item->id;
        },$orderItems);

        //  $orderItems
        $items = OrderRepository::CheckItems($idItems);
        if (count($items)) {
            $result = new ApiResponse(REQUEST_ERROR_TYPE::NOEXIST,
                'Faltan ids: ' . json_encode($items));
            $response->getBody()->write($result->ToJsonResponse());
        } else {
            $response = $next($request, $response);
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
