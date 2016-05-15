#php加密解密类
	<?php
    /**
     * Seedit_Aes
     *
     * @version $Id$
     * @copyright
     */

    /**
     * Aes类
     *
     * @author skc
     */
    class Seedit_Aes {

    public function encode($value,$key) {
        $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
        $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
        $plaintext_utf8 = $value;
        //md5做效验值
        $md5_key = substr(md5($plaintext_utf8),8,16);
        $plaintext_utf8 = $md5_key . $plaintext_utf8;
        $ciphertext = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $plaintext_utf8, MCRYPT_MODE_CBC, $iv);
        # prepend the IV for it to be available for decryption
        $ciphertext = $iv . $ciphertext;
        # encode the resulting cipher text so it can be represented by a string
        $ciphertext_base64 = base64_encode($ciphertext);
        return  $ciphertext_base64;
    }

    public function decode($value,$key) {
        $ciphertext_dec = base64_decode($value);
        $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
        # retrieves the IV, iv_size should be created using mcrypt_get_iv_size()
        $iv_dec = substr($ciphertext_dec, 0, $iv_size);
        # retrieves the cipher text (everything except the $iv_size in the front)
        $ciphertext_dec = substr($ciphertext_dec, $iv_size);
        # may remove 00h valued characters from end of plain text
        $plaintext = @mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, $ciphertext_dec, MCRYPT_MODE_CBC, $iv_dec);
        $plaintext = rtrim($plaintext, "\0");
        //md5效验
        $md5_key   = substr($plaintext,0,16);
        $plaintext = substr($plaintext,16);
        if($md5_key === substr(md5($plaintext),8,16))
            return $plaintext;
        else
            return false;
    }
	}
