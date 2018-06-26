<?php

class MediaApi
{

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

            $foto = Media::SaveFoto($request->getUploadedFiles(),
                $parsedBody['talle'] .
                $parsedBody['marca'] .
                $parsedBody['color'], './imgs/');

            //Concateno el nombre de la foto con host,puerto y api.
            $media->SetFoto($request->getUri()->getHost() .
                ':' .
                $request->getUri()->getPort() .
                PROYECT_NAME .
                "$foto");

            $internalResponse = MediaRepository::Insert($media);

            $result = $internalResponse;
        } catch (PDOException $exception) {
            $result = new ApiResponse(REQUEST_ERROR_TYPE::DATABASE, $exception->getMessage());
        } catch (Exception $exception) {
            $result = new ApiResponse(REQUEST_ERROR_TYPE::GENERAL, $exception->getMessage());
        }
        $response->getBody()->write($result->ToJsonResponse());
    }

    public function BorrarUno($request, $response, $args)
    {
        try {

            $id = $request->getParsedBody()['id'];

            $media = MediaRepository::TraerMediaPorId($id);
            if ($media) {
                Media::BackupFoto($media["foto"], './imgs/');

                $internalResponse = MediaRepository::Delete($id);

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

}
