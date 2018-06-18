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

                    if ($internalResponse->GetError()) {
                        $result = [-1, $internalResponse->GetMessege()];
                    } else {
                        $result = [0, $internalResponse->GetMessege()];
                    }
                }
            } while ($existCode && $count < 50);
        } catch (PDOException $exception) {
            $result = [-1, $exception->getMessage()];
        } catch (Exception $exception) {
            $result = [-1, $exception->getMessage()];
        }

        $response->getBody()->write(json_encode($result));
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
