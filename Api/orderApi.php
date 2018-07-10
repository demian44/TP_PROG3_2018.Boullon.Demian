<?php

class OrderApi
{
    public function GetAll($request, $response, $args)
    {
        try {
            $userInfo = $response->getHeader("userInfo");
            UserActionRepository::SaveByUser("Ver todos los pedidos", $userInfo[1]);
            $result = OrderRepository::GetAll($request->getUri()->getHost() . ':'
                . $request->getUri()->getPort() . PROYECT_NAME, $userInfo[0]);

        } catch (PDOException $exception) {
            $result = new ApiResponse(REQUEST_ERROR_TYPE::DATABASE, $exception->getMessage());
        } catch (Exception $exception) {
            $result = new ApiResponse(REQUEST_ERROR_TYPE::GENERAL, $exception->getMessage());
        }

        $response->getBody()->write($result->ToJsonResponse());
    }

    public function GetStateOrder($request, $response, $args)
    {
        try {

            $parsedBody = $request->getParsedBody();
            $mesaCode = $parsedBody["mesaCode"];
            $orderCode = $parsedBody["orderCode"];

            $result = OrderRepository::GetStateOrder($orderCode, $mesaCode);

        } catch (PDOException $exception) {
            $result = new ApiResponse(REQUEST_ERROR_TYPE::DATABASE, $exception->getMessage());
        } catch (Exception $exception) {
            $result = new ApiResponse(REQUEST_ERROR_TYPE::GENERAL, $exception->getMessage());
        }

        $response->getBody()->write($result->ToJsonResponse());
    }

    public function GetPendings($request, $response, $args)
    {
        try {
            $userInfo = $response->getHeader("userInfo");
            UserActionRepository::SaveByUser("Ver pendientes", $userInfo[1]);
            $result = OrderRepository::GetPendings($request->getUri()->getHost() . ':'
                . $request->getUri()->getPort() . PROYECT_NAME, $userInfo[1]/*User*/);
        } catch (PDOException $exception) {
            $result = new ApiResponse(REQUEST_ERROR_TYPE::DATABASE, $exception->getMessage());
        } catch (Exception $exception) {
            $result = new ApiResponse(REQUEST_ERROR_TYPE::GENERAL, $exception->getMessage());
        }

        $response->getBody()->write($result->ToJsonResponse());
    }

    public function ResolvePendings($request, $response, $args)
    {
        try {
            $parsedBody = $request->getParsedBody();
            $orderItems = $parsedBody["orderItems"];
            $user = $response->getHeader("userInfo");
            $orderItem = json_decode($orderItems);
            $user = $response->getHeader("userInfo");
            UserActionRepository::SaveByUser("Pedido listo", $user[1]);
            $result = OrderRepository::ResolvePending($orderItem);
        } catch (PDOException $exception) {
            $result = new ApiResponse(REQUEST_ERROR_TYPE::DATABASE, $exception->getMessage());
        } catch (Exception $exception) {
            $result = new ApiResponse(REQUEST_ERROR_TYPE::GENERAL, $exception->getMessage());
        }
        $response->getBody()->write($result->ToJsonResponse());

    }

    public function DeliverOrder($request, $response, $args)
    {
        $orderId = $response->getHeader("orderId");
        $userInfo = $response->getHeader("userInfo");
        try {
            UserActionRepository::SaveByUser("Pedido entregado", $userInfo[1]);
            
            $result = OrderRepository::DeliverOder($orderId[0]);
        } catch (PDOException $exception) {
            $result = new ApiResponse(REQUEST_ERROR_TYPE::DATABASE, $exception->getMessage());
        } catch (Exception $exception) {
            $result = new ApiResponse(REQUEST_ERROR_TYPE::GENERAL, $exception->getMessage());
        }
        $response->getBody()->write($result->ToJsonResponse());

    }

    public function CargarUno($request, $response, $args)
    {}

    public function TakeOrder($request, $response, $args)
    {
        try {
            $orderItems = $response->getHeader("orderItems");
            $orderItem = json_decode($orderItems[0]);
            $user = $response->getHeader("userInfo");

            UserActionRepository::SaveByUser("Tomar pedido", $user[1]);
            $userData = UserRepository::GetByUser($user[1]/*user*/);
            $result = OrderRepository::TakeOrder($orderItem, $userData->GetId(), $user[0]/*category*/);
        } catch (PDOException $exception) {
            $result = new ApiResponse(REQUEST_ERROR_TYPE::DATABASE, $exception->getMessage());
        } catch (Exception $exception) {
            $result = new ApiResponse(REQUEST_ERROR_TYPE::GENERAL, $exception->getMessage());
        }
        $response->getBody()->write($result->ToJsonResponse());
    }

    public function Set($request, $response, $args)
    {
        try {
            $count = 0;
            $parsedBody = $request->getParsedBody();
            $existCode = false;
            $orderItemsJson = json_decode($parsedBody['orderItems']);
            $orderItems = [];

            $userInfo = $response->getHeader("userInfo");
            UserActionRepository::SaveByUser("Guardar pedido", $userInfo[1]);
            do {
                $code = Order::generateCode();

                if (!OrderRepository::CheckCodes($code)) {
                    foreach ($orderItemsJson as $key => $orderItemJson) {
                        $orderItem = new OrderItem($orderItemJson->id);
                        $orderItem->SetCant($orderItemJson->cant);
                        array_push($orderItems, $orderItem);
                    }

                    $order = new Order($parsedBody['clientName'], $code, $parsedBody['mesaId'], $orderItems);
                    $header = $response->getHeader("mesaCode");

                    $order->SetItems($orderItems);
                    $order->SetMesaCode($header[0]);

                    date_default_timezone_set('America/Argentina/Buenos_Aires');
                    $date = date('Y/m/d H:i');
                    $order->SetOrderedTime($date); // Hora en que se hizo el pedido.
                    $foto = "SINFOTO.jpg";
                    $file = $request->getUploadedFiles(); // Agarramos la foto.
                    if (isset($file['foto'])) {
                        $file = $request->getUploadedFiles();

                        if (!$file['foto']->getError() && validateType($file["foto"])) {
                            $foto = Order::SaveFoto($file, $code, './imgs/orders/');
                        }

                    }

                    $order->SetFoto($foto);
                    $userData = UserRepository::GetByUser($userInfo[1]/*user*/);
                    $internalResponse = OrderRepository::InsertOrder($order, $userData->GetId());
                    $existCode = false;

                    $result = $internalResponse;
                } else {
                    $existCode = true;
                }
            } while ($existCode && $count < 50);
        } catch (PDOException $exception) {
            $result = new ApiResponse(REQUEST_ERROR_TYPE::DATABASE, $exception->getMessage());
        } catch (Exception $exception) {
            $result = new ApiResponse(REQUEST_ERROR_TYPE::GENERAL, $exception->getMessage());
        }
        $response->getBody()->write($result->ToJsonResponse());
    }

    private static function validateType($file)
    {
        $return = false;
        if (strpos(strtolower($file->getClientMediaType()), "jpg") ||
            strpos(strtolower($file->getClientMediaType()), "png") ||
            strpos(strtolower($file->getClientMediaType()), "jpeg")) {
            $return = true;
        }

        return $return;

    }

    public function GetOrderInfoToEvaluate($request, $response, $args)
    {
        try {
            $parsedBody = $request->getParsedBody();
            $result = OrderRepository::GetOrderInfoToEvaluate($parsedBody["orderCode"]);
        } catch (PDOException $exception) {
            $result = new ApiResponse(REQUEST_ERROR_TYPE::DATABASE, $exception->getMessage());
        } catch (Exception $exception) {
            $result = new ApiResponse(REQUEST_ERROR_TYPE::GENERAL, $exception->getMessage());
        }

        $response->getBody()->write($result->ToJsonResponse());
    }

    public function SetEvaluation($request, $response, $args)
    {
        try {
            $parsedBody = $request->getParsedBody();
            $statisctic = new Statisctic();
            $statisctic->SetMozoId($parsedBody["mozoId"]);
            $statisctic->SetMozoEvaluation($parsedBody["mozoEvaluation"]);
            $statisctic->SetMesaCode($parsedBody["mesaCode"]);
            $statisctic->SetMesaEvaluation($parsedBody["mesaEvaluation"]);
            $statisctic->SetOrderCode($parsedBody["orderCode"]);
            $statisctic->SetCocineros(json_decode($parsedBody["cocineros"]));
            $statisctic->SetRestaurantEvaluation($parsedBody["restaurantEvaluation"]);
            $statisctic->SetRestaurantComentario($parsedBody["comentario"]);
            $result = OrderRepository::SetEvaluation($statisctic);
        } catch (PDOException $exception) {
            $result = new ApiResponse(REQUEST_ERROR_TYPE::DATABASE, $exception->getMessage());
        } catch (Exception $exception) {
            $result = new ApiResponse(REQUEST_ERROR_TYPE::GENERAL, $exception->getMessage());
        }

        $response->getBody()->write($result->ToJsonResponse());
    }

    public function ResumenPedidos($request, $response, $args)
    {
        try {
            $userInfo = $response->getHeader("userInfo");
            UserActionRepository::SaveByUser("Guardar pedido", $userInfo[1]);
            $result = OrderRepository::ResumenPedidos();
        } catch (PDOException $exception) {
            $result = new ApiResponse(REQUEST_ERROR_TYPE::DATABASE, $exception->getMessage());
        } catch (Exception $exception) {
            $result = new ApiResponse(REQUEST_ERROR_TYPE::GENERAL, $exception->getMessage());
        }

        $response->getBody()->write($result->ToJsonResponse());
    }

}
