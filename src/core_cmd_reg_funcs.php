<?php

namespace Elbrus\Framework;





/**
 * @author Петухов Леонид <rusadrako@yandex.ru>
 * @package core
 */
class core_cmd_reg_funcs implements _inf\inf_core_cmd {



	/** Автоматически выполняемый метод
	 * @param null\string\array $set Настройки настройки. По умолчанию NULL
	 * @return void
	 */
	public function execute($set = null) {
		require_once('core_funcs.php');
		require_once('core_funcs_shutdown.php');
	}





/**/
}
