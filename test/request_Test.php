<?php

use PHPUnit\Framework\TestCase;

require_once(__DIR__ . '/../src/request/autoload.php');





/** Класс-тестировщик для request
 * @author Петухов Леонид <rusadrako@yandex.ru>
 */
class request_Test extends TestCase {
	/** Тестируемый объект */
	private $_test_object = null;





	/** Вызывается перед каждым запуском тестового метода */
	protected function setUp() : void {
		$this->_test_object = request::call();
		# Эметируем переменные входящего запроса
		$this->set_protected_property(
				$this->_test_object,
				'_request',
				['input_1' => 'data_1', 'input_2' => 'data_2']
				);
	}





	/** Вызывается после каждого запуска тестового метода */
	protected function tearDown() : void {}





	/** Метод присваивания значения защищённому свойству объекта
	 *
	 * @param $object - instance in which protected value is being modified
	 * @param $property - property on instance being modified
	 * @param $value - new value of the property being modified
	 *
	 * @return void
	 */
	public function set_protected_property($object, $property, $value) {
		$reflection = new ReflectionClass($object);
		$reflection_property = $reflection->getProperty($property);
		$reflection_property->setAccessible(true);
		$reflection_property->setValue($object, $value);
	}




	/** Тестирует получение свойств */
	public function test_get() {
		### Получение данных из переменных входящего запроса
		# Значение по умолчанию не указано
		$this->assertEquals(
			$this->_test_object->get('input_1'),
			'data_1');
		# Значение по умолчанию указано
		$this->assertEquals(
			$this->_test_object->get('input_2', 'data_null'),
			'data_2');

		### Получение данных из пустых переменных
		# Значение по умолчанию не указано
		$this->assertEquals(
			$this->_test_object->get('input_3'),
			null);
		# Значение по умолчанию указано
		$this->assertEquals(
			$this->_test_object->get('input_3', 'data_null'),
			'data_null');

	}





/**/
}
