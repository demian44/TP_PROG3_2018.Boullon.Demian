<?php
use \Firebase\JWT\JWT;
    class SecurityToken{
        private $key;
        public function __construct()
        {
            $this->key = "DigitalAustral";
        }
        public function Encode($token)
        {
            $jwt = JWT::encode($token, $this->key);
            return $jwt;
        }
        public function Decode($jwt)
        {
            $decoded = JWT::decode($jwt, $this->key, array('HS256'));
            return $decoded;
        }
    }
?>