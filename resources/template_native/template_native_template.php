<?php

namespace resources\template_native;





/** */
class template_native_template {
	private $_obj_native = null;
	private $_var = [];
	private $_content = [];









	/** Загрузка класса
	* @param object $obj Объект шаблонизатора
	* @param string $link Ссылка на шаблон
	* @param array $arr_var Массив переменных
	*/
	public function __construct(\resources\template_native\template_native $obj, string $link, array $arr_var) {
		$this->_obj_native = $obj;
		$this->_var = $arr_var;
		# Включаем буферизацию текущего шаблона
		ob_start();
			include($link);
			$this->_content[] = ob_get_contents();
		# Отключаем буферизацию текущего шаблона
		ob_end_clean();
	}





	/** Выгрузка класса */
	public function __destruct() {}





	/** */
	public function __get($name) {
		# Если переменная присутчствует в шаблоне
		if (isset($this->_var[$name])) {
			# Возвращаем значение переменной
			return $this->_var[$name];
		}
		# Возвращаем значение по умолчанию
		return 'Переменная не определена';
		return NULL;
	}





	/** */
	public function result() {
		# Возвращаем результат шаблона
		return \implode("\n", $this->_content);
	}





	/** */
	public function include(string $link, array $var = []) {
		# Запоминаем текущий результат буфера
		$this->_content[] = ob_get_contents();
		# Отключаем буферизацию текущего шаблона
		ob_end_clean();
		# Присваеваем переменные подшаблона
		foreach ($var as $k => $v) {
			$this->_obj_native->variable($k, $v);
		}
		# Выполняем подшаблон
		$this->_content[] = $this->_obj_native->html($link);
		# Включаем буферизацию текущего шаблона
		ob_start();
	}





/**/
}
