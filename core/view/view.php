<?php

require_once ('_abs/abs_adapter.php');





/**
 * @author Петухов Леонид <l.petuhov@okonti.ru>
 * @package core
 */
class view {
	/** Экземпляр класса-фасада шаблонизатора
	* @var object \core\view\_abs\abs_type
	*/
	private $_obj_view_type = null;
	/** Экземпляр класса
	 * @var object
	 */
	private static $_object = null;










	/** Конструктор объекта
	 * @param class $class Класс-фасад для шаблонизатора
	*/
	public function __construct(\core\view\_abs\abs_adapter $class) {
		$this->_obj_view_type = $class;
	}





	/** Деструктор класса */
	public function __destruct() {}





	/** Вызов объекта
	* @return object Объект модели
	*/
	public static function call(...$args) {
		if (!isset(self::$_object)) {
			self::$_object = new static(...$args);
		}
		return self::$_object;
	}





	/** Загружает переменную в шаблонизатор
	 * @param string $name Имя переменной в шаблонизаторе
	 * @param string|array|object $value Значение переменной
	 */
	final public function variable($name, $value) {
		$this->_obj_view_type->variable($name, $value);
		return $this;
	}





	/** Выводит указанный шаблон
	 * @param string $link Ссылка на файл шаблона
	 */
	final public function display($link) {
		$this->_obj_view_type->block($link);
	}





	/** Возвращает html-код указанного шаблона
	 * @param string $link Ссылка на файл шаблона
	 */
	final public function html($link) {
		return $this->_obj_view_type->html($link);
	}





/**/
}





/**/
?>
