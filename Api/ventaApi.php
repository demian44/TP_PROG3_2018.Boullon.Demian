<?php

class VentaApi
{

    public function CargarUno($request, $response, $args)
    {
        $count = 0;
        $parsedBody = $request->getParsedBody();

        $existCode = false;

        try {
            $venta = new Venta(
                $parsedBody['idMedia'],
                $parsedBody['nombreCliente'],
                $parsedBody['fecha'],
                $parsedBody['importe']
            );

            $date = explode("/", $parsedBody['fecha']);

            $file = $request->getUploadedFiles(); // Agarramos la foto.

            $foto = Venta::SaveFoto($request->getUploadedFiles(),
                $parsedBody['idMedia'] .
                $parsedBody['nombreCliente'] .
                $date[0] . $date[1] . $date[2], './FotosVentas/'
            );

            $venta->SetFoto($request->getUri()->getHost() .
                ':' .
                $request->getUri()->getPort() .
                PROYECT_NAME .
                $foto);

            //Concateno el nombre de la foto con host,puerto y api.

            $internalResponse = VentaRepository::InsertVenta($venta);

            $result = $internalResponse;
        } catch (PDOException $exception) {
            $result = new ApiResponse(REQUEST_ERROR_TYPE::DATABASE, $exception->getMessage());
        } catch (Exception $exception) {
            $result = new ApiResponse(REQUEST_ERROR_TYPE::GENERAL, $exception->getMessage());
        }
        $response->getBody()->write($result->ToJsonResponse());
    }

    public function ModificarUno($request, $response, $args)
    {
        $count = 0;
        $parsedBody = $request->getParsedBody();

        $ventaVieja = $response->getHeader("venta");

        $existCode = false;

        try {
            $venta = new Venta(
                $parsedBody['idMedia'],
                $parsedBody['nombreCliente'],
                $parsedBody['fecha'],
                $parsedBody['importe']
            );

            $date = explode("/", $parsedBody['fecha']);

            $file = $request->getUploadedFiles(); // Agarramos la foto.
            if (isset($file['foto'])) {
                Venta::BackupFoto($ventaVieja[0], './FotosVentas/');

                $foto = Venta::SaveFoto($request->getUploadedFiles(),
                    $parsedBody['idMedia'] .
                    $parsedBody['nombreCliente'] .
                    $date[0] . $date[1] . $date[2], './FotosVentas/'
                );

                $venta->SetFoto($request->getUri()->getHost() .
                    ':' .
                    $request->getUri()->getPort() .
                    PROYECT_NAME .
                    $foto);
            } else {
                $venta->SetFoto($ventaVieja[0]);
            }
            $venta->SetId($ventaVieja[1]);

            //Concateno el nombre de la foto con host,puerto y api.

            $internalResponse = VentaRepository::EditarVenta($venta);

            $result = $internalResponse;
        } catch (PDOException $exception) {
            $result = new ApiResponse(REQUEST_ERROR_TYPE::DATABASE, $exception->getMessage());
        } catch (Exception $exception) {
            $result = new ApiResponse(REQUEST_ERROR_TYPE::GENERAL, $exception->getMessage());
        }
        $response->getBody()->write($result->ToJsonResponse());
    }

}
