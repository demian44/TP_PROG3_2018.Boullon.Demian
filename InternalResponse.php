<?php
class InternalResponse
{
    private $errorCode;
    private $messege;
    private $element;

    public function __construct()
    {
        $this->errorCode = 0;       
    }
    ///Getters
    public function GetErrorCode()
    {
        return $this->errorCode;
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
    public function SetErrorCode($errorCode)
    {
        $return = false;
        if ($errorCode != null) {
            $this->errorCode = $errorCode;
            $return = true;
        }
        return $return;
    }
    public function SetMessege($messege)
    {
        $return = false;
        if ($messege != null && $messege != "") {
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