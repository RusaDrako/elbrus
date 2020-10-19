<?php

namespace resources\router;





/** <b>router</b><br> Модуль обработки маршрутов.
 * @version 0.0.1
 * @author Petukhov Leonid <elbrus.online@yandex.ru>
 */
class router {
	use trait__arg;
	/** Массив с элементами текущего маршрута */
	private $arr_router					= false;
	/** Текущий маршрут */
	private $_route						= false;
	/** Массив маршрутов по типам запросов */
	private $arr_router_settings		= [
		'GET'		=> [],
		'HEAD'		=> [],
		'POST'		=> [],
		'PUT'		=> [],
		'PATCH'		=> [],
		'DELETE'	=> [],
		'OPTIONS'	=> [],
/*		'API'		=> [],*/
	];
	/** Маршрут по умолчанию */
	private $router_default				= [];
	/** Метод запроса */
	private $method						= false;

	/** Объект модели */
	private static $_object				= null;






	/** Загрузка класса */
	function __construct () {
		# Получаем урл страницы
		$this->_route = \request::call()->get('sys_page', '') . '/';
		$this->_route = \str_replace('//', '/', $this->_route);
		$this->arr_router = \explode('/', $this->_route);

		// TODO переработать для консоли
		if (isset($_SERVER['REQUEST_METHOD'])) {
			# Определяем метод из режима сервера
			$method = $_SERVER['REQUEST_METHOD'];
		} else {
			# Метод по умолчанию (для вызова из режима консоли)
			$method = \request::call()->get('sys_method', 'GET');
		}
		$this->method = $method;
		$this->router_default = $this->arr_router_settings;
//		$_func = function ($a = false) {echo 'Ошибка 404: Страница не найдена.';};
//		$this->default_page($_func);
		$this->default_page(false);
	}





	/** Выгрузка класса */
	public function __destruct () {}





	/** Вызов объекта класса
	* @return object Объект модели
	*/
	public static function call(...$args) {
//		echo __METHOD__ . '<br>';
		if (null === self::$_object) {
			self::$_object = new static(...$args);
		}
		return self::$_object;
	}





	/** */
	private function _error_log ($title, $text = '') {
		# Выводим сообщение
		echo '<span style="background: #fbb;">' . $title . ':</span> ' . $text . '<br>';
	}





	/** */
	private function _add_router ($type, $mask, $action) {
		if ('/' != \substr($mask, 0, 1)) {
			$mask = '/' . $mask;
		}
		if ('/' != \substr($mask, -1)) {
			$mask = $mask . '/';
		}
		# Если тип запроса существует
		if (isset($this->arr_router_settings[$type])) {
			# Заносим обработчик маршрута
			$this->arr_router_settings[$type][$mask] = $action;
		}
	}





	/** Маршрут для всех типов запросов */
	public function any($mask, $action = null) {
		# Проходим по массиву типов запросов
		foreach($this->arr_router_settings as $k => $v) {
			# Заносим обработчик маршрута
			$this->_add_router($k, $mask, $action);
		}
	}





	/** Маршрут для GET */
	public function get($mask, $action = null) {
		# Заносим обработчик маршрута
		$this->_add_router('GET', $mask, $action);
	}





	/** Маршрут для POST */
	public function post ($mask, $action = null) {
		# Заносим обработчик маршрута
		$this->_add_router('POST', $mask, $action);
	}





	/** Выводим список зарегиситрированных маршрутов */
	public function get_group($num = 1) {
		if (isset($this->arr_router[$num])) {
			return $this->arr_router[$num];
		} else {
			return false;
		}
	}





	/** Выводим список зарегиситрированных маршрутов */
	public function get_set() {
		\print_info($this->_arr_arg, 'Зарегистрированные переменные', false, true);
		# Выводим зарегистрированные маршруты
		\print_info($this->arr_router_settings, 'Зарегистрированные маршруты', false, true);
		# Выводим зарегистрированные маршруты по умолчанию
		\print_info($this->router_default, 'Зарегистрированные маршруты по умолчанию', false, true);
	}





	/** Обработки текущего маршрута */
	public function router() {
		# Вывод информации по маршрутам
//		$this->get_set();
		$argv = [];
		# Проходим по всем маршрутам метода
		foreach ($this->arr_router_settings[$this->method] as $k => $v) {
			$reg_data = $this->_create_reg($k);
			$reg = $reg_data[0];
			$reg_key = $reg_data[1];
			if (preg_match($reg, $this->_route)) {
				preg_match_all($reg, $this->_route, $arg);
				# Удаляем первый элемент, т.к. это строка адреса
				\array_shift($arg);
				foreach($arg as $k_2 => $v_2) {
					if ($reg_key[$k_2]) {
						$argv[$reg_key[$k_2]] = $v_2[0];
					} else {
						$argv[] = $v_2[0];
					}
				}
				$arr_content = $this->_router_output($v, $argv);
				# Вызываем обработчик переменной / функции маршрута
				if ($arr_content[0]) {
					return $arr_content[1];
				} else {
\print_error($arr_content[0], 'Ошибка router');
					return $this->_router_output($this->router_default[$this->method])[1];
				}
			}
		}
		return $this->_router_output($this->router_default[$this->method])[1];
	}





	/** Обработка переменной/функции маршрута */
	private function _router_output($value, $argv = []) {
		# Если передана - false
		if (in_array($value, [false, null/*, ''*/])) {
			# Возвращаем пустое значение
			return [false, ''];
		# Если передана строка -> функция или метод
		} elseif (\is_string($value)) {
			# Разбиваем строку на массив
			$_arr_r = explode('@',$value);
			# Формируем имя файла
			$file_name = _ELBRUS_FOLDER . $_arr_r[0] . '.php';
			# Если файл отсутстует
			if (!\file_exists($file_name)) {
				# Возвращаем сообщение
				return [false, 'Отсутствует файл класса ' . $file_name];
			}
			# Подгружаем файл класса
			include_once ($file_name);
			# Формируем имя класса
			$class_name = '\\' . \str_replace('/', '\\', $_arr_r[0]);
			# Если класс отсутстует
			if (!\class_exists($class_name)) {
				# Возвращаем сообщение
				return [false, 'Отсутствует класс ' . $class_name];
			}
			# Формируем имя метода
			$method_name = $_arr_r[1];
			# Если метод отсутстует
			if (!\method_exists($class_name , $method_name)) {
				# Возвращаем сообщение
				return [false, 'Отсутствует метод класса ' . $class_name . ' ---> ' . $method_name];
			}
			# Начинаем обработку функции
			$method = new \ReflectionMethod($class_name, $method_name);
			# Формируем список аргументов функции
			$argv = $this->_router_arg($argv, $method);
			# Если cуществует метод вызова call
			if (\method_exists($class_name , 'call')) {
				# Получаем объект через call
				$obj_object = $class_name::call();
			# Иначе
			} else {
				# Создаём объект
				$obj_object = new $class_name();
			}
			# Выполняем метод из контрольного маршрута с переданными аргументами
			$content = $method->invokeArgs($obj_object, $argv);
			return [true, $content];
		# Обработка переданной функции
		} else {
			# Начинаем обработку функции
			$function = new \ReflectionFunction($value);
			# Формируем список аргументов функции
			$argv = $this->_router_arg($argv, $function);
			# Выполняем функцию из контрольного маршрута с переданными аргументами
			$content = $function->invokeArgs($argv);
			return [true, $content];
			# Возвращаем true
		}
		# Возвращаем false
		return [false, null];
	}





	/** Обработка переменной/функции маршрута */
	private function _router_arg($arr_arg, $obj_method) {
		# Получаем список аргументов объекта (функции/метода)
		$params = $obj_method->getParameters();
		$_argv = [];
		# Проходим по всем аргументам
		foreach($params as $k => $v) {
			# Получаем имя параметра
			$param_name = $v->getName();
			# Проверяем, имеетли он значение по умолчанию
			if ($v->isOptional()) {
				# Записываем значение
				$_argv[$param_name] = $v->getDefaultValue();
			} else {
				# Записываем null
				$_argv[$param_name] = null;
			}
		}
		# Проводим ряд с массивами, что бы присвоить значения соответствующим аргументам
		# Сводный массив переменных и аргументов
		$merge = \array_merge($_argv, $arr_arg);
		# Временный результат с пустыми аргументами
		$_result = \array_intersect_key($merge, $_argv);
		# Значения неприсвоенные аргументам
		$dif_1 = \array_diff_key($merge, $_argv);
		# Пустые аргументы функции
		$dif_control = \array_diff_key($_argv, $arr_arg);
		# Заполняем пустые аргументы
		foreach($dif_control as $k => $v) {
			$val = array_shift($dif_1);
			if (null !== $val) {
				$_result[$k] = $val;
			}
		};
		return $_result;
	}





	/** Задаём страницу по умолчанию */
	public function default_page($value, $type = false) {
		# Переводим все буквы в верхний регистр
		$type = \strtoupper($type);
		# Если существует указанный метод
		if (isset($this->router_default[$type])) {
			# Заносим переменную в указанный тип
			$this->router_default[$type] = $value;
			# Возвращаем объект
			return $this;
		}
		# Проходим по всем методам
		foreach($this->router_default as $k => $v) {
			# Заносим переменную в указанный тип
			$this->router_default[$k] = $value;
		}
		# Возвращаем объект
		return $this;
	}





	/** Задаём страницу по умолчанию */
	public function _create_reg($mask) {
		# Получаем маску маршрута
		$arr_key = [];
		# Разбиваем на массив по '/'
		$arr_mask = \explode('/', $mask);
		# Удаляем все пустые элементы
		$arr_mask = \array_diff($arr_mask, [null, false, '']);
		# Проходим по элементам
		foreach ($arr_mask as $k => $v) {
			# Проверяем наличие переменной по наличию фигурных скобок
			if ('{' == \substr($v, 0, 1)
					&& '}' == substr($v, -1, 1)) {
				# Получаем ключ/имя переменной
				$key = \substr($v, 1, strlen($v)-2);
				# Заносим ключ в массив
				$arr_key[] = $key;
				# Проверяем наличие регулярного выражения для имени переменной
				if (\array_key_exists($key, $this->_arr_arg)) {
					# Записываем заданное регулярное
					$arr_mask[$k] = '(' . $this->_arr_arg[$key] . ')';
				} else {
					# Записываем регулярное выражение по умолчанию
					$arr_mask[$k] = '(' . $this->_arg_def . ')';
				}
			}
		}
		if (count($arr_mask)) {
			# Формируем отвер функции (Регулярное выражение + массив имён переменных)
			$result = [
				'/^\/' . implode('\/', $arr_mask) . '\/$/iu',
				$arr_key,
			];
		} else {
			# Формируем отвер функции (Регулярное выражение + массив имён переменных)
			$result = [
				'/^\/$/iu',
				$arr_key,
			];
		}
		# Возвращаем объект
		return $result;
	}





/**/
}





/**/
