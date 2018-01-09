<?php
namespace App\Doctrine\DBAL\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\TextType;
use App\Security\Encoder\OpenSslEncoder;

class EncryptedTextType extends TextType {

    const MTYPE = 'encrypted_text';

    private $cypherMethod;
    private $iv;
    private $privateKey;

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        $openSslEncoder = new OpenSslEncoder($this->cypherMethod, $this->iv, $this->privateKey);

        return $openSslEncoder->decrypt($value);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        $openSslEncoder = new OpenSslEncoder($this->cypherMethod, $this->iv, $this->privateKey);

        return $openSslEncoder->encrypt($value);
    }

    public function getName()
    {
        return self::MTYPE;
    }

    /**
     * @param mixed $cypherMethod
     */
    public function setCypherMethod($cypherMethod)
    {
        $this->cypherMethod = $cypherMethod;
    }

    /**
     * @param mixed $iv
     */
    public function setIv($iv)
    {
        $this->iv = $iv;
    }

    /**
     * @param mixed $privateKey
     */
    public function setPrivateKey($privateKey)
    {
        $this->privateKey = $privateKey;
    }
} 