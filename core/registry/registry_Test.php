<?php

use PHPUnit\Framework\TestCase;

require_once('registry.php');





/** Класс-тестировщик для request
 * @author Петухов Леонид <l.petuhov@okonti.ru>
 */
class registry_Test extends TestCase {
	/** Тестируемый объект */
	private $_test_object = null;





	/** Вызывается перед каждым запуском тестового метода */
	protected function setUp() : void {
		$this->_test_object = registry::call();
	}





	/** Вызывается после каждого запуска тестового метода */
	protected function tearDown() : void {}





	/** Тестирует добавление свойств */
	public function test_set() {
		### Добавляем значения переменной (блокировано)
		# Добавляем ключ
		$this->assertTrue(
			$this->_test_object->set('test', 'data_1')
			,'Ключ не добавлен'
		);
		# Обновляем ключ
		$this->assertTrue(
			$this->_test_object->set('test', 'data_2')
			,'Ключ не обновлён'
		);
		# Обновляем ключ и блокируем его
		$this->assertTrue(
			$this->_test_object->set('test', 'data_2', true)
			,'Ключ не обновлён'
		);
		# Не можем обновить ключ, т.к. блокированное свойство
		$this->assertFalse(
			$this->_test_object->set('test', 'data_3', true)
			,'Обновили блокированный ключ'
		);
	}





	/** Тестирует получение свойств */
	public function test_get() {
		### Добавляем значения ключей
		$this->_test_object->set('test_1', 'data_1');
		$this->_test_object->set('test_2', 'data_2');

		### Получение данных из переменных входящего запроса
		$this->assertEquals(
			$this->_test_object->get('test_1')
			,'data_1'
			,'Ключ есть, значение по умолчанию не указано'
		);
		$this->assertEquals(
			$this->_test_object->get('test_2', 'data_null')
			,'data_2'
			,'Ключ есть, значение по умолчанию указано'
		);

		### Получение данных из пустых переменных
		$this->assertEquals(
			$this->_test_object->get('test_3')
			,null
			,'Ключа нет, значение по умолчанию не указано'
		);
		$this->assertEquals(
			$this->_test_object->get('test_3', 'data_null')
			,'data_null'
			,'Ключа нет, значение по умолчанию указано'
		);

	}





/**/
}




/**/
?>
