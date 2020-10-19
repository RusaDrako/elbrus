<?php

use PHPUnit\Framework\TestCase;

require_once('right.php');





/** Класс-тестировщик для request
 * @author Петухов Леонид <l.petuhov@okonti.ru>
 */
class right_Test extends TestCase {
	/** Тестируемый объект */
	private $_test_object = null;





	/** Вызывается перед каждым запуском тестового метода */
	protected function setUp() : void {
		$this->_test_object = right::call();
/*		$this->_test_object->right('admin', true);
		$this->_test_object->right('manager', true);
		$this->_test_object->right('user_1', true);
		$this->_test_object->right('user_2', true);*/
	}





	/** Вызывается после каждого запуска тестового метода */
	protected function tearDown() : void {}





	/** Данные для прогона тестов */
	public function test_access_provider() {
		# Данная проверка для того, что бы не было вывода предупреждения типа R
		$this->assertEquals(1,1);

		$right_array = [
			'admin' => true,
			'manager' => false,
			'user_1' => function () { return true;},
			'user_2' => function () { return false;},
			'user_3' => function () { return 1;},
			'user_4' => function () { return 0;},
		];
		return [
			# Тест - bool
			'test_admin'				=> [
				$right_array,
				'admin',
				true,
			],
			# Тест - bool
			'test_manager'				=> [
				$right_array,
				'manager',
				false,
			],
			# Тест - function
			'test_user_1'				=> [
				$right_array,
				'user_1',
				true,
			],
			# Тест - function
			'test_user_2'				=> [
				$right_array,
				'user_2',
				false,
			],
			# Тест - integer
			'test_user_3'				=> [
				$right_array,
				'user_3',
				true,
			],
			# Тест - integer
			'test_user_4'				=> [
				$right_array,
				'user_4',
				false,
			],
			# Тест - массив - строка
			'test_admin_manager'				=> [
				$right_array,
				'admin,manager',
				true,
			],
			# Тест - массив - строка
			'test_manager_user_1'				=> [
				$right_array,
				'manager,user_1',
				true,
			],
			# Тест - массив
			'test_manager_user_2'				=> [
				$right_array,
				['manager','user_2','user_4'],
				false,
			],
			# Тест - массив
			'test_manager_user_3'				=> [
				$right_array,
				['manager','user_3','user_4'],
				true,
			],

		];
	}





	/** Тестирует разрешение на доступ
	 * * @dataProvider test_access_provider
	 */
	public function test_access($array_right, $right, $result) {
//print_r($array_right);
		$this->_test_object->right_array($array_right);
		### Получение данных из пустых переменных
		$this->assertEquals(
			$this->_test_object->access($right)
			,$result
			,'Неожиданный ответ функции'
		);

	}





	/** Тестирует отказ в доступе
	 * * @dataProvider test_access_provider
	 */
	public function test_access_denied($array_right, $right, $result) {
		$this->_test_object->right_array($array_right);
		### Получение данных из пустых переменных
		$this->assertEquals(
			$this->_test_object->access_denied($right)
			,!$result
			,'Неправильный ответ функции'
		);

	}





	/** Тестирует разрешение на доступ
	 * * @dataProvider test_access_provider
	 */
	public function test_access_except($array_right, $right, $result) {
//print_r($array_right);
		$this->_test_object->right_array($array_right);
		### Получение данных из пустых переменных
		$this->assertEquals(
			$this->_test_object->access_except($right)
			,!$result
			,'Неожиданный ответ функции'
		);

	}





	/** Тестирует отказ в доступе
	 * * @dataProvider test_access_provider
	 */
	public function test_access_denied_except($array_right, $right, $result) {
		$this->_test_object->right_array($array_right);
		### Получение данных из пустых переменных
		$this->assertEquals(
			$this->_test_object->access_denied_except($right)
			,$result
			,'Неправильный ответ функции'
		);

	}





	/** Тестирует присвоение прав
	 */
	public function test_right_lock() {
		$right = 'admin';
		$this->_test_object->right($right, 1);
		### Получение данных из пустых переменных
		$this->assertEquals(
			$this->_test_object->access($right)
			,true
			,'Неправильный ответ функции'
		);
		$this->_test_object->right($right, 0, 1);
		### Получение данных из пустых переменных
		$this->assertEquals(
			$this->_test_object->access($right)
			,false
			,'Неправильный ответ функции'
		);
		$this->_test_object->right($right, 1);
		### Получение данных из пустых переменных
		$this->assertEquals(
			$this->_test_object->access($right)
			,false
			,'Неправильный ответ функции'
		);
	}





/**/
}
