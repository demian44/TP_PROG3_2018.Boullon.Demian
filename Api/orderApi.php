<?php

class OrderApi extends OrderRepository implements IApiUsable
{
    public function TraerUno($request, $response, $args)
    {
    }

    public function Ver($request, $response, $args)
    {
    }

    public function TraerTodos($request, $response, $args)
    {
    }

    public function CargarUno($request, $response, $args)
    {
        $count = 0;
        $parsedBody = $request->getParsedBody();
        $existCode = false;

        try {
            do {
                $code = Order::generateCode();

                if (!OrderRepository::CheckCodes($code)) {
                    $order = new Order($parsedBody['clientName'], $code, $parsedBody['mesaId'],
                    json_decode($parsedBody['orderItems']));

                    date_default_timezone_set('America/Argentina/Buenos_Aires');
                    $date = date('Y/m/d H:i');
                    $order->SetOrderedTime($date); // Hora en que se hizo el pedido.

                    $orderRepository = new OrderRepository();
                    $internalResponse = $orderRepository->InsertOrder($order);

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
