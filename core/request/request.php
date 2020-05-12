<?php




/**
 * @author Петухов Леонид <l.petuhov@okonti.ru>
 * @package core
 */
class request {
	/** Массив входящих переменных со значениями
	 * @var array $_request
	 */
	private $_request          = [];
	/** Объект класса */
	private static $_object    = null;





	/** Конструктор класса
	 * Приватный, т.к. требуется создавать только один объект класса
	 */
	private function __construct() {
		# Получаем входящие данные
		$this->_set_request();
		$this->_set_page();
	}





	/** Деструктор класса */
	public function __destruct() {}





	/** */
	public static function call() {
		# Работаем через static а не через self, что бы получать объект вызывающего класса
		if (null === static::$_object) {
			static::$_object = new static();
		}
		return static::$_object;
	}





	/** Получаем данные о странице */
	private function _set_page() {
		$this->_request['sys_page'] = '/';
		# При запуске через сервер
		if (isset($_SERVER['REQUEST_URI'])) {
			$this->_request['sys_page'] = explode('?',$_SERVER['REQUEST_URI'])[0];
		# При запуске через командную строку
		} else {
			foreach ($_SERVER['argv'] as $arg ) {
				if (strpos($arg ,'=')) {
					list ($key, $val ) = explode("=", $arg);
					if ($key == 'sys_page') {
						$this->_request['sys_page'] = $val;
						break;
					}
				}
			}
		}
	}





	/** Получаем данные о методе */
	private function _set_method() {
		$this->_request['sys_method'] = 'GET';
		# При запуске через сервер
		if (isset($_SERVER['REQUEST_METHOD'])) {
			$this->_request['sys_method'] = explode('?',$_SERVER['REQUEST_METHOD'])[0];
		# При запуске через командную строку
		} else {
			foreach ($_SERVER['argv'] as $arg ) {
				if (strpos($arg ,'=')) {
					list ($key, $val ) = explode("=", $arg);
					if ($key == 'sys_method') {
						$this->_request['sys_method'] = $val;
						break;
					}
				}
			}
		}
	}





	/** Получаем данные запроса */
	private function _set_request() {
		# При запуске через сервер
		if (isset($_SERVER['REQUEST_METHOD'])) {
			# Изначально заложен приоритет POST -> GET
			$this->_request = $_REQUEST;
		# При запуске через командную строку
		} else {
			foreach ($_SERVER['argv'] as $arg ) {
				if (strpos($arg ,'=')) {
					list ($key, $val ) = explode("=", $arg);
					$this->_request[$key] = $val;
				}
			}
		}
	}





	/** */
	public function isset($key) {
		if (isset($this->_request[$key])) {
			return true;
		}
		return false;
	}





	/** */
	public function get($key, $default = null) {
		if (isset($this->_request[$key])) {
			return $this->_request[$key];
		}
		return $default;
	}





	/** */
	public function get_list() {
		return $this->_request;
	}





/**/
}



?>
