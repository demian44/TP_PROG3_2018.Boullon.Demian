<?php
use \Firebase\JWT\JWT;

class PedidoApi extends PedidoRepository implements IApiUsable
{
  
    public function TraerUno($request, $response, $args)
    {
    }
    public function TraerTodos($request, $response, $args)
    {
    }
    
    
    
    public function CargarUno($request,$response,$args)
    {
        $parsedBody = $request->getParsedBody();
        $pedido = new Pedido();
        $pedido->SetCliente($parsedBody['name']);
        $this->InsertarPedido($pedido);

        /*$newResponse = $response->withJson($this,200);
        return $newResponse;*/
    }
    public function BorrarUno($request, $response, $args)
    {
    }
    public function ModificarUno($request, $response, $args)
    {
    }
}

?>