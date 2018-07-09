<?php

class UserApi
{
    public function GetOne($request, $response, $args)
    {
    }

    public function Ver($request, $response, $args)
    {
        $response->getBody()->write('Hola');
    }

    public function GetAllWithInfo($request, $response, $args)
    {
        try {

            $requestResponse = UserRepository::GetAllWithInfo();
        } catch (PDOException $exception) {
            $requestResponse = new ApiResponse(REQUEST_ERROR_TYPE::DATABASE, $exception->getMessage());
        } catch (Exception $exception) {
            $requestResponse = new ApiResponse(REQUEST_ERROR_TYPE::GENERAL, $exception->getMessage());
        }

        $response->getBody()->write($requestResponse->ToJsonResponse());
    }

    public function CargarUno($request, $response, $args)
    {
        try {
            $userInfo = $response->getHeader("userInfo");
            UserActionRepository::SaveByUser("Cargar Usuario", $userInfo[1]);

            $parsedBody = $request->getParsedBody();
            $user = new User(
                $parsedBody['name'],
                $parsedBody['user'],
                $parsedBody['password'],
                $parsedBody['category']
            );

            $requestResponse = UserRepository::Insert($user);
        } catch (PDOException $exception) {
            $requestResponse = new ApiResponse(REQUEST_ERROR_TYPE::DATABASE, $exception->getMessage());
        } catch (Exception $exception) {
            $requestResponse = new ApiResponse(REQUEST_ERROR_TYPE::GENERAL, $exception->getMessage());
        }

        $response->getBody()->write($requestResponse->ToJsonResponse());
    }

    public function BorrarUno($request, $response, $args)
    {
    }

    public function ModificarUno($request, $response, $args)
    {
    }
    public function DayAndHourEntry($request, $response, $args)
    {
        try {

            $requestResponse = UserRepository::DayAndHourEntry();
        } catch (PDOException $exception) {
            $requestResponse = new ApiResponse(REQUEST_ERROR_TYPE::DATABASE, $exception->getMessage());
        } catch (Exception $exception) {
            $requestResponse = new ApiResponse(REQUEST_ERROR_TYPE::GENERAL, $exception->getMessage());
        }

        $response->getBody()->write($requestResponse->ToJsonResponse());
    }
}
