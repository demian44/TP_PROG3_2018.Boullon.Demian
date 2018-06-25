<?php
class UserMiddleware
{
    public function CheckUserData($request, $response, $next)
    {
        $parsedBody = $request->getParsedBody();
        $flag = true;
        $errorMessege = '';
        if (!isset($parsedBody['user']) || !isset($parsedBody['password']) || !isset($parsedBody['perfil'])) {
            $flag = false;
            $errorMessege = 'Faltan campos'; // Este campo debería ser chequeado en front.
        }
        if ($flag) {

            $response = $next($request, $response);
        } else {
            $response->getBody()->write(
                (new ApiResponse(REQUEST_ERROR_TYPE::NODATA, $errorMessege))->ToJsonResponse()
            );
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
}
