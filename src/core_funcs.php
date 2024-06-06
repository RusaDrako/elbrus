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
