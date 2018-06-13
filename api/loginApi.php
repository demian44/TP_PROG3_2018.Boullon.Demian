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
        $headers = $request->getHeaders();
        foreach ($headers as $name => $values) {
            echo $name . ": " . implode(", ", $values);
        }
        
        
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
            $securityToken = new SecurityToken();
            try {
                $responseToken = $securityToken->Encode($token);
                $request->withAddedHeader('Category',$responseToken);  // Setteo en el header el tipo
               
                $newResponse = $response->withJson([$responseToken, 200,$headers]);

            } catch (Exception $excption) {
                $response->getBody()->write($excption->getMessage());
            }
        } else {
            $response->getBody()->write($loginResponse->GetMessege());
        }
        
        return $newResponse;
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
    private function ValidarToken($request, $response, $args)
    {
        try {
            $header = $request->getHeader("token");
            $tk = new SecurityToken();
            $decodedUser = $tk->Decode($header[0]);
            return $decodedUser;
        } catch (BeforeValidException $exception) {
            $response->getBody()->write(json_encode(['code' => -1, 'messege' => "Error de token: " . $exception->getMessage()]));
        } catch (ExpiredException $exception) {
            $response->getBody()->write(json_encode(['code' => -1, 'messege' => "Error de token: " . $exception->getMessage()]));
        } catch (SignatureInvalidException $exception) {
            $response->getBody()->write(json_encode(['code' => -1, 'messege' => "Error de token: " . $exception->getMessage()]));
        } catch (Exception $exception) {
            $response->getBody()->write(json_encode(['code' => -1, 'messege' => "Error de token: " . $exception->getMessage()]));
        }
    }

    public function ValidarMozo($request, $response, $args)
    {
        $return = false;
        $decodedUser = $this->ValidarToken($request, $response, $args);

        if ($decodedUser["category"] == Category::MOZO) {
            $return = true;
        }

        return $return;
    }

    public function ValidarSocio($request, $response, $args)
    {
        $return = false;
        $decodedUser = $this->ValidarToken($request, $response, $args);

        if ($decodedUser["category"] == Category::SOCIO) {
            $return = true;
        }

        return $return;
    }
}

?>