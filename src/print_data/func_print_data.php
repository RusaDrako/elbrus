<?php

namespace Elbrus\Framework\print_data;

/** Пользовательское сообщение.
 * @param int/string/array $value Переменная для печати.
 * @param string $title Заголовок.
 * @param bool $view Маркер "показывать в любом случае".
*/
function print_info($value, $title = false, $view = false) {
	# Отрабатывать только в тестовом режиме
	if ($view) {
		print_data::call()
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
function print_info_app($value, $title = false, $view = false) {
	# Отрабатывать только в тестовом режиме
	if ($view) {
		print_data::call()
				->backtrace(1)
				->description($value)
				->title($title)
				->print_app()
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
	if ($view) {
		print_data::call()
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
function print_table($value, $title = false, $view = false) {
	# Отрабатывать только в тестовом режиме
	if ($view) {
		$obj_array = new \array_class();
		print_data::call()
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
function print_tree($value, $title = false, $view = false) {
	# Отрабатывать только в тестовом режиме
	if ($view) {
		$obj_array = new \array_class();
		print_data::call()
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
	if ($view) {
		print_data::call()
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

	print_data::call()
			->color_title('#800')
			->color_bg('#faa')
			->description($value)
			->title($title)
			->print_form()
			;
}
