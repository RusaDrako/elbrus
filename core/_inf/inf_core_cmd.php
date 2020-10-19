<?php #!/usr/bin/env php

namespace core\_inf;





/** Интерфейс класса команды ядра */
interface inf_core_cmd {
	/** Автоматически выполняемый метод
	 * @param null\string\array $set Настройки настройки. По умолчанию NULL
	 * @return void
	 */
	public function execute($set = null);
}
