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
    }

    public function CargarUno($request, $response, $args)
    {
        $parsedBody = $request->getParsedBody();
        $user = new User(
            $parsedBody['name'],
            $parsedBody['user'],
            $parsedBody['password'],
            $parsedBody['category']
        );

        $response = $this->InsertUser($user);
    }

    public function BorrarUno($request, $response, $args)
    {
    }

    public function ModificarUno($request, $response, $args)
    {
    }
}
