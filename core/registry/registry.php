<?php





/**
 * @author Петухов Леонид <l.petuhov@okonti.ru>
 * @package core
 */
class registry {
	/**
	 * @param array $_keys Массив ключей со значениями
	 */
	private $_value             = [];
	/**
	 * @param array $_lock Массив с указанием блокированных (неизменяемых) ключей
	 */
	private $_lock             = [];
	/**
	 * @param object $_object Единственный объект класса
	 */
	private static $_object    = null;





	/** Конструктор объекта
	 * Приватный, т.к. требуется создавать только один объект класса
	 */
	private function __construct() {}





	/** Деструктор класса */
	public function __destruct() {}





	/** Деструктор класса */
	public function __debugInfo() {
		$lock = array_intersect_key($this->_value, $this->_lock);
		$unlock = array_diff_key($this->_value, $this->_lock);
		ksort($lock);
		ksort($unlock);
		return ['value' => $this->_value, 'lock' => $this->_lock];
		return ['lock' => $lock, 'unlock' => $unlock];
	}





	/** */
	public static function call() {
		# Работаем через static а не через self, что бы получать объект вызывающего класса
		if (null === static::$_object) {
			static::$_object = new static();
		}
		return static::$_object;
	}





	/** Заполняет массив ключей данными полученными из скласса команды (массив)
	 * @param string $name Имя класса комманды
	 * @param array $arr_set Массив настроек
	 * @return void
	 */
	public function cmd($name, $arr_set = []) {
		# Подключаем класс комманды
		$cmd = new $name();
		# Получаем данные
		$arr_key = $cmd->execute($arr_set);
		# Проверяем на массив
		if (is_array($arr_key)) {
			# Обрабатываем массив
			foreach ($arr_key as $k => $v) {
				# Заполняет/обновляет ключ регистра (блокируем)
				$this->set($k, $v, true);
			}
		}
	}





	/** Заполняет/обновляет ключ регистра
	 * @param string $key Ключ регистра
	 * @param string/bool/int $value Значение регистра
	 * @param bool $lock Ключ регистра
	 * @return bool true - успешно, false - ошибка
	 */
	public function set($key, $value, $lock = false) {
		if (isset($this->_lock[$key])) {
			return false;
		}
		$this->_value[$key] = $value;
		if ($lock) {
			$this->_lock[$key] = true;
		}
		return true;
	}





	/** Возвращает ключ регистра
	 * @param string $key Ключ регистра
	 * @param string/bool/int/null $default Значение по умолчанию (null)
	 * @return string/bool/int/null Значение ключа или значение переменной $default
	 */
	public function get($key, $default = null) {
		if (isset($this->_value[$key])) {
			return  $this->_value[$key];
		}
		return $default;
	}





/**/
}



?>
