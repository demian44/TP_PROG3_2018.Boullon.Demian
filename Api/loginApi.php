<?php
class LoginApi extends LoginRepository implements IApiUsable
{
    public function TraerUno($request, $response, $args)
    {
    }
    public function Ver($request, $response, $args)
    {
        $response->getBody()->write("Hola");
    }
    public function TraerTodos($request, $response, $args)
    {
    }
    public function Login($request, $response, $args)
    {
        $parsedBody = $request->getParsedBody();
        $user = new User(
            "",
            $parsedBody['user'],
            $parsedBody['password'],
            ""
        );

        $loginResponse = new InternalResponse();
        $loginResponse = $this->CheckUser($user);

        if ($loginResponse->GetElement()["succesLogin"]) {
            $token = array(
                "category" => $loginResponse->GetElement()["category"], //Tipo de usuario
                "exp" => time() + 600, // La sesión dura 10 minutos.
                "nbf" => time()
            );
            $tk = new SecurityToken();
            try {
                $responseToken = $tk->Encode($token);
                $response->getBody()->write($responseToken);
            } catch (Exception $excption) {
                $response->getBody()->write($excption->getMessage());
            }
        } else {
            $response->getBody()->write($loginResponse->GetMessege());
        }
        
        /*$newResponse = $response->withJson($this,200);
        return $newResponse;*/
    }

    public function CargarUno($request, $response, $args)
    {
    }
    public function BorrarUno($request, $response, $args)
    {
    }
    public function ModificarUno($request, $response, $args)
    {
    }
}

?>