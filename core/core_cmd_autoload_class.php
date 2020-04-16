<?php

namespace core;





/**
 * @author Петухов Леонид <l.petuhov@okonti.ru>
 * @package core
 */
class core_cmd_autoload_class implements _inf\inf_core_cmd {
	/** Число автоматически подгружаемых классов
	 * @var integer
	 */
	private static $_count_class = 0;
	/** Список автоматически подгружаемых классов
	 * @var array
	 */
	private static $_arr_name_class = [];










	/** Автоматически выполняемый метод
	 * @param null|string|array $set Настройки настройки. По умолчанию NULL
	 * @return void
	 */
	public function execute($set = null) {
		return spl_autoload_register(function (string $class_name) {
			$this_class = __CLASS__;
			$this_class::$_count_class++;
			$this_class::$_arr_name_class[] = $class_name;
			# Разбиваем имя функции на массив адреса
			$_arr_class_folder = \explode('\\', $class_name);
			# Нулевой массив адреса
			$arr_class_folder = array_diff($_arr_class_folder, array(null, false, ''));
			# Преобразуем имя функции в имя папки
			$class_folder = \implode('/', $arr_class_folder);
			# Формируем адрес файла класса
			$class_file = \registry::call()->get('FOLDER_ROOT') . $class_folder . '.php';
			# Пробуем подключить файл
			if (!file_exists($class_file)) {
				$this->_message('Файл класса, трайта или интерфейса не найден:<br><b style="color: #008;">' . $class_file . '</b>');
				exit;
			}
			require_once($class_file);
			if (!class_exists($class_name) && !trait_exists($class_name) && !interface_exists($class_name)) {
				$this->_message('Класс, трайт или интерфейс не найден:<br><b style="color: #008;">' . $class_name . '</b>');
				exit;
			}
		});
	}





	/** Выводит сообщение об ошибке
	 * @param string $title Текст сообщения
	 * @return void
	 */
	private function _message($title) {
		$mes = [];
		if (!\registry::call()->get('test')) {
			$mes[] = 'Ошибка обращения к классу. Обратитесь к администратору. <div style="display: none;">';
		}
		$mes[] = '<hr><b style="font-size: 150%;">' . __CLASS__ . ':</b>';
		$mes[] = '';
		$mes[] = $title;
		$mes[] = '';
		$mes[] = $this->_backtrace();
		if (!\registry::call()->get('test')) {
			$mes[] = '</div>';
		}
		echo implode('<br>', $mes);
	}





	/** Возвращает маршрут приведший к ошибке
	 * @return string Сообщение с маршрутом
	 */
	private function _backtrace() {
		# Смотрим откуда вызвана функция
		$arr_backtrace = debug_backtrace();
		unset($arr_backtrace[0]);
		unset($arr_backtrace[1]);
		unset($arr_backtrace[2]);
		$mes = [];
		foreach ($arr_backtrace as $v) {
			$class = isset($v['class'])			? $v['class'] . ' => '		: '';
			$function = isset($v['function'])	? $v['function']			: '';
			$file = isset($v['file'])			? $v['file']				: '---';
			$line = isset($v['line'])			? $v['line']				: '---';
			# Выводим сообщение
			$mes[] = '<b>' . $file
					. ' (' . $line . ')'
					. ' <span style="color: #00b;">' .  $class  . '</span>'
					. '<span style="color: #b00;">' . $function . '</span></b>'
					;
		}
		return implode('<br>', $mes);
	}





/**/
}



?>
