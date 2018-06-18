<?php
    class Order extends Entity
    {
        private $cliente;
        private $code;
        private $items;
        private $mesaId;
        private $mesaCode;
        private $orderedTime;
        private $estimateTime;
        private $deliveredTime;
        private $photo;

        public function __construct($cliente, $code, $mesaId, $items)
        {
            $this->cliente = $cliente;
            $this->code = $code;
            $this->mesaId = $mesaId;
            $this->items = $items;
        }

        /// Getters
        public function GetCliente()
        {
            return $this->cliente;
        }

        public function GetItems()
        {
            return $this->items;
        }

        public function GetPhoto()
        {
            return $this->photo;
        }

        public function GetCode()
        {
            return $this->code;
        }

        public function GetMesaId()
        {
            return $this->mesaId;
        }

        public function GetMesaCode()
        {
            return $this->mesaCode;
        }

        public function GetOrderedTime()
        {
            return $this->orderedTime;
        }

        public function GetEstimateTime()
        {
            return $this->estimateTime;
        }

        public function GetDeliveredTime()
        {
            return $this->deliveredTime;
        }

        // End Getters

        ///Setters
        public function SetCliente($cliente)
        {
            $retorno = false;
            if (is_string($cliente) && $cliente != '') {
                $this->cliente = $cliente;
                $retorno = true;
            }

            return $retorno;
        }

        public function SetPhoto($photo)
        {
            $retorno = false;
            if (is_string($photo)) {
                $this->photo = $photo;
                $retorno = true;
            }

            return $retorno;
        }

        public function SetCode($code)
        {
            $retorno = false;
            if (is_string($code) && count_chars($mesaId) == 5) {
                $this->code = $code;
                $retorno = true;
            }

            return $retorno;
        }

        public function SetMesaId($mesaId)
        {
            $retorno = false;
            if (is_int($mesaId)) {
                $this->mesaId = $mesaId;
                $retorno = true;
            }

            return $retorno;
        }

        public function SetMesaCode($mesaCode)
        {
            $retorno = false;
            if (is_string($mesaCode) && count_chars($mesaCode) == 5) {
                $this->mesaCode = $mesaCode;
                $retorno = true;
            }

            return $retorno;
        }

        public function SetOrderedTime($orderedTime)
        {
            $retorno = false;
            //if (is_int($orderedTime)) {
            $this->orderedTime = $orderedTime;
            $retorno = true;
            //}

            return $retorno;
        }

        public function SetItems($items)
        {
            $return = false;
            if (is_array($items) && count($items) > 0) {
                $this->items = $items;
                $return = true;
            }

            return $return;
        }

        public function SetItem($item)
        {
            $return = false;
            if (!is_null($item)) {
                $return = true;
            }

            if ($return) {
                if (!is_array($this->items)) {
                    $this->items = [];
                }
                array_push($this->items, $item);
            }

            return $return;
        }

        public function SetEstimateTime($estimateTime)
        {
            //Hacer bien
            $retorno = false;
            if (is_int($estimateTime)) {
                $this->estimateTime = $estimateTime;
                $retorno = true;
            }

            return $retorno;
        }

        public function CreateTime()
        {
            $this->orderedTime = time();
        }

        public static function generateCode()
        {
            $caracters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $count = strlen($caracters) - 1;
            //Genero un nuevo string con substrings aleatorios de 1 caracter de largo.
            return substr($caracters, rand(0, $count), 1).
                        substr($caracters, rand(0, $count), 1). //1
                        substr($caracters, rand(0, $count), 1). //2
                        substr($caracters, rand(0, $count), 1). //3
                        substr($caracters, rand(0, $count), 1). //4
                        substr($caracters, rand(0, $count), 1); //5
        }
    }
