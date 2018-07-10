<?php

class MESA_STATUS
{
    const CERRADA = 0;
    const CON_CLIENTE_ESPERANDO_PEDIDO = 1;
    const CON_CLIENTES_COMIENDO = 2;
    const CON_CLIENTES_PAGANDO = 3;

    public static function String(int $category): string
    {
        $return = "";
        switch ($category) {
            case Self::CERRADA:
                $return = "CERRADA";
                break;
            case Self::CON_CLIENTE_ESPERANDO_PEDIDO:
                $return = "CON_CLIENTE_ESPERANDO_PEDIDO";
                break;
            case Self::CON_CLIENTES_COMIENDO:
                $return = "CON_CLIENTES_COMIENDO";
                break;
            case Self::CON_CLIENTES_PAGANDO:
                $return = "CON_CLIENTES_PAGANDO";
                break;
        }
        return $return;
    }


}
