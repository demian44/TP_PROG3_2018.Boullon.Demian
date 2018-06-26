<?php

class UserApi
{
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

            $foto = User::SaveFoto($request->getUploadedFiles(),
                $parsedBody['user']
                , './UserImg/'
            );

            $user->SetFoto($request->getUri()->getHost() .
                ':' .
                $request->getUri()->getPort() .
                PROYECT_NAME .
                $foto);

            $requestResponse = UserRepository::Insert($user);
        } catch (PDOException $exception) {
            $requestResponse = new ApiResponse(REQUEST_ERROR_TYPE::DATABASE, $exception->getMessage());
        } catch (Exception $exception) {
            $requestResponse = new ApiResponse(REQUEST_ERROR_TYPE::GENERAL, $exception->getMessage());
        }

        $response->getBody()->write($requestResponse->ToJsonResponse());
    }

    public function Editar($request, $response, $args)
    {
        try {
            $parsedBody = $request->getParsedBody();
            $user = new User(
                "",
                $parsedBody['user'],
                $parsedBody['password'],
                ""
            );

            $requestResponse = UserRepository::EditUsuarios($user);
        } catch (PDOException $exception) {
            $requestResponse = new ApiResponse(REQUEST_ERROR_TYPE::DATABASE, $exception->getMessage());
        } catch (Exception $exception) {
            $requestResponse = new ApiResponse(REQUEST_ERROR_TYPE::GENERAL, $exception->getMessage());
        }

        $response->getBody()->write($requestResponse->ToJsonResponse());
    }

    public function Borrar($request, $response, $args)
    {
        try {
            $parsedBody = $request->getParsedBody();
            $id = $request->getParsedBody()['id'];
            $foto = $response->getHeader("foto");
            Venta::BackupFoto($foto[0], './UserImg/');
            $requestResponse = UserRepository::EliminarUsuario($id);
        } catch (PDOException $exception) {
            $requestResponse = new ApiResponse(REQUEST_ERROR_TYPE::DATABASE, $exception->getMessage());
        } catch (Exception $exception) {
            $requestResponse = new ApiResponse(REQUEST_ERROR_TYPE::GENERAL, $exception->getMessage());
        }

        $response->getBody()->write($requestResponse->ToJsonResponse());
    }
}
