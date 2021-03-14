<?php

use \Elbrus\Framework\print_data as print_data;



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
	if (\registry::call()->get('WEB_APP', true)) {
		return print_data\print_info($value, $title, _access_print_control($view));
	} else {
		return print_data\print_info_app($value, $title, _access_print_control($view));
	}
}



/** Пользовательское сообщение.
 * @param int/string/array $value Переменная для печати.
 * @param string $title Заголовок.
 * @param bool $view Маркер "показывать в любом случае".
*/
function print_info_var($value, $title = false, $view = false) {
	return print_data\print_info_var($value, $title, _access_print_control($view));
}



/** Пользовательское сообщение.
 * @param int/string/array $value Переменная для печати.
 * @param string $title Заголовок.
 * @param bool $view Маркер "показывать в любом случае".
*/
function print_table($value, $title = false, $view = false, $profile = []) {
	return print_data\print_table($value, $title, _access_print_control($view), $profile);
}



/** Пользовательское сообщение.
 * @param int/string/array $value Переменная для печати.
 * @param string $title Заголовок.
 * @param bool $view Маркер "показывать в любом случае".
*/
function print_tree($value, $title = false, $view = false, $profile = []) {
	return print_data\print_tree($value, $title, _access_print_control($view), $profile);
}



/** Пользовательское сообщение - лог.
 * @param int/string/array $value Переменная для печати.
 * @param bool $view Маркер "показывать в любом случае".
*/
function print_log($value, $view = false) {
	return print_data\print_log($value, _access_print_control($view));
}



/** Пользовательское сообщение - ошибка.
 * @param int/string/array $value Переменная для печати.
 * @param string $title Заголовок.
 * @param bool $view Маркер "показывать в любом случае".
*/
function print_error($value, $title = false, $view = false) {
	return print_data\print_error($value, $title, _access_print_control($view));
}
