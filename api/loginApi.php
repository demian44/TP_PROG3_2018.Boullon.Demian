<?php
use \Firebase\JWT\JWT;
use Slim\Http\Headers;

class LoginApi
{
    const KEY = "WEA";
    private $token;




    public function GenerarTokken($request, $response, $args)
    {
        $hoy = getdate();

        echo ("\n\n\n\n" . $hoy[0] . "\n\n\n\n");
        echo ("\n\n\n\n" . time() . "\n\n\n\n");

        $parsedBody = $request->getParsedBody();
       // var_dump($parsedBody);
        $this->token = array(
            "name" => $parsedBody['name'],
            "admin" => $parsedBody['admin'],
            "iat" => time(), // REFERENCIA de cuando fue creado
            "exp" => time() + 60, // Tope, hasta que momento va a funcionar 
            "nbf" => time() +30 // Desde que momento puedo usarlo
        );

        $jwt = JWT::encode($this->token, self::KEY);

        $response->getBody()->write($jwt);
    }


    public function ProbarTokken($request, $response, $args)
    {

        try {
            $header = $request->getHeader('tokken');
            $decoded = JWT::decode($header[0], self::KEY, array('HS256'));
            var_dump($decoded);

        } catch (ExpiredException $exception) {
            $messege = $exception->getMessage();
            $response->getBody()->write("$messege");
            
        } catch (SignatureInvalidException $exception) {
            $messege = $exception->getMessage();
            $response->getBody()->write("$messege");
        } catch (BeforeValidException $exception) {
            $messege = $exception->getMessage();
            $response->getBody()->write("$messege");
        } catch (Exception $exception) {
            $messege = $exception->getMessage();
            $response->getBody()->write("$messege");
        }


        /**
         * IMPORTANT:
         * You must specify supported algorithms for your application. See
         * https://tools.ietf.org/html/draft-ietf-jose-json-web-algorithms-40
         * for a list of spec-compliant algorithms.
         */
        // $jwt = JWT::encode($this->token,self::KEY);
        
        
        
        
        // print_r($jwt);

        // print_r("<br>");
        // print_r( $decoded);
        // print_r("<br>");
        
        // /*
        //  NOTE: This will now be an object instead of an associative array. To get
        //  an associative array, you will need to cast it as such:
        // */
        
        // $decoded_array = (array) $decoded;
        
        // /**
        //  * You can add a leeway to account for when there is a clock skew times between
        //  * the signing and verifying servers. It is recommended that this leeway should
        //  * not be bigger than a few minutes.
        //  *
        //  * Source: http://self-issued.info/docs/draft-ietf-oauth-json-web-token.html#nbfDef
        //  */
        // JWT::$leeway = 60; // $leeway in seconds
        // $decoded = JWT::decode($jwt, self::KEY, array('HS256'));

    }
}

?>