<?php

class Cryptografia {

    private function encrypt_decrypt($action, $string) {
		$output = false;
        $key = hash('sha256', '0j6X6EBFyw');
        $iv = substr(hash('sha256', 'qdzZG8uQgh'), 0, 16);

        if ($action == 'encrypt') {	
			$output = openssl_encrypt($string, 'AES-256-CBC', $key, 0, $iv);
            $output = base64_encode($output);
        } else if ($action == 'decrypt') {
            $output = openssl_decrypt(base64_decode($string), 'AES-256-CBC', $key, 0, $iv);
        }
        return $output;
    }

    function encrypt($string) {
        return $this->encrypt_decrypt('encrypt', $string);
    }
    function decrypt($string) {
        return $this->encrypt_decrypt('decrypt', $string);
    }
}