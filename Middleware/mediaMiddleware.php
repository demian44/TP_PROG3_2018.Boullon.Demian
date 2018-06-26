<?php
class MediaMiddleware
{
    public function CheckCarga($request, $response, $next)
    {
        $parsedBody = $request->getParsedBody();
        $file = $request->getUploadedFiles();
        $destino = './fotos/';

        if (isset($parsedBody['color']) && isset($parsedBody['marca']) &&
            isset($parsedBody['talle']) && isset($parsedBody['precio']) &&
            isset($file['foto'])) {

            if (!$file['foto']->getError()) {
                $response = $next($request, $response);
            } else {
                $result = new ApiResponse(REQUEST_ERROR_TYPE::NODATA, 'Foto corrompida.');
                $response->getBody()->write($result->ToJsonResponse());
            }

        } else {
            $result = new ApiResponse(REQUEST_ERROR_TYPE::NODATA, 'Faltan datos');
            $response->getBody()->write($result->ToJsonResponse());
        }

        return $response;
    }

    public function FiltrarRespuesta($request, $response, $next)
    {
        $response = $next($request, $response);

        $header = $response->getHeader("perfil");
        if ($header[0] != "encargado") {
            $medias = ($response->getBody()->__toString());
            $medias = json_decode($medias);

            $arrayMedias = $medias->response;

            foreach ($arrayMedias as $media) {
                unset($media->id);
            }
            $jsonResponse = (['code' => $medias->code, 'response' => $medias->response]);

            $newResponse = $response->withJson(json_encode($jsonResponse));
            return $newResponse;

        }
        return $response;
    }

    public function IdCargado($request, $response, $next)
    {
        $parsedBody = $request->getParsedBody();

        if (isset($parsedBody['id'])) {
            $response = $next($request, $response);
        } else {
            $result = new ApiResponse(REQUEST_ERROR_TYPE::NODATA, 'Faltan datos');
            $response->getBody()->write($result->ToJsonResponse());
        }

        return $response;
    }

    public function ExisteId($request, $response, $next)
    {
        $parsedBody = $request->getParsedBody();
        $media = MediaRepository::TraerMediaPorId($parsedBody['id']);

        if ($media) {
            $response = $next($request, $response);
        } else {
            $result = new ApiResponse(REQUEST_ERROR_TYPE::NOEXIST, 'No existe la media.');
            $response->getBody()->write($result->ToJsonResponse());
        }

        return $response;
    }
}
