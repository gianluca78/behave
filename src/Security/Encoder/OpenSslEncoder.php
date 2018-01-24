<?php
namespace App\Security\Encoder;

class OpenSslEncoder {

    private $cypherMethod;
    private $iv;
    private $privateKey;

    public function __construct($cypherMethod, $iv, $privateKey)
    {
        $this->cypherMethod = $cypherMethod;
        $this->iv = $iv;
        $this->privateKey = $privateKey;
    }

    public function encrypt($value)
    {
        return openssl_encrypt($value, $this->cypherMethod, $this->privateKey, false, $this->iv);
    }

    public function decrypt($value)
    {
        return openssl_decrypt($value, $this->cypherMethod, $this->privateKey, false, $this->iv);
    }

} 