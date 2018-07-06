<?php
interface IApiUsable
{
    public function GetOne($request,$response,$args);    
    public function GetAll($request,$response,$args);    
    public function CargarUno($request,$response,$args);    
    public function BorrarUno($request,$response,$args);    
    public function ModificarUno($request,$response,$args);    
}

?>