<?php


class InternalResponse
{
    private $error; //REQUEST_ERROR_TYPE
    private $messege;
    private $element;

    public function __construct()
    {
        $this->error = REQUEST_ERROR_TYPE::NOERROR;
    }

    ///Getters
    public function GetError()
    {
        echo "\n----";
        var_dump($this->error);

        return $this->error;
    }

    public function GetMessege()
    {
        return $this->messege;
    }

    public function GetElement()
    {
        return $this->element;
    }

    //End Getters

    ///Setters
    public function SetError($error)
    {
        $return = false;
        if ($error != null) {
            $this->error = $error;
            $return = true;
        }

        return $return;
    }

    public function SetMessege($messege)
    {
        $return = false;
        if ($messege != null && $messege != '') {
            $this->messege = $messege;
            $return = true;
        }

        return $return;
    }

    public function SetElement($element)
    {
        $return = false;
        if ($element != null) {
            $this->element = $element;
            $return = true;
        }

        return $return;
    }

    // End Setters
}
