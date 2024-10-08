<?php

namespace Elbrus\Framework\print_data;

/** <b>Печать сообщений v 1.0.0</b>
 * @version 1.0.0
 * @created 2020-01-23
 * @author Петухов Леонид <rusadrako@yandex.ru>
 */
class print_data {

	/** */
	private $_backtrace				= true;
	/** */
	private $_title					= false;
	/** */
	private $_description			= false;
	/** */
	private $_var_dump				= false;
	/** */
	private $_as_it_is				= false;
	/** */
	private $_color_bg				= false;
	/** */
	private $_color_title			= false;

	/** Объект модели */
	private static $_object			= null;



	/** Загрузка класса */
	public function __construct() {
		# Базовая чистка
		$this->_clean();
	}

	/** Выгрузка класса */
	public function __destruct () {}



	/** Вызов объекта класса
	* @return object Объект модели
	*/
	public static function call(...$args) {
		# Если объект отсутствует
		if (null === self::$_object) {
			# Активируем объект
			self::$_object = new static(...$args);
		}
		# Возвращаем объект каласса
		return self::$_object;
	}

	/** Базова чистка настроек */
	private function _clean() {
		$this->_backtrace		= false;
		$this->_title			= false;
		$this->_description		= false;
		$this->_var_dump		= false;
		$this->_color_bg		= '#ffd';
		$this->_color_title		= '#080';
	}

	/** Рекурсивная сборка массива в строку
	 * @param string $glue Строка-склейка
	 * @param array $array Массив
	 */
	private function _implode_recursion ($glue, $array) {
		foreach ($array as $k => $v) {
			if (is_array($v)) {
				$array[$k] = $this->_implode_recursion($glue, $v);
			}
		}
		return implode($glue, $array);
	}

	/** */
	public function color_bg($value) {
		$this->_color_bg = $value;
		return $this;
	}

	/** */
	public function color_title($value) {
		$this->_color_title = $value;
		return $this;
	}

	/** */
	public function backtrace($type) {
		$this->_backtrace = $type;
		return $this;
	}

	/** */
	public function as_it_is($bool = false) {
		$this->_as_it_is = $bool;
		return $this;
	}

	/** */
	public function var_dump($bool = false) {
		$this->_var_dump = $bool;
		return $this;
	}

	/** */
	public function title($value) {
		$this->_title = $value;
		return $this;
	}

	/** */
	public function description($value) {
		$this->_description = $value;
		return $this;
	}

	/** */
	public function print_form() {
		$content = [];
		# Рандомный ключ для checkbox
		$key_rand = rand(1000, 9999);
		# Стиль для "всплывающего div"
		$content[] = '<style>
	.block_print_info .block_print_info_show{ display: none;}
	.block_print_info input[type=checkbox]:checked + .block_print_info_show { display: block;}
</style>';
		# Открываем тэг вывода (без обработки текста браузером)
		$content[] = '<pre class="block_print_info" style="display: block; position: relative; min-height: 20px; background: ' . $this->_color_bg . '; color: #000; border: 2px dashed #000; padding: 10px 20px; margin: 10px 15px; font-size: 12px;">';
		$content[] = $this->_print_title($key_rand);
		# Выводим скрытый input
		$content[] = '<input id="input_print_info_' . $key_rand . '" class="input_print_info" type="checkbox" style="display: none;">';
		# Открываем "всплывающий div"
		$content[] = '<div class="block_print_info_show">';
		$content[] = $this->_print_backtrace();
		# Добавляем контент
		$content[] = $this->_print_description();
		# Закрываем "всплывающий div"
		$content[] = '</div>' . "\n";
		# Закрываем тэг вывода
		$content[] = '</pre>' . "\n";
		# Выводим блок
		echo $this->_implode_recursion('', $content);
		$this->_clean();
	}

	/** */
	public function print_app() {
		$content = [];
		# Открываем тэг вывода (без обработки текста браузером)
		$content[] = '================================================================================';
		$content[] = $this->_print_title_app($key_rand);
		$content[] = $this->_print_backtrace_app();
		$content[] = '================================================================================';
		# Добавляем контент
		$content[] = $this->_print_description();
		$content[] = "================================================================================\n";
		# Выводим блок
		echo $this->_implode_recursion("\n", $content);
		$this->_clean();
	}

	/** */
	public function _print_title($key_rand) {
		# Если заголовок не задан
		if (!$this->_title) {
			# Заголовок по умолчанию
			$_title = 'Не указано';
		} else {
			$_title = $this->_title;
		}
		# Выводим заголовок
		$content = '<label for="input_print_info_' . $key_rand . '"><span style="color: ' . $this->_color_title . '; font-size: 120%;"><b>&#9660; '  . $_title . " &#9660;</b></span></label>";
		# Возвращаем содержимое
		return $content;
	}

	/** */
	public function _print_title_app($key_rand) {
		# Если заголовок не задан
		if (!$this->_title) {
			# Заголовок по умолчанию
			$_title = 'Не указано';
		} else {
			$_title = $this->_title;
		}
		# Выводим заголовок
		$content = $_title . "\n";
		# Возвращаем содержимое
		return $content;
	}

	/** Блок информации по "цепочке вызова" */
	private function _print_backtrace() {
		$content = [];
		switch ($this->_backtrace) {
			case '1':
				$content[] = $this->_print_backtrace_1();
				break;
			case '2':
				$content[] = $this->_print_backtrace_2();
				break;
			default:
				break;
		}
		# Возвращаем содержимое
		return $content;
	}

	/** Блок информации по "цепочке вызова" Тип 1 */
	public function _print_backtrace_1() {
		$content = [];
		# Получаем информацию по "цепочке вызова"
		$backtrace = debug_backtrace();
		unset($backtrace[0]);
		unset($backtrace[1]);
		unset($backtrace[2]);
		# "Разворачиваем" массив
		$backtrace = array_reverse($backtrace);
		$content[] = '<span style="font-size: 90%;">';
		# Проходим по элементам массива
		foreach ($backtrace as $k => $v) {
			$content[] = '<b>' . (isset($v['file']) ? dirname($v['file']) . '\\' . basename($v['file']) : '---')
					. ' (<span style="">' . (isset($v['line']) ? $v['line'] : '---') . '</span>):</b> => '
					. (isset($v['function']) ? $v['function'] : '---') . '<br>';
		}
		$content[] = '</span>';
		$content[] = '<hr>';
		# Возвращаем содержимое
		return $content;
	}

	/** Блок информации по "цепочке вызова" Тип 2 */
	private function _print_backtrace_2() {
		$content = [];
		# Получаем информацию по "цепочке вызова"
		$backtrace = debug_backtrace();
		unset($backtrace[0]);
		unset($backtrace[1]);
		unset($backtrace[2]);
		# "Разворачиваем" массив
		$backtrace = array_reverse($backtrace);
		# "Разворачиваем" массив
		$backtrace = array_reverse($backtrace);
		$content[] = '<span style="font-size: 90%;">';
		# Проходим по элементам массива
		foreach ($backtrace as $k => $v) {
			# Выводим информацию о вызывающем файле
			if (isset($v['file'])) {
				$content[] = '<b>Папка:</b>	' . dirname($v['file']) . "\n";
				$content[] = '<b>Файл:</b>	' . basename($v['file']) . "\n";
				$content[] = '<b>Строка:</b>	' . $v['line'];
			} else {
				$content[] = '<b>Папка:</b>' . "\n";
				$content[] = '<b>Файл:</b>' . "\n";
				$content[] = '<b>Строка:</b>';
			}
			$content[] = '<hr>';
		}
		$content[] = '</span>';
		# Возвращаем содержимое
		return $content;
	}

	/** Блок информации по "цепочке вызова" */
	private function _print_backtrace_app() {
		$content = [];
		switch ($this->_backtrace) {
			case '1':
				$content[] = $this->_print_backtrace_1_app();
				break;
			case '2':
				$content[] = $this->_print_backtrace_2_app();
				break;
			default:
				break;
		}
		# Возвращаем содержимое
		return $content;
	}

	/** Блок информации по "цепочке вызова" Тип 1 */
	public function _print_backtrace_1_app() {
		$content = [];
		# Получаем информацию по "цепочке вызова"
		$backtrace = debug_backtrace();
		unset($backtrace[0]);
		unset($backtrace[1]);
		unset($backtrace[2]);
		# "Разворачиваем" массив
		$backtrace = array_reverse($backtrace);
		# Проходим по элементам массива
		foreach ($backtrace as $k => $v) {
			$content[] = (isset($v['file']) ? dirname($v['file']) . '\\' . basename($v['file']) : '---')
					. ' (' . (isset($v['line']) ? $v['line'] : '---') . '): => '
					. (isset($v['function']) ? $v['function'] : '---');
		}
		# Возвращаем содержимое
		return $content;
	}

	/** Блок информации по "цепочке вызова" Тип 2 */
	private function _print_backtrace_2_app() {
		$content = [];
		# Получаем информацию по "цепочке вызова"
		$backtrace = debug_backtrace();
		unset($backtrace[0]);
		unset($backtrace[1]);
		unset($backtrace[2]);
		# "Разворачиваем" массив
		$backtrace = array_reverse($backtrace);
		# "Разворачиваем" массив
		$backtrace = array_reverse($backtrace);
		# Проходим по элементам массива
		foreach ($backtrace as $k => $v) {
			# Выводим информацию о вызывающем файле
			if (isset($v['file'])) {
				$content[] = 'Папка:	' . dirname($v['file']);
				$content[] = 'Файл:	' . basename($v['file']);
				$content[] = 'Строка:	' . $v['line'] . "\n";
			} else {
				$content[] = '<b>Папка:';
				$content[] = '<b>Файл:';
				$content[] = '<b>Строка:' . "\n";
			}
		}
		# Возвращаем содержимое
		return $content;
	}

	/** Вывод содержимого */
	public function _print_description() {
		$content = [];
		$value = $this->_description;
		# Выводим основной блок информации
		if (true == $this->_var_dump) {
//			$content[] = var_export($value, true);
			ob_start();
			var_dump($value);
			$content[] = ob_get_contents();
			ob_end_clean();
		} else if (is_string($value)) {
			if (true != $this->_as_it_is) {
				$value = str_replace('<', '&#60;', $value);
				$value = str_replace('<', '&#62;', $value);
			}
			$content[] = print_r($value, true);
		} else {
			$content[] = print_r($value, true);
		}
		# Возвращаем содержимое
		return $content;
	}

/**/
}
