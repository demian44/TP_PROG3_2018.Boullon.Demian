<?php

class OrderApi implements IApiUsable
{
    public function TraerUno($request, $response, $args)
    {
    }

    public function Ver($request, $response, $args)
    {
    }

    public function TraerTodos($request, $response, $args)
    {
        try {
            $header = $response->getHeader("category");
            $result = OrderRepository::GetAll($request->getUri()->getHost() . ':'
                . $request->getUri()->getPort() . PROYECT_NAME, $header[0]);

        } catch (PDOException $exception) {
            $result = new ApiResponse(REQUEST_ERROR_TYPE::DATABASE, $exception->getMessage());
        } catch (Exception $exception) {
            $result = new ApiResponse(REQUEST_ERROR_TYPE::GENERAL, $exception->getMessage());
        }

        $response->getBody()->write($result->ToJsonResponse());
    }

    public function CargarUno($request, $response, $args)
    {
        $count = 0;
        $parsedBody = $request->getParsedBody();
        $existCode = false;

        try {

            do {
                $code = Order::generateCode();
                $orderItemsJson = json_decode($parsedBody['orderIdItems']);
                $orderItems = [];
                foreach ($orderItemsJson as $key => $orderItemJson) {
                    $orderItem = new OrderItem($orderItemJson->id, $orderItemJson->cant);
                    array_push($orderItems, $orderItem);
                }

                if (!OrderRepository::CheckCodes($code)) {
                    $order = new Order($parsedBody['clientName'], $code, $parsedBody['mesaId'], $orderItems);

                    date_default_timezone_set('America/Argentina/Buenos_Aires');
                    $date = date('Y/m/d H:i');
                    $order->SetOrderedTime($date); // Hora en que se hizo el pedido.

                    $file = $request->getUploadedFiles(); // Agarramos la foto.
                    if (isset($file['foto'])) {
                        $foto = Order::SaveFoto($request->getUploadedFiles(), $code, './imgs/orders/');
                    }

                    $order->SetFoto($foto);

                    $internalResponse = OrderRepository::InsertOrder($order);

                    $result = $internalResponse;
                }
            } while ($existCode && $count < 50);
        } catch (PDOException $exception) {
            $result = new ApiResponse(REQUEST_ERROR_TYPE::DATABASE, $exception->getMessage());
        } catch (Exception $exception) {
            $result = new ApiResponse(REQUEST_ERROR_TYPE::GENERAL, $exception->getMessage());
        }
        $response->getBody()->write($result->ToJsonResponse());
    }

    private function SaveOrder($order)
    {
    }

    public function BorrarUno($request, $response, $args)
    {
    }

    public function ModificarUno($request, $response, $args)
    {
    }
}
