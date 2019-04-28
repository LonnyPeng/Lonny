<?php

namespace Plugin;

/**
 * 私钥加密-》公钥解密 
 * 公钥加密-》私钥解密
 */
class RSA
{
    private $privateKey = '';//私钥（用于用户加密）
    private $publicKey = '';//公钥（用于服务端数据解密）
 
    public function __construct()
    {
        $this->privateKey = openssl_pkey_get_private(file_get_contents(__DIR__ . '/../Pem/rsa_private.pem'));//私钥，用于加密
        $this->publicKey = openssl_pkey_get_public(file_get_contents(__DIR__ . '/../Pem/rsa_public.pem'));//公钥，用于解密
    }
    
    /**
     * 私钥加密
     * @param 原始数据 $data
     * @return 密文结果 string
     */
    public function encryptByPrivateKey($data) 
    {
        $crypto = '';
        foreach (str_split($data, 117) as $chunk) {
            openssl_private_encrypt($chunk, $encrypted, $this->privateKey, OPENSSL_PKCS1_PADDING);//私钥加密
            $crypto .= $encrypted;
        }
        $encrypted = $this->urlsafe_b64encode($crypto);//加密后的内容通常含有特殊字符，需要编码转换下，在网络间通过url传输时要注意base64编码是否是url安全的
        
        return $encrypted;
    }
    
    /**
     * 私钥解密
     * @param 密文数据 $data
     * @return 原文数据结果 string
     */
    public function decryptByPrivateKey($data)
    {
        $crypto = '';
        foreach (str_split($this->urlsafe_b64decode($data), 128) as $chunk) {
            openssl_private_decrypt($chunk, $encrypted, $this->privateKey, OPENSSL_PKCS1_PADDING);//私钥解密
            $crypto .= $encrypted;
        }

        return $crypto;
    }
    
    /**
     * 公钥加密
     * @param 原文数据 $data
     * @return 加密结果 string
     */
    public function encryptByPublicKey($data) 
    {
        $crypto = '';
        foreach (str_split($data, 117) as $chunk) {
            openssl_public_encrypt($chunk, $decrypted, $this->publicKey, OPENSSL_PKCS1_PADDING);//公钥加密
            $crypto .= $decrypted;
        }
        $encrypted = $this->urlsafe_b64encode($crypto);

        return $encrypted;
    }
    
    /**
     * 公钥解密
     * @param 密文数据 $data
     * @return 原文结果 string
     */
    public function decryptByPublicKey($data) 
    {
        $crypto = '';
        foreach (str_split($this->urlsafe_b64decode($data), 128) as $chunk) {
            openssl_public_decrypt($chunk, $decrypted, $this->publicKey, OPENSSL_PKCS1_PADDING);//公钥解密
            $crypto .= $decrypted;
        }

        return $crypto;
    }

    //加密码时把特殊符号替换成URL可以带的内容
    public function urlsafe_b64encode($string)
    {
        $data = base64_encode($string);
        $data = str_replace(array('+', '/', '='), array('-', '_', ''), $data);

        return $data;
    }

    //解密码时把转换后的符号替换特殊符号
    public function urlsafe_b64decode($string)
    {
        $data = str_replace(array('-', '_'), array('+', '/'), $string);
        $mod4 = strlen($data) % 4;
        if ($mod4) {
            $data .= substr('====', $mod4);
        }

        return base64_decode($data);
    }
    
    public function __destruct()
    {
        openssl_free_key($this->privateKey);
        openssl_free_key($this->publicKey);
    }
}