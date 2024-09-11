<?php

namespace Elbrus\Framework;





/**
 * @author Петухов Леонид <rusadrako@yandex.ru>
 * @package core
 */
class core_cmd_autoload_class implements _inf\inf_core_cmd {
	/** Игнорируемые папки (вызов файла)
	 * @var array
	 */
	private static $_folder_ignore = [];
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
		return spl_autoload_register(function ($class_name) {
			# Получаем имя текущего класса (для вызова функций и свойств)
			$this_class = __CLASS__;
			# Проверяем игнорирование папки вызова
			if($this_class::_is_ignor_folder()){ return; }

			# Увеличиваем счётчик автозагруженных файлов (для контроля)
			$this_class::$_count_class++;
			# Запоминаем автозагруженный файл (для контроля)
			$this_class::$_arr_name_class[] = $class_name;

			# Разбиваем имя класса на массив адреса
			$_arr_class_folder = \explode('\\', $class_name);
			# Нулевой массив адреса
			$arr_class_folder = array_diff($_arr_class_folder, array(null, false, ''));

			# Преобразуем имя функции в имя папки
			$class_folder = \implode('/', $arr_class_folder);
			# Формируем адрес файла класса

			$folder_root = \registry::call()->get('FOLDER_ROOT');
			$class_file = $folder_root . $class_folder . '.php';

			# Пробуем подключить файл
			if (!file_exists($class_file)) {
				$this->_message('Файл класса, трайта или интерфейса не найден:<br><b style="color: #008;">' . $class_file . '</b>');
				exit;
			}
			# Подгружаем файл класса
			require_once($class_file);
			if (!class_exists($class_name) && !trait_exists($class_name) && !interface_exists($class_name)) {
				$this->_message('Класс, трайт или интерфейс не найден:<br><b style="color: #008;">' . $class_name . '</b>');
				exit;
			}
		});
	}



	/** Проверяеn, надо ли игнорировать папку вызова
	 * @return bool
	 */
	private function _is_ignor_folder() {
		# Получаем имя текущего класса (для вызова функций и свойств)
		$this_class = __CLASS__;
		# Получаем бактрайс активации файла (первые два уровня - текущий файл)
		# Третий уровень - место вызова неизвестного класса
		$backtrace = debug_backtrace (0, 4);
		# Проверяем, существует ли ссылка на файл (запоминаем её)
		if (isset($backtrace[3]) && isset($backtrace[3]['file'])) {
			$backtrace_file = $backtrace[3]['file'];
		} else {
			$backtrace_file = '';
		}
		# Для винды меняем слэши на обратные
		$backtrace_file = \str_replace('\\', '/', $backtrace_file);
		# Проверяем, не относится ли папка файла к игнорируемым
		foreach ($this_class::$_folder_ignore as $k => $v) {
			if (\substr($backtrace_file, 0, \strlen($v)) == $v) {
				return true;
			}
		}
		return false;
	}



	/** Выводит сообщение об ошибке
	 * @param string $title Текст сообщения
	 * @return void
	 */
	private function _message($title) {
		$mes = [];
		$mes[] = '<hr><b style="font-size: 150%;">' . __CLASS__ . ':</b>';
		$mes[] = '';
		$mes[] = $title;
		$mes[] = '';
		$mes[] = $this->_backtrace();
		if (!\registry::call()->get('test')) {
			array_unshift($mes, 'Ошибка обращения к классу. Обратитесь к администратору. <div style="display: none;">');
			$mes[] = '</div>';
		}
		echo '<pre>';
		echo implode("\r\n", $mes);
		throw new \Exception(strip_tags($title), 1);
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



	/** Отмена регистрации автозагрузчика файлов
	 * Иногда, нужно при активации сторонних библиотек: smarty 3.1.34
	 * @return void
	 */
	public function autoload_unregister() {
		$functions = spl_autoload_functions();
		foreach($functions as $function) {
			spl_autoload_unregister($function);
		}
	}



	/** Добавляем игнорируемую папку (если обращение из этой папки или ниже, то автозагрузка не обрабатывается)
	 * Иногда, нужно при активации сторонних библиотек: smarty 3.1.34
	 * @return void
	 */
	public static function add_folder_ignore($folder) {
		self::$_folder_ignore[] = $folder;
	}

/**/
}
