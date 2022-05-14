<?php
namespace src;
/**
* @author jsstoni
* script by jsstoni
* Secure
*/
class Secure {
	public $type = '';

	public function __construct() {
		array_walk($_POST, array(&$this, '_POST'));
		array_walk($_GET, array(&$this, '_GET'));
	}

	private function _clean($str) {
		if (!empty($str)) $str = is_array($str) ? array_map('self::_clean', $str) : str_replace('\\', '&bsol;', htmlspecialchars((get_magic_quotes_gpc() ? stripslashes($str) : $str), ENT_QUOTES, 'UTF-8'));
		else $str = NULL;
		return $str;
	}

	public function _GET($str, $key) {
		$_GET[$key] = $this->_clean($str);
		return $_GET[$key];
	}

	public function _POST($str, $key) {
		$_POST[$key] = $this->_clean($str);
		return $_POST[$key];
	}
}
?>