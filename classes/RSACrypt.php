<?php
// statically decompiled from RSACrypt.php  [structured; all 10 record(s) structured]



class RsaCrypt
{
    protected $private;
    protected $public;

    final public function __construct()
    {
        if (function_exists('openssl_get_publickey') === false || function_exists('openssl_public_encrypt') === false || function_exists('openssl_get_privatekey') === false || function_exists('openssl_private_decrypt') === false) {
            throw new RuntimeException('Not all the functions of openssl.');
        }
    }

    final public function setPublicKey($key)
    {
        if ($key === null || empty($key) || file_exists($key) === false) {
            throw new RuntimeException('Wrong key.');
        } else {
            $this->public = $key;
            return true;
        }
    }

    final public function getPublicKey()
    {
        return $this->public === null ? false : $this->public;
    }

    final public function setPrivateKey($key)
    {
        if ($key === null || empty($key) || file_exists($key) === false) {
            throw new RuntimeException('Wrong key.');
        } else {
            $this->private = $key;
            return true;
        }
    }

    final public function getPrivateKey()
    {
        return $this->private === null ? false : $this->private;
    }

    final public function encryptWithPublicKey($data)
    {
        if ($data === null || empty($data) || is_string($data) === false) {
            throw new RuntimeException('Needless to encrypt.');
        } else {
            if ($this->public === null || empty($this->public)) {
                throw new RuntimeException('You need to set the public key.');
            } else {
                $key = file_get_contents($this->public);
                if ($key) {
                    $key = openssl_get_publickey($key);
                    openssl_public_encrypt($data, $encrypted, $key);
                    return chunk_split(base64_encode($encrypted));
                } else {
                    return false;
                }
            }
        }
    }

    final public function decryptWithPrivateKey($data)
    {
        if ($data === null || empty($data) || is_string($data) === false) {
            throw new RuntimeException('Needless to encrypt.');
        } else {
            if ($this->private === null || empty($this->private)) {
                throw new RuntimeException('You need to set the private key.');
            } else {
                $key = file_get_contents($this->private);
                if ($key) {
                    $key = openssl_get_privatekey($key);
                    openssl_private_decrypt(base64_decode($data), $result, $key);
                    return $result;
                }
            }
        }
    }

    final public function encryptWithPrivateKey($data)
    {
        if ($data === null || empty($data) || is_string($data) === false) {
            throw new RuntimeException('Needless to encrypt.');
        } else {
            if ($this->private === null || empty($this->private)) {
                throw new RuntimeException('You need to set the private key.');
            } else {
                $key = file_get_contents($this->private);
                if ($key) {
                    $key = openssl_get_privatekey($key);
                    openssl_private_encrypt($data, $encrypted, $key);
                    return chunk_split(base64_encode($encrypted));
                } else {
                    return false;
                }
            }
        }
    }

    final public function decryptWithPublicKey($data)
    {
        if ($data === null || empty($data) || is_string($data) === false) {
            throw new RuntimeException('Needless to encrypt.');
        } else {
            if ($this->public === null || empty($this->public)) {
                throw new RuntimeException('You need to set the public key.');
            } else {
                $key = file_get_contents($this->public);
                if ($key) {
                    $key = openssl_get_publickey($key);
                    openssl_public_decrypt(base64_decode($data), $result, $key);
                    return $result;
                }
            }
        }
    }
}
