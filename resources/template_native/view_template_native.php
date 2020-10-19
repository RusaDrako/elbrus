<?php

namespace resources\template_native;





/**
 * @author Петухов Леонид <l.petuhov@okonti.ru>
 * @package core
 */
class view_template_native extends \core\view\_abs\abs_adapter {
	/** Расширение шаблона */
	protected $_extension = '.php';
	/** Шаблон открывающей части */
	protected $_header = 'header';
	/** Шаблон замыкающей части */
	protected $_footer = 'footer';
	/** Объект шаблонизатора */
	protected $_obj_view_engine = null;










	/** Загружает настройки шаблонизатора */
	public function _setting() {
		$this->_object = \resources\template_native\template_native::call(\registry::call()->get('FOLDER_ROOT') . 'app/_template/template_native/');
	}






	/** Загружает переменную в шаблонизатор
	 * @param string $name Имя переменной в шаблонизаторе
	 * @param string|array|object $value Значение переменной
	 */
	public function variable($name, $value) {
		$this->_object->variable($name, $value);
	}





	/** Выводит указанный шаблон в обёртке шаблонов header и footer
	 * @param string $link Ссылка на файл шаблона
	 */
	public function page($link) {
		$this->block($this->_header);
		$this->block($link);
		$this->block($this->_footer);
	}





	/** Выводит указанный шаблон
	 * @param string $link Ссылка на файл шаблона
	 */
	public function block($link) {
		$this->_object->view($link . $this->_extension);
	}





	/** Возвращает html-код указанного шаблона
	 * @param string $link Ссылка на файл шаблона
	 */
	public function html($link) {
		$this->_object->html($link . $this->_extension);
	}





/**/
}
