<?php

class ApiResponse
{
    private $errorCode;
    private $response;

    public function __construct($errorCode, $response)
    {
        if ($this->SetErrorCode($errorCode) && $this->SetResponse($response)) {
            return true;
        } else {
            return false;
        }
    }

    public function ToJsonResponse()
    {
        return json_encode(['code' => $this->errorCode, 'response' => $this->response]);
    }

    public function GetErrorCode()
    {
        return $this->errorCode;
    }
    public function Succes()
    {
        if ($this->errorCode == REQUEST_ERROR_TYPE::NOERROR) {
            return true;
        } else {
            return false;
        }

    }

    public function GetResponse()
    {
        return $this->response;
    }

    public function SetErrorCode($errorCode)
    {
        $return = false;
        if (is_int($errorCode)) {
            $this->errorCode = $errorCode;
            $return = true;
        } else {
            $return = false;
        }

        return $return;
    }

    public function SetResponse($response)
    {
        $return = false;
        if ($response != null) {
            $this->response = $response;
            $return = true;
        } else {
            $return = false;
        }

        return $return;
    }
}
