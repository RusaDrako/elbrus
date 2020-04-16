<?php

namespace core;





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
		require_once(__DIR__ . '/registry/registry.php');
		require_once(__DIR__ . '/registry/_inf/inf_registry_cmd.php');
		require_once(__DIR__ . '/registry/registry_cmd_get_data_from_file_evn.php');

		$folder = $this->_get_host_folder();
		\registry::call()->set('FOLDER_ROOT', $folder, true);
		# При запуске через крон переменной $_SERVER['HTTP_HOST'] не существует.
		\registry::call()->set('SITE_HOST', isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '', true);
		$ts = time();
		\registry::call()->set('TS_NOW_S', $ts, true);
		\registry::call()->set('TS_NOW_D', date('Y-m-d', $ts), true);
		\registry::call()->set('TS_NOW_T', date('H:i:s', $ts), true);
		\registry::call()->set('TS_NOW_DT', date('Y-m-d H:i:s', $ts), true);
		\registry::call()->cmd('\registry_cmd_get_data_from_file_evn', $folder . 'test.env');
		\registry::call()->cmd('\registry_cmd_get_data_from_file_evn', $folder . '.env');
	}





	/** Генерирование корневой папки */
	private function _get_host_folder() {
		# Формируем ссылку к корню проекта
		$__dir__ = __DIR__;
		# Меняем \ на / (при работе на локальной машине)
		$__dir__ = str_replace('\\', '/', $__dir__);
		# Берём текущую директорию и преобразуем её в массив
		$_arr_dir = \explode('/', $__dir__);
		# Удаляем последний элемент (папка ядра)
		\array_pop($_arr_dir);
		# Объединяем массив в новую строку адреса (корневая папка)
		$_dir = \implode('/', $_arr_dir) . '/';
		# Удаляем временную переменную
		unset($_arr_dir);
		return $_dir;
	}





/**/
}



?>
