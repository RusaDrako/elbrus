<?php

namespace core\view;





/**
 * @author Петухов Леонид <l.petuhov@okonti.ru>
 * @package core
 */
class type_smarty extends _abs\abs_type {





	/** Загружает настройки шаблонизатора */
	public function _setting() {
		# Шаблоны header и footer
		$this->_header = 'header';
		$this->_footer = 'footer';

		$folder = \registry::call()->get('FOLDER_ROOT') . '_conf/smarty/';
		require_once($folder . 'Smarty.class.php');
		$this->_obj_view_engine = new \Smarty();
		$this->_obj_view_engine->view_dir = \registry::call()->get('FOLDER_ROOT') . '/_views';
		$this->_obj_view_engine->compile_dir = $folder . 'views_c';
		$this->_obj_view_engine->cache_dir = $folder . 'cache';
		$this->_obj_view_engine->config_dir = $folder . 'configs';

		# Функция-модификатор для смарти: Телефон
		function smarty_phone_format($phone) {
			return preg_replace("/(\d{1})(\d{3})(\d{3})(\d{2})(\d{2})/i", '+$1($2)$3-$4-$5', $phone);
		}

		$this->_obj_view_engine->register_modifier("phone","smarty_phone_format");
	}





	/** Загружает переменную в шаблонизатор
	 * @param string $name Имя переменной в шаблонизаторе
	 * @param string|array|object $value Значение переменной
	 */
	public function variable($name, $value) {
		$this->_obj_view_engine->assign($name, $value);
	}





	/** Выводит указанный шаблон
	 * @param string $link Ссылка на файл шаблона
	 */
	public function block($link) {
		$this->_obj_view_engine->display($this->_name_file($link));
	}





	/** Возвращает html-код указанного шаблона
	 * @param string $link Ссылка на файл шаблона
	 */
	public function html($link) {
		return $this->_obj_view_engine->fetch($this->_name_file($link));
	}










/**/
}





/**/
?>
