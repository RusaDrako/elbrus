<?php

namespace core;





	/* * Выводит список доступных пространств имён * /
	function namespace_list() {
		$result = array();
		$array_1 = get_declared_classes();
		foreach ($array_1 as $k => $v) {
			$result['class'][] = $v;
		}
		$array_2 = get_defined_functions();
		foreach ($array_2['user'] as $k => $v) {
			$result['function'][] = $v;
		}
		return $result;
	}




	/** Функция со статистикой */
	function shutdown_stat() {
	//	if (_ELBRUS_AJAX) {return;}
		# Объём затраченной памяти
		$memory		= \memory_get_peak_usage();
			global $db;
			$content = [];
			$content[] = '<div class="card collapsed-card text-xs" style="position: fixed; bottom: 70px; right: 70px; width: 375px; z-index: 10000; background: #bff; border: 1px solid #444; border-radius: 5px; padding: 10px; box-sizing: border-box; font-family: monospace;">';

			$content[] = '<div class="card-header p-0">';
			$content[] = '<b>Пик памяти:</b> ' . \round(($memory/1024/1024), 2) . ' Мб / ' . \round(($memory/1024), 2) . ' Кб / ' . $memory . ' байт';
			$content[] = '<br>';
			$content[] = '<b>Время отработки:</b> ' . round((microtime(true)-_ELBRUS_START), 8) . ' сек.';

			$content[] = '<div class="card-tools old_skin_invisible">';
			$content[] = '<button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">';
			$content[] = '<i class="fas fa-plus"></i></button>';
			$content[] = '<button type="button" class="btn btn-tool" data-card-widget="remove" data-toggle="tooltip" title="Remove">';
			$content[] = '<i class="fas fa-times"></i></button>';
			$content[] = '</div>';

			$content[] = '</div>';

			$content[] = '<div class="card-body p-0">';
			$content[] = '<span class="old_skin_invisible" style="font-size: 50%;">' . __FILE__ . ' - ' . __LINE__ . '</span>';
			$content[] = '<br class="old_skin_invisible">';

			$content[] = '<b>PHP:</b> ' . phpversion();
			$content[] = '<b>MySQL:</b> ' . $db->getValue('SELECT VERSION()');
			$content[] = '; <b>mode:</b> ' . _CMS_MODE_HOST;
			$content[] = '; <b>test:</b> ' . \registry::call()->get('test');
			$content[] = '; <b>mysql:</b> ' . MYSQL_HOST;
			$content[] = '<br>';

			$content[] = '</div>';
			$content[] = '</div>';
			echo \implode('', $content);
	}





	/** Функция со статистикой */
	function shutdown_error() {
		global $db;
		$error = error_get_last();
		if (!isset($error['type']))			{ return; }
		if (in_array($error['type'], [8]))	{ return; }

/*		echo '<div style="position: fixed; bottom: 220px; right: 70px; width: 375px; z-index: 10000; background: #fbb; border: 1px solid #444; border-radius: 5px; padding: 10px; box-sizing: border-box; font-family: monospace;">';
		echo '<b>type:</b> ' . $error['type'];
		echo '<br>';
		echo '<b>message:</b> ' . $error['message'];
		echo '<br>';
		echo '<b>file:</b> ' . $error['file'];
		echo '<b>( ' . $error['line'] . ' )</b>';
		echo '</div>';*/
		$arr_insert = [
			'log_error_type' => $error['type'],
			'log_error_message' => $error['message'],
			'log_error_file' => $error['file'],
			'log_error_line' => $error['line'],
			'log_error_request' => json_encode(\request::call()->get_list()),
		];
		$db->insertRow('log_error', $arr_insert);
	}





	/* * Функция со статистикой * /
	function shutdown_stat_2() {
//		echo 123;
		if (!\registry::call()->set('isAjax', false)) {
			return;
		}
		# Объём затраченной памяти
		$memory		= \memory_get_peak_usage();
		if (!defined('_ELBRUS_PRINT_SHUTDOWN_STAT')
				|| 0 >= _ELBRUS_PRINT_SHUTDOWN_STAT) {
			return;
		}

		# Объём затраченной памяти
		$time		= \round((\microtime(true) - _ELBRUS_START), 8);

		echo '<div style="position: fixed; bottom: 50px; right: 25px; width: 375px; z-index: 100; background: #bff; border: 1px solid #444; border-radius: 10px; padding: 5px 15px; box-sizing: border-box; font-family: monospace; font-size: 14px;">';
		echo '<span style="font-size: 50%;">' . __FILE__ . ' - ' . __LINE__ . '</span>';
		echo '<br>';
		echo '<b style="color: #007;">' . _ELBRUS_TITLE . ' - ' . _ELBRUS_HOST . ' (' . _ELBRUS_VERSION . ')<span style="float: right;">PHP: ' . phpversion() . '</span></b>';
		echo '<br>';
		echo '<b>Пиковое потребление памяти:</b><br>' . \round(($memory/1024/1024), 2) . ' Мб / ' . \round(($memory/1024), 2) . ' Кб / ' . $memory . ' байт';
		echo '<br>';

		echo '<b>Время отработки:</b><br>' . round((microtime(true)-_ELBRUS_START), 8) . ' сек.';
		echo '</div>';


		if (1 < _ELBRUS_PRINT_SHUTDOWN_STAT) {

			echo '<div style="font-family: monospace; background: #ffb;"><hr><hr><hr>' . "\n";
			echo '<h3>Отчёт о выполнении сценария</h3>' . "\n";

			# Список глобальных переменных
			$arr_list = [
/*				'GLOBALS',* /
				'_REQUEST',
				'_SESSION',
				'_GET',
				'_POST',
				'_COOKIE',
				'_FILES',
				'_ENV',
				'_SERVER',
			];

			# Проходим по списоку глобальных переменных
			foreach ($arr_list as $k => $v) {
				# Если переменная существует
				if (isset($GLOBALS[$v])
						# И массив заполнен
						&& count($GLOBALS[$v])) {
					# Выводим массив глобальной переменной
					core::call()->print_info($GLOBALS[$v], '==> $' . $v . ' -- ' . count($GLOBALS[$v]));
				}
			}

			# Список доступных функций и классов
			core::call()->print_info(get_defined_constants(), 'Константы');

			# Список доступных функций и классов
			core::call()->print_info(namespace_list(), 'namespace');

			# Список использованных файлов
			core::call()->print_info(implode("\n", \get_included_files()), 'Пожгруженный файлы');

			echo '<hr></div>' . "\n";
		}

	}





	/* * */
	function shutdown_stat_error() {
	/*	global $global_array_error;
		if (!$global_array_error) {
			return;
		}
		echo '<div style="position: fixed; top: 40px; right: 15px; width: 600px; z-index: 10001; background: #fdd; border: 1px solid #444; border-radius: 5px; padding: 10px; box-sizing: border-box; font-family: monospace; max-height: 225px; overflow-y: auto;">';
		$folder = $_SERVER['DOCUMENT_ROOT'] ? $_SERVER['DOCUMENT_ROOT'] : '';
		$len = strlen($folder);
		echo '<span style="color: #080; font-weight: bold;">' . $folder . '</span>';
		echo '<br>';
		foreach ($global_array_error as $k => $v) {
			$file = substr($v['file'], $len);
			echo '<span style="color: #008; font-weight: bold;">' . $v['type'] . ' => ' . $file . ' (<span style="color: #800;">' . $v['line'] . '</span>)</span>';
			echo '<br>';
			if (substr($file, -8) == '.tpl.php') {
				echo '<span style="font-weight: bold;">Шаблон:</span> ' . $v['message'];
				echo '<br>';
			} else {
				echo '<span style="font-weight: bold;">Код:</span> ' . $v['message'];
				echo '<br>';
			}
		}
		echo '</div>';*/
	}





	//$global_array_error = [];

	/*
	// наш обработчик ошибок
	function myHandler($level, $message, $file, $line, $context) {
		global $global_array_error;
	    // в зависимости от типа ошибки формируем заголовок сообщения
	    switch ($level) {
	        case E_WARNING:
	            $type = 'Warning';
	            break;
	        case E_NOTICE:
	            $type = 'Notice';
	            break;
	        default;
	            // это не E_WARNING и не E_NOTICE
	            // значит мы прекращаем обработку ошибки
	            // далее обработка ложится на сам PHP
	//            return false;
	    }
		$global_array_error[] = [
			'type'		=> $type,
			'message'	=> $message,
			'file'		=> $file,
			'line'		=> $line,
		];
	/* /	$global_array_error[] = ''
				. "<h2>$type: $message</h2>"
				. "<p><strong>File</strong>: $file:$line</p>"
				. "<p><strong>Context</strong>: $". join(', $', array_keys($context))."</p>"
				;
	/*    // выводим текст ошибки
	    echo "<h2>$type: $message</h2>";
	    echo "<p><strong>File</strong>: $file:$line</p>";
	    echo "<p><strong>Context</strong>: $". join(', $',
	    array_keys($context))."</p>";* /
	    // сообщаем, что мы обработали ошибку, и дальнейшая обработка не требуется
	    return true;
	}

/**/
?>
