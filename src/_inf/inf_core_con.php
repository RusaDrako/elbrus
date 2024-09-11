<?php #!/usr/bin/env php

namespace Elbrus\Framework\_inf;

/** Интерфейс класса контроллера команд ядра */
interface inf_core_con {

	/** Осуществляет вызов команды
	 * @param string $class_name Имя класса команды.
	 * @param null\string\array $set Настройки настройки. По умолчанию NULL
	 * @return void
	 */
	public function cmd($class_name, $set = null);

}
