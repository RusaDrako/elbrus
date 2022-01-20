<?php




/** Логирование точек времени */
class log_time {
	/** @param object $arr_point Массив точек */
	private $arr_point = [];
	/** @param object $_object Объект класса */
	private static $_object		= null;





	/** Конструктор	*/
	public function __construct() {}





	/** Деструктор */
	function __destruct() {}





	/** Типовой вызов объекта класса */
	public static function call(...$args) {
		# Работаем через static а не через self, что бы получать объект вызывающего класса
		if (null === static::$_object) {
			static::$_object = new static(...$args);
		}
		return static::$_object;
	}





	/** Выводит отчёт
	* @param $return [false] Метка о возврате содержимого отчёта, а не его вывод
	*/
	function report($return = false) {
		$this->point('Отчёт', 1);

		$arr_report = [];

		foreach ($this->arr_point as $k => $v) {
			if ($k == 0) {
				$time_start = $v['time'];
				$time_last = $v['time'];
			}
			$delta_start = $v['time'] - $time_start;
			$delta_last = $v['time'] - $time_last;
			$arr_report[] = $this->_report_line($delta_start, $delta_last, $v['title'], $v['file'], $v['line']);
			$time_last = $v['time'];
		}

		$str_report = \implode('<br>', $arr_report);

		if ($return) {return $str_report;}

		echo '<pre style="background: #fff; font-family: monospace; font-size: 14px; padding: 5px;"><hr>';
		echo $str_report;
		echo '<hr></pre>';
	}





	/** Формирует строчку отчёта */
	private function _report_line($delta_start, $delta_last, $title, $file = null, $line = null) {
		$delta_start = number_format($delta_start, 6);
		$delta_last = number_format($delta_last, 6);
		$str = "{$delta_start} сек. -> {$delta_last} сек.: <b>{$title}</b>";
		if ($file) { $str .= " <span style=\"color: #000077;\">{$file} ($line)</span>";}
		return $str;
	}





	/** Устанавливает точку времени
	* @param $title [---] Заголовок точки
	* @param $level [0] Уровень debug_backtrace для получения файла и линии вызова
	*/
	function point($title = '---', $level = 0) {
		$arr_back = debug_backtrace();
		$file = null;
		$line = null;
		if (isset($arr_back[$level])) {
			$file = isset($arr_back[$level]['file']) ? $arr_back[$level]['file'] : null;
			$line = isset($arr_back[$level]['line']) ? $arr_back[$level]['line'] : null;
		}
		$this->arr_point[] = [
			'title' => $title,
			'file'	=> $file,
			'line'	=> $line,
			'time'	=> microtime(true),
		];
	}





/**/
}
