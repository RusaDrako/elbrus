<?php

namespace Elbrus\Framework\right;



/**
 * @author Петухов Леонид <rusadrako@yandex.ru>
 * @package core
 */
class right {
	/** */
	private $_right = [];
	/** */
	private $_lock = [];
	/**
	 * @var object
	 */
	private static $_object = null;










	/** Конструктор объекта */
	private function __construct() {}





	/** Деструктор класса */
	public function __destruct() {}





	/** Вывод объекта в строковом виде */
	public function __debugInfo() {
		return ['right' => $this->_right, 'lock' => $this->_lock];
	}





	/** Вызов объекта
	* @return object Объект модели
	*/
	public static function call(...$args) {
		if (!isset(self::$_object)) {
			self::$_object = new static(...$args);
		}
		return self::$_object;
	}





	/** Добавляет правило доступа
	 * @param string $key Ключ правила доступа
	 * @param string|function $value Алгоритмическое описание правила
	 * @param bool $lock Метка о блокировке правила
	 * @return void
	 */
	public function _add_right($key, $value, $lock = false) {
		if (!isset($this->_lock[$key])) {
			$this->_right[$key] = $value;
			if ($lock) {
				$this->_lock[$key] = true;
			}
		}
	}





	/** Добавляет массив правил доступа (без блокирования)
	 * @param array $array Массив правил доступа
	 * @param string|function $value Алгоритмическое описание правила
	 * @return void
	 */
	public function right_array($array) {
		foreach ($array as $k => $v) {
			$this->_add_right($k, $v);
		}
	}





	/** Добавляет правило доступа
	 * @param string $key Ключ правила доступа
	 * @param string|function $value Алгоритмическое описание правила
	 * @param bool $lock Метка о блокировке правила
	 * @return void
	 */
	public function right($key, $value, $lock = false) {
		$this->_add_right($key, $value, $lock);
	}





	/** Проверяет ключ на массив
	 * @param array|string $key Ключ правила доступа
	 * @return array Массив ключейРезультат проверки
	 */
	public function _key_control($key) {
		if (!is_array($key)) {
			$key = \explode(',', $key);
			foreach ($key as $k => $v) {
				$key[$k] = trim($v);
			}
		}
		return $key;
	}





	/** Проверяет на разрешение доступа указанным группам
	 * @param array|string $key Ключ правила доступа
	 * @return bool Результат проверки
	 */
	public function access($key) {
		# Проверка переданного ключа на массив
		$arr_key = $this->_key_control($key);
		$control = [];
		foreach ($arr_key as $k => $v) {
			if (!isset($this->_right[$v])) { continue;}
			if (is_callable($this->_right[$v])) {
				$control[] = $this->_right[$v]();
			} else {
				$control[] = $this->_right[$v];
			}
		}
		foreach ($control as $v) {
			if ($v) {
				return true;
			}
		}
		return false;
	}





	/** Проверяет на отказ в доступе указанным группам
	 * @param array|string $key Ключ правила доступа
	 * @return bool Результат проверки
	 */
	public function access_denied($key) {
		return !$this->access($key);
	}





	/** Проверяет на разрешение доступа всем кроме указанных групп
	 * @param array|string $key Ключ правила доступа
	 * @return bool Результат проверки
	 */
	public function access_except($key) {
		return $this->access_denied($key);
	}





	/** Проверяет на отказ в доступе всем кроме указанных групп
	 * @param array|string $key Ключ правила доступа
	 * @return bool Результат проверки
	 */
	public function access_denied_except($key) {
		return $this->access($key);
	}





/**/
}
