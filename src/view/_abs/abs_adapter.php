<?php

namespace Elbrus\Framework\view\_abs;



/**
 * @author Петухов Леонид <rusadrako@yandex.ru>
 * @package core
 */
abstract class abs_adapter {
	/** Расширение шаблона */
	protected $_extension = null;
	/** Объект шаблонизатора */
	protected $_obj_view_engine = null;
	/** Рабочий объект
	 * @var object
	 */
	private static $_object = null;










	/** Конструктор объекта (загрузка настроек) */
	final public function __construct() {
		$this->_setting();
	}





	/** Деструктор класса */
	final public function __destruct() {}





	/** Вызов объекта
	* @return object Объект модели
	*/
	final public static function call(...$args) {
		if (!isset(self::$_object)) {
			self::$_object = new static(...$args);
		}
		return self::$_object;
	}





	/** Загружает настройки шаблонизатора */
	abstract public function _setting();





	/** Загружает настройки шаблонизатора */
	protected function _name_file($link) {
		return $link . $this->_extension;
	}





	/** Загружает переменную в шаблонизатор
	 * @param string $name Имя переменной в шаблонизаторе
	 * @param string|array|object $value Значение переменной
	 */
	abstract public function variable($name, $value);





	/** Выводит указанный шаблон
	 * @param string $link Ссылка на файл шаблона
	 */
	abstract public function block($link);





	/** Возвращает html-код указанного шаблона
	 * @param string $link Ссылка на файл шаблона
	 */
	abstract public function html($link);





/**/
}
