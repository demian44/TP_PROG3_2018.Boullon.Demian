<?php

class UserApi extends UserRepository implements IApiUsable
{
    public function TraerUno($request, $response, $args)
    {
    }

    public function Ver($request, $response, $args)
    {
        $response->getBody()->write('Hola');
    }

    public function TraerTodos($request, $response, $args)
    {

        try {

            if (UserRepository::TraerUsuarios($arrayUsers)) {
                $result = new ApiResponse(REQUEST_ERROR_TYPE::NOERROR, $arrayUsers);
            } else {
                $result = new ApiResponse(REQUEST_ERROR_TYPE::NODATA, "Sin elementos");
            }
        } catch (PDOException $exception) {
            $result = new ApiResponse(REQUEST_ERROR_TYPE::DATABASE, $exception->getMessage());
        } catch (Exception $exception) {
            $result = new ApiResponse(REQUEST_ERROR_TYPE::GENERAL, $exception->getMessage());
        }
        echo "we";

        $response->getBody()->write($result->ToJsonResponse());
    }

    public function CargarUno($request, $response, $args)
    {
        try {
            $parsedBody = $request->getParsedBody();
            $user = new User(
                $parsedBody['name'],
                $parsedBody['user'],
                $parsedBody['password'],
                $parsedBody['perfil']
            );

            $requestResponse = $this->InsertUser($user);
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
}
