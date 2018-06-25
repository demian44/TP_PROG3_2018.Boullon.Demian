<?php

class MediaApi extends MediaRepository implements IApiUsable
{
    public function TraerUno($request, $response, $args)
    {
    }

    public function Ver($request, $response, $args)
    {
    }

    public function TraerTodos($request, $response, $args)
    {
        try {

            if (MediaRepository::TraerMedias($arrayMedias)) {
                $result = new ApiResponse(REQUEST_ERROR_TYPE::NOERROR, $arrayMedias);
            } else {
                $result = new ApiResponse(REQUEST_ERROR_TYPE::NODATA, "Sin elementos");
            }
        } catch (PDOException $exception) {
            $result = new ApiResponse(REQUEST_ERROR_TYPE::DATABASE, $exception->getMessage());
        } catch (Exception $exception) {
            $result = new ApiResponse(REQUEST_ERROR_TYPE::GENERAL, $exception->getMessage());
        }
        $response->getBody()->write($result->ToJsonResponse());

    }

    public function CargarUno($request, $response, $args)
    {
        $count = 0;
        $parsedBody = $request->getParsedBody();
        $existCode = false;

        try {
            $media = new Media($parsedBody['color'],
                $parsedBody['marca'],
                $parsedBody['talle'],
                $parsedBody['precio']);

            $foto = $this->SaveFoto($request->getUploadedFiles(),
                $parsedBody['talle'] .
                $parsedBody['marca'] .
                $parsedBody['color']);

            //Concateno el nombre de la foto con host,puerto y api.
            $media->SetFoto($request->getUri()->getHost() .
                ':' .
                $request->getUri()->getPort() .
                PROYECT_NAME .
                "$foto");

            $mediaRepository = new MediaRepository();
            $internalResponse = $mediaRepository->InsertMedia($media);

            $result = $internalResponse;
        } catch (PDOException $exception) {
            $result = new ApiResponse(REQUEST_ERROR_TYPE::DATABASE, $exception->getMessage());
        } catch (Exception $exception) {
            $result = new ApiResponse(REQUEST_ERROR_TYPE::GENERAL, $exception->getMessage());
        }
        $response->getBody()->write($result->ToJsonResponse());
    }

    public function SaveFoto($file, $name)
    {
        $destino = './imgs/';
        ////GUARDAR ARCHIVO
        $nombreAnterior = $file['foto']->getClientFilename();
        $extension = explode('.', $nombreAnterior);
        $file['foto']->moveTo($destino . "$name." . $extension[1]);

        return substr($destino, 2, 5) . "$name." . $extension[1];
    }

    private function SaveMedia($media)
    {
    }

    public function BorrarUno($request, $response, $args)
    {
        try {

            $id = $request->getParsedBody()['id'];

            $mediaRepository = new MediaRepository();
            $coso = MediaRepository::TraerMediaPorId($id);
            if (count($coso)) {
                Media::BackupFoto($coso[0]["foto"]);

                $internalResponse = $mediaRepository->DeletetMedia($id);

                $result = $internalResponse;
            } else {
                $result = new ApiResponse(REQUEST_ERROR_TYPE::NOEXIST, "No existe el id");
            }

        } catch (PDOException $exception) {
            $result = new ApiResponse(REQUEST_ERROR_TYPE::DATABASE, $exception->getMessage());
        } catch (Exception $exception) {
            $result = new ApiResponse(REQUEST_ERROR_TYPE::GENERAL, $exception->getMessage());
        }
        $response->getBody()->write($result->ToJsonResponse());
    }

    public function ModificarUno($request, $response, $args)
    {
    }
}
