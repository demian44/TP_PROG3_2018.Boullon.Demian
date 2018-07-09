<?php

class ItemApi
{
    
    public function GetAll($request, $response, $args)
     {
        try {
            $userInfo = $response->getHeader("userInfo");
            UserActionRepository::SaveByUser("Ver Items", $userInfo[1]);

            $result = ItemRepository::GetAll();
        } catch (PDOException $exception) {
            $result = new ApiResponse(REQUEST_ERROR_TYPE::DATABASE, $exception->getMessage());
        } catch (Exception $exception) {
            $result = new ApiResponse(REQUEST_ERROR_TYPE::GENERAL, $exception->getMessage());
        }
        $response->getBody()->write($result->ToJsonResponse());
    }

    public function CargarUno($request, $response, $args)
    {
        $parsedBody = $request->getParsedBody();
        $item = new Item();
        
        $item->SetName($parsedBody["name"]);
        $item->SetSector($parsedBody["sector"]);
        $item->SetPrecio($parsedBody["precio"]);
        
        echo "Asdhasoidsao";
        try {
            $userInfo = $response->getHeader("userInfo");
            UserActionRepository::SaveByUser("Agregar Item", $userInfo[1]);
            $result = ItemRepository::Save($item);
        } catch (PDOException $exception) {
            $result = new ApiResponse(REQUEST_ERROR_TYPE::DATABASE, $exception->getMessage());
        } catch (Exception $exception) {
            $result = new ApiResponse(REQUEST_ERROR_TYPE::GENERAL, $exception->getMessage());
        }
        $response->getBody()->write($result->ToJsonResponse());
    }

   
}
