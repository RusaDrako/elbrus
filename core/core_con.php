<?php

namespace core;

require_once('_inf/inf_core_con.php');
require_once('_inf/inf_core_cmd.php');





/** Загрузка ядра
 * Класс загружается в единственном экземпляре
 * @author Петухов Леонид <l.petuhov@okonti.ru>
 * @package core
 */
class core_con implements _inf\inf_core_con /**/{
	/** Объект последней команды
	 * @var object $_last_cmd
	 */
	private $_obj_last_cmd    = null;
	/** Единственный объект класса
	 * @var object $_object
	 */
	private static $_object    = null;





	/**
	 * Конструктор объекта.
	 * Приватный, т.к. требуется создавать только один объект класса
	 */
	private function __construct($file_map = null) {
		if (!$file_map) {
			$file_map = 'core_map.php';
		}
		$map = require_once($file_map);
		foreach ($map as $v) {
			require_once($v[0] . '.php');
//echo get_called_class() . ': загрузка ' . $v[0];
			$this->cmd('\\' . __NAMESPACE__ . '\\' . $v[0], $v[1]);
//			echo '<hr>';
		}
//		echo '---<hr>';
	}





	/** Деструктор класса */
	public function __destruct() {}





	/** */
	public static function call($file_map = null) {
		# Работаем через static а не через self, что бы получать объект вызывающего класса
		if (null === static::$_object) {
			static::$_object = new static($file_map);
		}
		return static::$_object;
	}





	/** Осуществляет вызов команды
	 * @param string $class_name Имя класса команды.
	 * @param null\string\array $set Настройки настройки. По умолчанию NULL
	 * @return void
	 */
	public function cmd($cmd_name, $set = null) {
		# Подключаем класс комманды
		$this->_obj_last_cmd = $cmd = new $cmd_name();
		# Выпролняем команду
		$cmd->execute($set);
//		return $cmd->get_status();
	}





	/**/
	public function get_cmd() {
		return $this->_obj_last_cmd;
	}





/**/
}










# Запуск ядра
\core\core_con::call('core_map.php');





/**/
?>
