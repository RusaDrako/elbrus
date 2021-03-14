<?php

namespace Elbrus\Framework;





/**
 * @author Петухов Леонид <l.petuhov@okonti.ru>
 * @package core
 */
class core_cmd_registry implements _inf\inf_core_cmd {





	/** Автоматически выполняемый метод
	 * @param null|string|array $set Настройки настройки. По умолчанию NULL
	 * @return void
	 */
	public function execute($set = null) {
		require_once(__DIR__ . '/registry/autoload.php');
		$web = $this->_get_web_application();
		\registry::call()->set('WEB_APP', $web, true);
		$folder = $this->_get_host_folder();
		\registry::call()->set('FOLDER_ROOT', $folder, true);
		# При запуске через крон переменной $_SERVER['HTTP_HOST'] не существует.
		\registry::call()->set('SITE_HOST', isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '', true);
		$ts = time();
		\registry::call()->set('TS_NOW_S', $ts, true);
		\registry::call()->set('TS_NOW_D', date('Y-m-d', $ts), true);
		\registry::call()->set('TS_NOW_T', date('H:i:s', $ts), true);
		\registry::call()->set('TS_NOW_DT', date('Y-m-d H:i:s', $ts), true);
		\registry::call()->cmd('\Elbrus\Framework\registry\registry_cmd_get_data_from_file_evn', $folder . 'test.env');
		\registry::call()->cmd('\Elbrus\Framework\registry\registry_cmd_get_data_from_file_evn', $folder . '.env');
	}





	/** Генерирование корневой папки приложение */
	private function _get_web_application() {
		# Если есть переменная корня сервера
		if (isset($_SERVER['DOCUMENT_ROOT'])) {
			if ($_SERVER['DOCUMENT_ROOT']) {
				return true;
			}
		}
		return false;
	}





	/** Генерирование корневой папки приложение */
	private function _get_host_folder() {
		# Если есть переменная корня сервера
		if (isset($_SERVER['DOCUMENT_ROOT']) && $_SERVER['DOCUMENT_ROOT']) {
			$__dir__ = $_SERVER['DOCUMENT_ROOT'];
		} else {
			# Ищем откуда был запущено приложение
			$backtrace = debug_backtrace();
			$backtrace = array_reverse($backtrace);
			# Формируем ссылку к корню проекта
			$__dir__ = dirname($backtrace[0]['file']);
		}
		# Меняем \ на / (при работе на локальной машине)
		$__dir__ = str_replace('\\', '/', $__dir__);
		# Берём текущую директорию и преобразуем её в массив
		$_arr_dir = \explode('/', $__dir__);
		# Объединяем массив в новую строку адреса (корневая папка)
		$_dir = \implode('/', $_arr_dir) . '/';
		# Удаляем временную переменную
		unset($_arr_dir);
		return $_dir;
	}





/**/
}
