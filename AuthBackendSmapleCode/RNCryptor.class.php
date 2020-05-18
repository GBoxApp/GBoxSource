<?php

class Cryptor
{
    const DEFAULT_SCHEMA_VERSION = 3;

    protected $config;

    public function generateKey($salt, $password, $version = self::DEFAULT_SCHEMA_VERSION)
    {
        $this->configure($version);

        return $this->makeKey($salt, $password);
    }

    protected function aesCtrLittleEndianCrypt($payload, $key, $iv)
    {
        $numOfBlocks = ceil(strlen($payload) / strlen($iv));
        $counter = '';
        for ($i = 0; $i < $numOfBlocks; ++$i) {
            $counter .= $iv;

            // Yes, the next line only ever increments the first character
            // of the counter string, ignoring overflow conditions.  This
            // matches CommonCrypto's behavior!
            $iv[0] = chr(ord($iv[0]) + 1);
        }

        return $payload ^ $this->encryptInternal($key, $counter, 'ecb');
    }

    protected function encryptInternal($key, $payload, $mode, $iv = null)
    {
        return openssl_encrypt($payload, $this->config->algorithm . $mode, $key, OPENSSL_RAW_DATA, (string)$iv);
    }

    protected function makeHmac(stdClass $components, $hmacKey)
    {
        $hmacMessage = '';
        if ($this->config->hmac->includesHeader) {
            $hmacMessage .= ''
                . $components->headers->version
                . $components->headers->options
                . (isset($components->headers->encSalt) ? $components->headers->encSalt : '')
                . (isset($components->headers->hmacSalt) ? $components->headers->hmacSalt : '')
                . $components->headers->iv;
        }

        $hmacMessage .= $components->ciphertext;

        $hmac = hash_hmac($this->config->hmac->algorithm, $hmacMessage, $hmacKey, true);

        if ($this->config->hmac->includesPadding) {
            $hmac = str_pad($hmac, $this->config->hmac->length, chr(0));
        }
    
        return $hmac;
    }

    protected function makeKey($salt, $password)
    {
        if ($this->config->truncatesMultibytePasswords) {
            $utf8Length = mb_strlen($password, 'utf-8');
            $password = substr($password, 0, $utf8Length);
        }

        $algo = $this->config->pbkdf2->prf;
        $iterations = $this->config->pbkdf2->iterations;
        $length = $this->config->pbkdf2->keyLength;

        return hash_pbkdf2($algo, $password, $salt, $iterations, $length, true);
    }

    protected function configure($version)
    {
        $config = new stdClass;

        $config->algorithm = 'aes-256-';
        $config->saltLength = 8;
        $config->ivLength = 16;

        $config->pbkdf2 = new stdClass;
        $config->pbkdf2->prf = 'sha1';
        $config->pbkdf2->iterations = 10000;
        $config->pbkdf2->keyLength = 32;

        $config->hmac = new stdClass();
        $config->hmac->length = 32;

        if (!$version) {
            $this->configureVersionZero($config);
        } elseif ($version <= 3) {
            $config->mode = 'cbc';
            $config->options = 1;
            $config->hmac->algorithm = 'sha256';
            $config->hmac->includesPadding = false;

            switch ($version) {
                case 1:
                    $config->hmac->includesHeader = false;
                    $config->truncatesMultibytePasswords = true;
                    break;

                case 2:
                    $config->hmac->includesHeader = true;
                    $config->truncatesMultibytePasswords = true;
                    break;

                case 3:
                    $config->hmac->includesHeader = true;
                    $config->truncatesMultibytePasswords = false;
                    break;
            }
        } else {
            throw new \RuntimeException('Unsupported schema version ' . $version);
        }

        $this->config = $config;
    }

    private function configureVersionZero(stdClass $config)
    {
        $config->mode = 'ctr';
        $config->options = 0;
        $config->hmac->includesHeader = false;
        $config->hmac->algorithm = 'sha1';
        $config->hmac->includesPadding = true;
        $config->truncatesMultibytePasswords = true;
    }
}

/**
 * RNEncryptor for PHP
 *
 * Encrypt data interchangeably with Rob Napier's Objective-C implementation
 * of RNCryptor
 */
class Encryptor extends Cryptor
{
    /**
     * Encrypt plaintext using RNCryptor's algorithm
     *
     * @param string $plaintext Text to be encrypted
     * @param string $password Password to use
     * @param int $version (Optional) RNCryptor schema version to use.
     * @throws \Exception If the provided version (if any) is unsupported
     * @return string Encrypted, Base64-encoded string
     */
    public function encrypt($plaintext, $password, $version = Cryptor::DEFAULT_SCHEMA_VERSION, $base64Encode = true)
    {
        $this->configure($version);

        $components = $this->makeComponents($version);
        $components->headers->encSalt = $this->makeSalt();
        $components->headers->hmacSalt = $this->makeSalt();
        $components->headers->iv = $this->makeIv($this->config->ivLength);

        $encKey = $this->makeKey($components->headers->encSalt, $password);
        $hmacKey = $this->makeKey($components->headers->hmacSalt, $password);

        return $this->encryptFromComponents($plaintext, $components, $encKey, $hmacKey, $base64Encode);
    }

    public function encryptWithArbitrarySalts(
        $plaintext,
        $password,
        $encSalt,
        $hmacSalt,
        $iv,
        $version = Cryptor::DEFAULT_SCHEMA_VERSION,
        $base64Encode = true
    ) {
        $this->configure($version);

        $components = $this->makeComponents($version);
        $components->headers->encSalt = $encSalt;
        $components->headers->hmacSalt = $hmacSalt;
        $components->headers->iv = $iv;

        $encKey = $this->makeKey($components->headers->encSalt, $password);
        $hmacKey = $this->makeKey($components->headers->hmacSalt, $password);

        return $this->encryptFromComponents($plaintext, $components, $encKey, $hmacKey, $base64Encode);
    }

    public function encryptWithArbitraryKeys(
        $plaintext,
        $encKey,
        $hmacKey,
        $iv,
        $version = Cryptor::DEFAULT_SCHEMA_VERSION,
        $base64Encode = true
    ) {
        $this->configure($version);

        $this->config->options = 0;

        $components = $this->makeComponents($version);
        $components->headers->iv = $iv;

        return $this->encryptFromComponents($plaintext, $components, $encKey, $hmacKey, $base64Encode);
    }

    private function makeComponents($version)
    {
        $components = new stdClass;
        $components->headers = new stdClass;
        $components->headers->version = chr($version);
        $components->headers->options = chr($this->config->options);

        return $components;
    }

    private function encryptFromComponents($plaintext, stdClass $components, $encKey, $hmacKey, $base64encode = true)
    {
        $iv = $components->headers->iv;
        if ($this->config->mode == 'ctr') {
            $components->ciphertext = $this->aesCtrLittleEndianCrypt($plaintext, $encKey, $iv);
        } else {
            $components->ciphertext = $this->encryptInternal($encKey, $plaintext, 'cbc', $iv);
        }

        $data = $components->headers->version
            . $components->headers->options
            . ($components->headers->encSalt ? $components->headers->encSalt : '')
            . ($components->headers->hmacSalt ? $components->headers->hmacSalt : '')
            . $components->headers->iv
            . $components->ciphertext
            . $this->makeHmac($components, $hmacKey);

        return ($base64encode ? base64_encode($data) : $data);
    }

    private function makeSalt()
    {
        return $this->makeIv($this->config->saltLength);
    }

    private function makeIv($blockSize)
    {
        return openssl_random_pseudo_bytes($blockSize);
    }
}


/**
 * RNDecryptor for PHP
 *
 * Decrypt data interchangeably with Rob Napier's Objective-C implementation
 * of RNCryptor
 */
class Decryptor extends Cryptor
{
    /**
     * Decrypt RNCryptor-encrypted data
     *
     * @param string $base64EncryptedData Encrypted, Base64-encoded text
     * @param string $password Password the text was encoded with
     * @throws Exception If the detected version is unsupported
     * @return string|false Decrypted string, or false if decryption failed
     */
    public function decrypt($encryptedBase64Data, $password)
    {
        $components = $this->unpackEncryptedBase64Data($encryptedBase64Data);

        if (!$this->hmacIsValid($components, $password)) {
            return false;
        }

        $key = $this->makeKey($components->headers->encSalt, $password);
        if ($this->config->mode == 'ctr') {
            return $this->aesCtrLittleEndianCrypt($components->ciphertext, $key, $components->headers->iv);
        }

        $iv = (string)$components->headers->iv;
        $method = $this->config->algorithm . 'cbc';

        return openssl_decrypt($components->ciphertext, $method, $key, OPENSSL_RAW_DATA, (string)$iv);
    }

    private function unpackEncryptedBase64Data($encryptedBase64Data, $isPasswordBased = true)
    {
        $binaryData = base64_decode($encryptedBase64Data);

        $components = new stdClass;
        $components->headers = $this->parseHeaders($binaryData, $isPasswordBased);

        $components->hmac = substr($binaryData, -$this->config->hmac->length);

        $offset = $components->headers->length;
        $length = strlen($binaryData) - $offset - strlen($components->hmac);

        $components->ciphertext = substr($binaryData, $offset, $length);

        return $components;
    }

    private function parseHeaders($binData, $isPasswordBased = true)
    {
        $offset = 0;

        $versionChr = $binData[0];
        $offset += strlen($versionChr);

        $this->configure(ord($versionChr));

        $optionsChr = $binData[1];
        $offset += strlen($optionsChr);

        $encSalt = null;
        $hmacSalt = null;
        if ($isPasswordBased) {
            $encSalt = substr($binData, $offset, $this->config->saltLength);
            $offset += strlen($encSalt);

            $hmacSalt = substr($binData, $offset, $this->config->saltLength);
            $offset += strlen($hmacSalt);
        }

        $iv = substr($binData, $offset, $this->config->ivLength);
        $offset += strlen($iv);

        $headers = (object)[
            'version' => $versionChr,
            'options' => $optionsChr,
            'encSalt' => $encSalt,
            'hmacSalt' => $hmacSalt,
            'iv' => $iv,
            'length' => $offset
        ];

        return $headers;
    }

    private function hmacIsValid($components, $password)
    {
        $hmacKey = $this->makeKey($components->headers->hmacSalt, $password);

        return hash_equals($components->hmac, $this->makeHmac($components, $hmacKey));
    }
}
