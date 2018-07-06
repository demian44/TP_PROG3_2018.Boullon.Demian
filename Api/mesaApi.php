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
        $count = 0;
        $parsedBody = $request->getParsedBody();
        $existCode = true;
        $result = new ApiResponse(REQUEST_ERROR_TYPE::GENERAL,
            "Problema en la generacion de código.");
        $mesa = new Mesa();

        try {
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
}
