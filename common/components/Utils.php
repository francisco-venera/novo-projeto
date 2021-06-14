<?php

namespace common\components;

class Utils
{
    const ACTION_ENCRYPT = 'encrypt';
    const ACTION_DECRYPT = 'decrypt';

    /**
     * Simple method to encrypt or decrypt a plain text string
     * initialization vector(IV) has to be the same when encrypting and decrypting
     *
     * @param string $action: can be 'encrypt' or 'decrypt'
     * @param string $string: string to encrypt or decrypt
     *
     * @see https://gist.github.com/joashp/a1ae9cb30fa533f4ad94
     * @return string
     */
    public static function encrypt_decrypt($action, $string)
    {
        $output = false;
        $encrypt_method = "AES-256-CBC";
        /*
         * Caso necessite outra hash para criptografrar informações internas,
         * a "chave params"[ssl_encrypt_key_external_information] deve ser passado por parâmetro para que a função recupere a hash correta desejada.
         */
        $secret_key = \Yii::$app->params['ssl_encrypt_key_external_information'];
        $secret_iv = \Yii::$app->params['ssl_encrypt_iv_external_information'];

        // hash
        $key = hash('sha256', $secret_key);

        // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
        $iv = substr(hash('sha256', $secret_iv), 0, 16);
        switch ($action) {
            case self::ACTION_ENCRYPT:
                $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
                break;
            case self::ACTION_DECRYPT:
                $output = openssl_decrypt($string, $encrypt_method, $key, 0, $iv);
                break;
            default:
                $output = false;
        }

        return $output;
    }
    
    /**
     * metodo para formatar string
     *
     * @param string $value
     * @param int $limit
     *
     * @return string
     */
    public function formatDescription($value, $limit = 50)
    {
        if(strlen($value) > $limit){
            $value = substr($value, 0, $limit) . '...';
        }
        return $value;
    }
}