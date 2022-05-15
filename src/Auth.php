<?php
namespace src;
class Auth
{
	private static $method = "aes-256-ecb";
    private static $clave = "cE&ED#24=BE&C937E.=8";
	public function encode($id)
	{
		$iv = base64_encode(openssl_random_pseudo_bytes(openssl_cipher_iv_length(self::$method)));
		return base64_encode(openssl_encrypt(
			$id, //string a codificar
			self::$method,
			self::$clave,
			true,
			$iv
		));
	}

	public function decode($code)
	{
		$iv = base64_encode(openssl_random_pseudo_bytes(openssl_cipher_iv_length(self::$method)));
		$valor = base64_decode($code);
        return openssl_decrypt($valor, self::$method, self::$clave, true, $iv);
	}

	public static function getAuthorization()
    {
		$headers = apache_request_headers();
		if (isset($headers['Authorization'])) {
			return self::decode($headers['Authorization']);
		}
		return false;
	}
}