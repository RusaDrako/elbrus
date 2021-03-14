<?php

namespace Elbrus\Framework;





/** Команда на загрузку класса request (данные запроса)
 * @author Петухов Леонид <l.petuhov@okonti.ru>
 * @package core
 */
class core_cmd_right implements _inf\inf_core_cmd {



	/** Автоматически выполняемый метод
	 * @param \null\string\array $set Настройки настройки. По умолчанию NULL
	 * @return \void
	 */
	public function execute($set = null) {
		require_once(__DIR__ . '/right/autoload.php');
		\right::call();
	}





/**/
}
