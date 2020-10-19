<?php

use \core\print_class\print_class;





/**
 * @param bool $view Маркер "показывать в любом случае".
*/
function _access_print_control($view = false) {
	# Отрабатывать только в тестовом режиме
	if (\registry::call()->get('test', false)
			|| $view) {
		return true;
	}
	return false;
}





/** Пользовательское сообщение.
 * @param int/string/array $value Переменная для печати.
 * @param string $title Заголовок.
 * @param bool $view Маркер "показывать в любом случае".
*/
function print_info($value, $title = false, $view = false) {
	# Отрабатывать только в тестовом режиме
	if (_access_print_control($view)) {
		print_class::call()
				->backtrace(1)
				->description($value)
				->title($title)
				->print_form()
				;
	}
}





/** Пользовательское сообщение.
 * @param int/string/array $value Переменная для печати.
 * @param string $title Заголовок.
 * @param bool $view Маркер "показывать в любом случае".
*/
function print_info_var($value, $title = false, $view = false) {
	# Отрабатывать только в тестовом режиме
	if (_access_print_control($view)) {
		print_class::call()
				->backtrace(1)
				->var_dump(true)
				->description($value)
				->title($title)
				->print_form()
				;
	}
}





/** Пользовательское сообщение.
 * @param int/string/array $value Переменная для печати.
 * @param string $title Заголовок.
 * @param bool $view Маркер "показывать в любом случае".
*/
function print_table($value, $title = false, $view = false, $profile = []) {
	# Отрабатывать только в тестовом режиме
	if (_access_print_control($view, $profile)) {
		$obj_array = new \array_class();
		print_class::call()
				->backtrace(1)
				->as_it_is(true)
				->description($obj_array->print_table_2d_array($value))
				->title($title)
				->print_form()
				;
	}
}





/** Пользовательское сообщение.
 * @param int/string/array $value Переменная для печати.
 * @param string $title Заголовок.
 * @param bool $view Маркер "показывать в любом случае".
*/
function print_tree($value, $title = false, $view = false, $profile = []) {
	# Отрабатывать только в тестовом режиме
	if (_access_print_control($view, $profile)) {
		$obj_array = new \array_class();
		print_class::call()
				->backtrace(1)
				->as_it_is(true)
				->description($obj_array->print_table_tree_array($value))
				->title($title)
				->print_form()
				;
	}
}





/** Пользовательское сообщение - лог.
 * @param int/string/array $value Переменная для печати.
 * @param bool $view Маркер "показывать в любом случае".
*/
function print_log($value, $view = false) {
	# Отрабатывать только в тестовом режиме
	if (_access_print_control($view)) {
		print_class::call()
				->color_title('#008')
				->color_bg('#eef')
				->description($value)
				->print_form()
				;
	}
}





/** Пользовательское сообщение - ошибка.
 * @param int/string/array $value Переменная для печати.
 * @param string $title Заголовок.
 * @param bool $view Маркер "показывать в любом случае".
*/
function print_error($value, $title = false, $view = false) {
	# Если тестовый режим отключен и нет маркера "показать", то возвращаем false
	$test = defined('_ELBRUS_PRINT_ERROR') ? _ELBRUS_PRINT_ERROR : false;
	if (true !== $view
			&& true !== $test) {
		return;
	}

	print_class::call()
			->color_title('#800')
			->color_bg('#faa')
//				->backtrace(1)
			->description($value)
			->title($title)
			->print_form()
			;
//		\core\core::call()->print_error($value, $title, $view);
}





/*
function print_info() {
	echo __FUNCTION__;
}




/**/
