<?php

namespace resources\template_native;

use \resources\template_native\template_native_template as tmp_class;

require_once('template_native_template.php');





/** <b>Template Module</b><br> Модуль работы с Шаблонами.
 * @version 0.1.0
 * @author Petukhov Leonid <elbrus.online@yandex.ru>
 */
class template_native {
	/** Папка с шаблонами
	* @var string $arr_variable */
	private $_url_template				= null;

	/** Массив переменных используемых в шаблоне
	 * @var array $arr_variable */
	private $_arr_variable				= [];


	/** Объект модели */
	private static $_object				= null;










	/** Загрузка класса */
	public function __construct($folder) {
		# Запоминаем ссылку на папку шаблонов
		$this->_url_template = $folder;
	}





	/** Выгрузка класса */
	public function __destruct() {}





	/** Вызов объекта
	* @param object $db Объект драйвера подключения к БД
	* @return object Объект модели
	*/
	public static function call(...$args) {
		if (null === self::$_object) {
			self::$_object = new static(...$args);
		}
		# Возвращаем объект модели
		return self::$_object;
	}





	/** */
	public function variable($name, $value) {
		# Регистрируем функцию
		$this->_arr_variable[$name] = $value;
	}





	/** */
	public function variable_clean() {
		# Регистрируем функцию
		$this->_arr_variable = [];
	}





	/** */
	public function view($link) {
		echo $this->html($link);
	}





	/** */
	public function html($link) {
		$arr_var = $this->_arr_variable;
		$this->variable_clean();
		$class = new tmp_class($this, $this->_url_template . $link, $arr_var);
		return $class->result();
	}
















/**/
}





/**/
?>
