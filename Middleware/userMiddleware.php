<?php
class UserMiddleware
{
    public function CheckUserData($request, $response, $next)
    {
        $parsedBody = $request->getParsedBody();
        $file = $request->getUploadedFiles();

        $errorMessege = '';
        if (!isset($parsedBody['user']) ||
            !isset($parsedBody['password']) ||
            !isset($parsedBody['perfil']) ||
            !isset($parsedBody['name']) ||
            !isset($file['foto'])) {

            $errorMessege = 'Faltan campos'; // Este campo debería ser chequeado en front.
            $response->getBody()->write((new ApiResponse(REQUEST_ERROR_TYPE::NODATA,
                $errorMessege))->ToJsonResponse());
        } else {
            $response = $next($request, $response);
        }

        return $response;
    }

    public function ValidarToken($request, $response, $next)
    {
        ///Implementar middleware así
        try {
            $header = $request->getHeader('token');
            $tk = new SecurityToken();
            $decodedUser = $tk->Decode($header[0]);
            $response = $next($request, $response);

        } catch (BeforeValidException $exception) {
            $response->getBody()->write(json_encode(['code' => REQUEST_ERROR_TYPE::TOKEN, 'messege' => 'Error de token: ' . $exception->getMessage()]));
        } catch (ExpiredException $exception) {
            $response->getBody()->write(json_encode(['code' => REQUEST_ERROR_TYPE::TOKEN, 'messege' => 'Error de token: ' . $exception->getMessage()]));
        } catch (SignatureInvalidException $exception) {
            $response->getBody()->write(json_encode(['code' => REQUEST_ERROR_TYPE::TOKEN, 'messege' => 'Error de token: ' . $exception->getMessage()]));
        } catch (Exception $exception) {
            $response->getBody()->write(json_encode(['code' => REQUEST_ERROR_TYPE::TOKEN, 'messege' => 'Error de token: ' . $exception->getMessage()]));
        }

        return $response;
    }

    public function CheckUserEdit($request, $response, $next)
    {
        $parsedBody = $request->getParsedBody();

        $errorMessege = '';
        if (!isset($parsedBody['password']) || !isset($parsedBody['user'])) {

            $errorMessege = 'Faltan campos'; // Este campo debería ser chequeado en front.
            $response->getBody()->write((new ApiResponse(REQUEST_ERROR_TYPE::NODATA,
                $errorMessege))->ToJsonResponse());
        } else {
            $response = $next($request, $response);
        }

        return $response;
    }

    public function ExisteId($request, $response, $next)
    {
        $parsedBody = $request->getParsedBody();

        $user = UserRepository::TraerUsuarioPorId($parsedBody['id']);
        if ($user) {
            $newResponse = $response->withAddedHeader("foto", $user["foto"]);
            $response = $next($request, $newResponse);
        } else {
            $result = new ApiResponse(REQUEST_ERROR_TYPE::NOEXIST, 'No existe la usuario.');
            $response->getBody()->write($result->ToJsonResponse());
        }

        return $response;

    }

    public function UserRepetido($request, $response, $next)
    {
        $parsedBody = $request->getParsedBody();

        $user = UserRepository::ExisteUser($parsedBody['user']);

        if (!$user) {
            $response = $next($request, $response);
        } else {
            $result = new ApiResponse(REQUEST_ERROR_TYPE::NOEXIST, 'Ya existe la usuario.');
            $response->getBody()->write($result->ToJsonResponse());
        }

        return $response;

    }

    public function IdCargado($request, $response, $next)
    {
        $parsedBody = $request->getParsedBody();

        if (isset($parsedBody['id'])) {
            $response = $next($request, $response);

        } else {
            $result = new ApiResponse(REQUEST_ERROR_TYPE::NOEXIST, 'Falta cargar Id.');
            $response->getBody()->write($result->ToJsonResponse());
        }

        return $response;
    }
}
