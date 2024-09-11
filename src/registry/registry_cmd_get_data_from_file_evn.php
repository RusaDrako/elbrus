<?php

namespace Elbrus\Framework\registry;



class registry_cmd_get_data_from_file_evn implements _inf\inf_registry_cmd {



	/** Заполняет/обновляет ключ регистра
	 * @param string $set Имя файла
	 * @return array Набор ключей со значениями
	 */
	public function execute($set) {
		$result = [];
		# Проверка на существование файла
		if (file_exists($set)) {
			$result = $this->_file_read($set);
		}
		return $result;
	}



	/** Считывание и обработка данных из файла
	 * @param string $file_name Имя файла
	 * @return array Набор ключей со значениями
	 */
	private function _file_read($file_name) {
		$result = [];
		if ($handle = fopen($file_name, "r")) {
			while (($buffer = fgets($handle)) !== false) {
				$arr_buffer = explode('#', $buffer);
				$arr_key_val = explode('=', $arr_buffer[0]);
				if (count($arr_key_val) > 1) {
					$key = array_shift($arr_key_val);
					$value =  trim(implode('=', $arr_key_val));
					$_v = mb_strtolower($value);
					# Перевод строковых значений в bool и null
					switch ($_v) {
						case 'true':	$value = true;		break;
						case 'false':	$value = false;		break;
						case '':		$value = false;		break;
						case 'null':	$value = null;		break;
					}
					$result[$key] = $value;
				}
			}
			fclose($handle);
		}
		return $result;
	}



/**/
}
