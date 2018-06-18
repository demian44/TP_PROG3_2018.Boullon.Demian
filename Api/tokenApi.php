<?php
class TokenApi extends TokenRepository implements IApiUsable
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
        $newResponse = ["Usuario inexistente",-1];

        $headers = $request->getHeaders();
          
        $parsedBody = $request->getParsedBody();
        $user = new User(
            "",
            $parsedBody['user'],
            $parsedBody['password'],
            ""
        );
        
        $loginResponse = new InternalResponse();

        $loginResponse = $this->CheckUser($user);
        if ($loginResponse->GetElement()["succesToken"]) {
            $token = array(
                "category" => $loginResponse->GetElement()["category"], //Tipo de usuario
                "exp" => time() + 6000, // La sesión dura 10 minutos.
                "nbf" => time()
            );
            
            $securityToken = new SecurityToken();
            try {
                $headers['HTTP_TOKEN'] = $securityToken->Encode($token);
                $responseToken = $headers['HTTP_TOKEN']; // Guardo el token en el header
                $headers['category'] = $loginResponse->GetElement()["category"];
                $request->withAddedHeader('Category',$responseToken);  // Setteo en el header el tipo
                 $newResponse = $responseToken; 
                 $result = json_encode([0,$responseToken]);

            } catch (Exception $excption) {
                $result= [-1,$excption->getMessage()];
            }
        } else {
            $result =json_encode([-2,$loginResponse->GetMessege()]);
        }

        $response->getBody()->write($result);
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
    public function ValidarToken($request, $response, $args)
    {
        $return = false;
        try {
            $header = $request->getHeader("token");
            $tk = new SecurityToken();
            $decodedUser = $tk->Decode($header[0]);
            echo "      decode     ";
            $headers = $request->getHeaders();
            var_dump($decodedUser);
            $headers['category'] = $decodedUser->category;
            
            $return = true;
        } catch (BeforeValidException $exception) {
            echo "wea suprema  1";
            //$response->getBody()->write(json_encode(['code' => -1, 'messege' => "Error de token: " . $exception->getMessage()]));
        } catch (ExpiredException $exception) {
            echo "wea suprema  2";
            //$response->getBody()->write(json_encode(['code' => -1, 'messege' => "Error de token: " . $exception->getMessage()]));
        } catch (SignatureInvalidException $exception) {
            echo "wea suprema  3";
            //$response->getBody()->write(json_encode(['code' => -1, 'messege' => "Error de token: " . $exception->getMessage()]));
        } catch (Exception $exception) {
            echo "wea suprema  4";
            $response->getBody()->write("Error");
                //json_encode(['code' => -1, 'messege' => "Error de token: " . $exception->getMessage()]));
        }

        return $return;
    }

    public function ValidarMozo($request, $response, $args)
    {
        $return = false;
        
        if ($headers["category"] == Category::MOZO) {
            $return = true;
        }

        return $return;
    }

    public function ValidarSocio($request, $response, $args)
    {
        $return = false;
        
        if ($headers["category"] == Category::SOCIO) {
            $return = true;
        }

        return $return;
    }
}

?>