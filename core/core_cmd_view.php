<?php

namespace core;





/** Команда на загрузку базового шаблонизатора
 * @author Петухов Леонид <l.petuhov@okonti.ru>
 * @package core
 */
class core_cmd_view implements _inf\inf_core_cmd {



	/** Автоматически выполняемый метод
	 * @param \null\string\array $set Настройки настройки. По умолчанию NULL
	 * @return \void
	 */
	public function execute($set = null) {
		require_once(__DIR__ . '/view/view.php');
//		\view::call('smarty');
	}





/**/
}
