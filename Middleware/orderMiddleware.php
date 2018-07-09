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
        if (isset($parsedBody['orderItems']) && isset($parsedBody['clientName']) &&
            isset($parsedBody['mesaId'])) {

            if (isset($file['foto']) && $file['foto']->getError()) {
                $result = new ApiResponse(REQUEST_ERROR_TYPE::NODATA, 'Foto corrompida.');
                $response->getBody()->write($result->ToJsonResponse());
            } else {

                $orderItems = json_decode($parsedBody['orderItems']);
                $orderGood = false;
                if (is_array($orderItems)) {
                    foreach ($orderItems as $value) {
                        $orderGood = true;
                        if (!(property_exists($value, "id") && property_exists($value, "cant"))) {
                            $orderGood = false;
                            break;
                        }
                    }
                }

                if ($orderGood) {
                    $response = $next($request, $response);
                } else {
                    $result = new ApiResponse(REQUEST_ERROR_TYPE::NODATA, 'Error formato orderItems');
                    $response->getBody()->write($result->ToJsonResponse());
                }

            }
        } else {
            $messege = "Datos faltantes: ";
            if (!isset($parsedBody['orderItems'])) {
                $messege .= " orderItems ";
            }
            if (!isset($parsedBody['clientName'])) {
                $messege .= "clientName ";
            }
            if (!isset($parsedBody['mesaId'])) {
                $messege .= " mesaId ";
            }

            $response->getBody()->write(
                (new ApiResponse(REQUEST_ERROR_TYPE::NODATA, $messege))->ToJsonResponse());
        }
        return $response;
    }

    public function CheckTakedOrders($request, $response, $next)
    {
        $flag = false;
        $parsedBody = $request->getParsedBody();
        $file = $request->getUploadedFiles();
        $destino = './fotos/';
        $mensaje = 'Faltan datos';
        $array = [];

        if (isset($parsedBody['orderItems']) && json_decode($parsedBody['orderItems']) != null) {
            $orderItems = json_decode($parsedBody['orderItems']);

            foreach ($orderItems as $value) {
                if (property_exists($value, "id") && property_exists($value, "stimatedTime")) {
                    array_push($array, $value);
                }
            }
            //Validamos y cargamos en un arrat a los orderItems;
            $newResponse = $response->withAddedHeader("orderItems", json_encode($array));
            $response = $next($request, $newResponse);
        } else {
            $response->getBody()->write(
                (new ApiResponse(REQUEST_ERROR_TYPE::NODATA, 
                "Cargar orderItems con comilla doble (ej:[{'id':2,'stimatedTime':15},{'id':2,'stimatedTime':20}"))->ToJsonResponse());

        }

        return $response;
    }

    public function CheckUserTaking($request, $response, $next)
    {
        $user = $response->getHeader("userInfo");
        if ($user[0] != USER_CATEGORY::MOZO) {
            $response = $next($request, $response);
        } else {
            $apiResponse = new ApiResponse(REQUEST_ERROR_TYPE::GENERAL, "Mozo no hace pedido");
            $response->getBody()->write($apiResponse->ToJsonResponse());
        }

        return $response;
    }

    public function CheckOrder($request, $response, $next)
    {
        $flag = false;
        $parsedBody = $request->getParsedBody();
        if (isset($parsedBody["orderId"])) {
            $file = $request->getUploadedFiles();
            $newResponse = $response->withAddedHeader("orderId", $parsedBody["orderId"]);
            $response = $next($request, $newResponse);
        } else {
            $response->getBody()->write("Falta cargar el id de la orden");
        }

        return $response;
    }

    public function ExistOrderItems($request, $response, $next)
    {
        $orderItems = $response->getHeader("orderItems");
        $user = $response->getHeader("userInfo");
        $orderItems = json_decode($orderItems[0]);
        // Vamos a chequear los orderItems para ver si existen y si no fueron tomados
        $problem = "";
        if (OrderRepository::CheckOrderItems($orderItems, $user[0]/*category*/, $problem)) {
            $response = $next($request, $response);
        } else {
            $result = new ApiResponse(REQUEST_ERROR_TYPE::NOEXIST,
                $problem);
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

        if (isset($parsedBody['id']) && isset($parsedBody['idMedia']) && isset($parsedBody['clientName']) &&
            isset($parsedBody['fecha'])) {

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
        $orderItems = json_decode($parsedBody['orderItems']);
        $idItems = array_map(function ($item) {
            return $item->id;
        }, $orderItems);

        //  $orderItems
        $itemsFaltantes = OrderRepository::CheckItems($idItems);
        if (count($itemsFaltantes)) {
            $result = new ApiResponse(REQUEST_ERROR_TYPE::NOEXIST,
                'Faltan ids: ' . json_encode($itemsFaltantes));
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
