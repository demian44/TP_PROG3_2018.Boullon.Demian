<?php

class MesaApi implements IApiUsable
{
    public function GetOne($request, $response, $args)
    {
    }

    public function Ver($request, $response, $args)
    {
    }

    public function GetAll($request, $response, $args)
    {
        try {
            $userInfo = $response->getHeader("userInfo");
            UserActionRepository::SaveByUser("Ver todos las mesas", $userInfo[1]);
            $result = MesaRepository::GetAll();
        } catch (PDOException $exception) {
            $result = new ApiResponse(REQUEST_ERROR_TYPE::DATABASE, $exception->getMessage());
        } catch (Exception $exception) {
            $result = new ApiResponse(REQUEST_ERROR_TYPE::GENERAL, $exception->getMessage());
        }
        $response->getBody()->write($result->ToJsonResponse());
    }

    public function CargarUno($request, $response, $args)
    {
        try {
            $count = 0;
            $parsedBody = $request->getParsedBody();
            $existCode = true;
            $userInfo = $response->getHeader("userInfo");
            UserActionRepository::SaveByUser("Cargar mesa", $userInfo[1]);
            $result = new ApiResponse(REQUEST_ERROR_TYPE::GENERAL, "Problema en la generacion de código.");
            $mesa = new Mesa();

            do {
                $code = Mesa::generateCode();

                if (!MesaRepository::CheckCodes($code)) {
                    $mesa->SetCode($code);
                    $result = MesaRepository::Insert($mesa);
                    $existCode = false;
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

    public function Waiting($request, $response, $args)
    {
        $mesaId = $response->getHeader("mesaId");
        $this->SetStatus($request, $response, $args, $mesaId[0], MESA_STATUS::CON_CLIENTE_ESPERANDO_PEDIDO);
    }
    public function Eating($request, $response, $args)
    {
        $mesaId = $response->getHeader("mesaId");
        $this->SetStatus($request, $response, $args, $mesaId[0], MESA_STATUS::CON_CLIENTES_COMIENDO);
    }
    public function Paying($request, $response, $args)
    {
        $mesaId = $response->getHeader("mesaId");
        $this->SetStatus($request, $response, $args, $mesaId[0], MESA_STATUS::CON_CLIENTES_PAGANDO);
    }
    public function Close($request, $response, $args)
    {
        $mesaId = $response->getHeader("mesaId");
        $this->SetStatus($request, $response, $args, $mesaId[0], MESA_STATUS::CERRADA);
    }

    private function SetStatus($request, $response, $args, int $id, int $status)
    {
        try {
            $userInfo = $response->getHeader("userInfo");
            UserActionRepository::SaveByUser("Cambiar estado de la mesa.", $userInfo[1]);
            $result = MesaRepository::SetStatus($id, $status);
        } catch (PDOException $exception) {
            $result = new ApiResponse(REQUEST_ERROR_TYPE::DATABASE, $exception->getMessage());
        } catch (Exception $exception) {
            $result = new ApiResponse(REQUEST_ERROR_TYPE::GENERAL, $exception->getMessage());
        }
        $response->getBody()->write($result->ToJsonResponse());
    }
}
