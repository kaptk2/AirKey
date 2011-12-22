<?php

class MY_Encrypt extends CI_Encrypt
{

	function showkey()
	{
		$key = $this->get_key();
		return $key;
	}

	function ssl_encode($data, $key = '')
	{
		// Use the Encrypt.php function get_key to encode the data.
		$key = $this->get_key($key);

		// Set a random salt
		$salt = substr(md5(mt_rand(), true), 8);

		$block = mcrypt_get_block_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
		$pad = $block - (strlen($data) % $block);
		$data = $data . str_repeat(chr($pad), $pad);

		// Setup encryption parameters
		$td = mcrypt_module_open(MCRYPT_RIJNDAEL_128, "", MCRYPT_MODE_CBC, "");


		$key_len =  mcrypt_enc_get_key_size($td);
		$iv_len =  mcrypt_enc_get_iv_size($td);

		$total_len = $key_len + $iv_len;
		$salted = '';
		$dx = '';
		// Salt the key and iv
		while (strlen($salted) < $total_len)
		{
				$dx = md5($dx.$key.$salt, true);
				$salted .= $dx;
		}
		$key = substr($salted,0,$key_len);
		$iv = substr($salted,$key_len,$iv_len);


		mcrypt_generic_init($td, $key, $iv);
		$encrypted_data = mcrypt_generic($td, $data);
		mcrypt_generic_deinit($td);
		mcrypt_module_close($td);

		return chunk_split(base64_encode('Salted__' . $salt . $encrypted_data),32,"\n");
	}
}

?>
