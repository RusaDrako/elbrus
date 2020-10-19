<?php

namespace core;





/** Команда на загрузку класса request (данные запроса)
 * @author Петухов Леонид <l.petuhov@okonti.ru>
 * @package core
 */
class core_cmd_log_time implements _inf\inf_core_cmd {



	/** Автоматически выполняемый метод
	 * @param \null\string\array $set Настройки настройки. По умолчанию NULL
	 * @return \void
	 */
	public function execute($set = null) {
		require_once(__DIR__ . '/log_time/log_time.php');
	}





/**/
}
