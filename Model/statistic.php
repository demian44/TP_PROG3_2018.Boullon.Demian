<?php
class Statisctic
{
    private $mozo;
    private $mozoId;
    private $mozoEvaluation;
    private $cocineros;
    private $mesaEvaluation;
    private $mesaCode;
    private $orderCode;
    private $comentario;

    /// Getters
    public function GetMozo()
    {
        return $this->mozo;
    }
    public function GetMozoEvaluation()
    {
        return $this->mozoEvaluation;
    }
    public function GetMozoId()
    {
        return $this->mozoId;
    }
    public function GetMesaEvaluation()
    {
        return $this->mesaEvaluation;
    }

    public function GetCocineros()
    {
        return $this->cocineros;
    }

    public function GetMesaCode()
    {
        return $this->mesaCode;
    }

    public function GetComentario()
    {
        return $this->comentario;
    }

    public function GetOrderCode()
    {
        return $this->orderCode;
    }

    public function GetRestaurantEvaluation()
    {
        return $this->restaurantEvaluation;
    }

    // End Getters

    ///Setters
    public function SetMozoId(int $mozoId)
    {
        $this->mozoId = $mozoId;
    }

    public function SetMozoEvaluation($mozoEvaluation)
    {
        $this->mozoEvaluation = $mozoEvaluation;
    }

    public function SetMozo(string $mozo)
    {
        $this->mozo = $mozo;
    }
    public function SetMesaCode(string $mesaCode)
    {
        $this->mesaCode = $mesaCode;
    }

    public function SetOrderCode(string $orderCode)
    {
        $this->orderCode = $orderCode;
    }

    public function SetMesaEvaluation(string $mesaEvaluation)
    {
        $this->mesaEvaluation = $mesaEvaluation;
    }
    public function SetRestaurantEvaluation(int $restaurantEvaluation)
    {
        $this->restaurantEvaluation = $restaurantEvaluation;
    }
    public function SetRestaurantComentario(string $comentario)
    {
        $this->comentario = $comentario;
    }

    public function SetCocineros(array $cocineros)
    {
        $this->cocineros = $cocineros;
    }

    public function ToJson()
    {
        $statistics["mozo"] = $this->mozo;
        $statistics["mozoId"] = $this->mozoId;
        $statistics["cocineros"] = $this->cocineros;
        $statistics["mesaCode"] = $this->mesaCode;
        $statistics["orderCode"] = $this->orderCode;
        return $statistics;
    }

}
