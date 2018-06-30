<?php
    class OrderItem extends Entity
    {
        private $orderId;
        private $name;
        private $description;
        private $cant;
        private $available;

        /// Getters
        public function GetName()
        {
            return $this->name;
        }

        public function GetDescription()
        {
            return $this->description;
        }

        public function GetCodigoPedido()
        {
            return $this->codigoPedido;
        }

        public function GetCant()
        {
            return $this->cant;
        }

        public function GetAvailable()
        {
            return $this->available;
        }

        // End Getters

        ///Setters
        public function SetName($name)
        {
            $retorno = false;
            if (is_string($name) && $name != '') {
                $this->name = $name;
                $retorno = true;
            }

            return $retorno;
        }

        public function SetCodigoPedido($orderId)
        {
            $retorno = false;
            if (is_int($orderId) && $orderId >= 0) {
                $this->codigoPedido = $orderId;
                $retorno = true;
            }

            return $retorno;
        }

        public function SetCodigoDescription($description)
        {
            $retorno = false;
            if (is_string($description) && count_chars($description) == 5) {
                $this->description = $description;
                $retorno = true;
            }

            return $retorno;
        }

        public function SetCant($cant)
        {
            $retorno = false;
            if (is_string($cant) && count_chars($cant) == 5) {
                $this->cant = $cant;
                $retorno = true;
            }

            return $retorno;
        }
    }
